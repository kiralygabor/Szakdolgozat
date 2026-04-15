<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\City;
use App\Models\Job;
use App\Models\Review;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    private const SHOWCASE_ITEMS_PER_CATEGORY = 6;
    private const ACTIVE_TASKS_LIMIT = 3;
    private const MIN_SEARCH_LENGTH = 2;
    private const CITY_RESULTS_LIMIT = 20;
    private const ITEMS_PER_PAGE = 20;

    private const EXCLUDED_CATEGORIES = [
        'Renovations & Construction',
        'Events',
        'Renovation & Construction',
        'Events & Creative',
    ];

    public function index(): View
    {
        $categories = Category::whereNotIn('name', self::EXCLUDED_CATEGORIES)
            ->whereHas('advertisements', function ($query) {
                $query->open();
            })
            ->with(['advertisements' => function ($query) {
                $query->open()->with(['employer.city'])->orderByDesc('created_at');
            }])
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return [
                    'category' => $category,
                    'jobs' => $category->advertisements->take(self::SHOWCASE_ITEMS_PER_CATEGORY),
                ];
            });

        return view('pages.index', [
            'categoriesWithJobs' => $categories,
        ]);
    }

    public function category(): View
    {
        $categories = Category::orderByRaw("name = 'Other' ASC, name ASC")->get();
        $jobsByCategory = Job::whereIn('categories_id', $categories->pluck('id'))
            ->orderByRaw("name = 'Other' ASC, name ASC")
            ->get()
            ->groupBy('categories_id');

        return view('pages.category', compact('categories', 'jobsByCategory'));
    }

    public function myTasks(Request $request): View
    {
        $userId = Auth::id();
        $viewMode = $request->query('view', 'posted');
        $status = (string) $request->query('status', 'posted');

        $tasks = Advertisement::query()
            ->with(['job.category', 'employer.city', 'offers.user', 'employee'])
            ->withCount(['offers', 'distinctViews as views_count'])
            ->forMyTasks($userId, $viewMode)
            ->byStatusFilter($status)
            ->latest()
            ->paginate(self::ITEMS_PER_PAGE)
            ->withQueryString();

        $focusedTask = null;
        if ($request->has('task_id')) {
            $focusedId = (int) $request->query('task_id');
            // Try to find it in the current set first for efficiency
            $focusedTask = $tasks->getCollection()->firstWhere('id', $focusedId);
            
            // If not in current page, fetch it specifically (optional, but good for UX)
            if (!$focusedTask) {
                $focusedTask = Advertisement::with(['job.category', 'employer.city', 'offers.user', 'employee'])
                    ->withCount(['offers', 'distinctViews as views_count'])
                    ->find($focusedId);
            }
        }

        $allCategories = Category::with('jobs')->orderBy('name')->get();
        
        return view('pages.mytasks', [
            'tasks' => $tasks,
            'focusedTask' => $focusedTask,
            'allCategories' => $allCategories,
            'viewMode' => $viewMode,
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'status' => $status,
                'view' => $viewMode,
            ],
        ]);

    }

    public function notifications(): View
    {
        $notifications = Auth::user()->unreadNotifications()->latest()->get();

        return view('pages.notifications', compact('notifications'));
    }

    public function publicProfile($id): View
    {
        $user = User::with(['city', 'reviewsReceived.reviewer'])->findOrFail((int) $id);
        $activeTasks = Advertisement::where('employer_id', $user->id)
            ->open()
            ->latest()
            ->take(self::ACTIVE_TASKS_LIMIT)
            ->get();

        $canReview = false;
        if (Auth::check() && Auth::id() !== $user->id) {
            $alreadyReviewed = Review::where('reviewer_id', Auth::id())
                ->where('target_user_id', $user->id)
                ->exists();

            if (!$alreadyReviewed) {
                $canReview = Advertisement::where(function($q) use ($user) {
                    $q->where('employer_id', $user->id)->where('employee_id', Auth::id());
                })->orWhere(function($q) use ($user) {
                    $q->where('employer_id', Auth::id())->where('employee_id', $user->id);
                })->where('status', TaskStatus::Completed)->exists();
            }
        }

        return view('pages.public-profile', [
            'user' => $user,
            'activeTasks' => $activeTasks,
            'reviews' => $user->reviewsReceived()->latest()->get(),
            'canReview' => $canReview,
        ]);
    }

    public function searchCities(Request $request): JsonResponse
    {
        $search = $request->string('q', '');

        if (strlen($search) < self::MIN_SEARCH_LENGTH) {
            return response()->json([]);
        }

        $cities = City::where('name', 'like', $search . '%')
            ->orWhere('name', 'like', '% ' . $search . '%')
            ->orderBy('name')
            ->limit(self::CITY_RESULTS_LIMIT)
            ->get(['id', 'name', 'county_id']);

        return response()->json($cities);
    }

    public function storeReview(\App\Http\Requests\Task\ReviewEmployerRequest $request, $id): RedirectResponse
    {
        $request->validated();

        $targetUserId = (int) $id;

        // Prevent self-reviews
        if (Auth::id() === $targetUserId) {
            return back()->with('error', __('You cannot review yourself.'));
        }

        // Prevent duplicate reviews
        $alreadyReviewed = Review::where('reviewer_id', Auth::id())
            ->where('target_user_id', $targetUserId)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', __('You have already reviewed this user.'));
        }

        // Verify they have done a job together
        $hasCompletedJob = Advertisement::where(function($q) use ($targetUserId) {
                $q->where('employer_id', $targetUserId)->where('employee_id', Auth::id());
            })->orWhere(function($q) use ($targetUserId) {
                $q->where('employer_id', Auth::id())->where('employee_id', $targetUserId);
            })->where('status', TaskStatus::Completed)->exists();

        if (!$hasCompletedJob) {
            return back()->with('error', __('You must complete at least one job with this user to leave a review.'));
        }

        Review::create([
            'reviewer_id' => Auth::id(),
            'target_user_id' => $targetUserId,
            'stars' => $request->input('stars'),
            'comment' => $request->input('comment'),
        ]);

        return back()->with('success', __('Review submitted successfully.'));
    }

    public function markAllRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}
