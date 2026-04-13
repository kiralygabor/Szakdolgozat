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
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    /**
     * Home page with category/job showcase.
     */
    public function index(): View
    {
        $excludedCategories = ['Renovations & Construction', 'Events', 'Renovation & Construction', 'Events & Creative'];

        $categories = Category::whereNotIn('name', $excludedCategories)
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
                    'jobs' => $category->advertisements->take(6),
                ];
            });

        return view('pages.index', [
            'categoriesWithJobs' => $categories,
        ]);
    }

    /**
     * Browse categories.
     */
    public function category(): View
    {
        $categories = Category::orderByRaw("name = 'Other' ASC, name ASC")->get();
        $jobsByCategory = Job::whereIn('categories_id', $categories->pluck('id'))
            ->orderByRaw("name = 'Other' ASC, name ASC")
            ->get()
            ->groupBy('categories_id');

        return view('pages.category', compact('categories', 'jobsByCategory'));
    }

    /**
     * Display the My Tasks dashboard.
     */
    public function myTasks(Request $request): View
    {
        $userId = Auth::id();
        $viewMode = $request->query('view', 'posted');
        $status = (string) $request->query('status', 'posted');

        $query = Advertisement::query()
            ->with(['job.category', 'employer.city', 'offers.user', 'employee'])
            ->withCount(['offers', 'distinctViews as views_count']);

        // Context Switching (Posted, Applied, Direct)
        if ($viewMode === 'applied') {
            $query->whereHas('offers', fn($q) => $q->where('user_id', $userId));
        } elseif ($viewMode === 'direct') {
            $query->where('is_direct', true)->where(fn($q) => $q->where('employer_id', $userId)->orWhere('employee_id', $userId));
        } else {
            $query->where('employer_id', $userId)->where('is_direct', false);
        }

        // Status Filtering
        if ($status === 'pending' || $status === 'assigned') {
            $query->where('status', TaskStatus::Assigned);
        } elseif ($status === 'completed') {
            $query->where('status', TaskStatus::Completed);
        } else {
            $query->where('status', TaskStatus::Open);
        }

        $tasks = $query->latest()->paginate(20)->withQueryString();

        return view('pages.mytasks', [
            'tasks' => $tasks,
            'viewMode' => $viewMode,
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'status' => $status,
                'view' => $viewMode,
            ],
        ]);
    }

    /**
     * External notifications view.
     */
    public function notifications(): View
    {
        $notifications = Auth::user()->notifications()->latest()->get();
        return view('pages.notifications', compact('notifications'));
    }

    /**
     * Public user profile show page.
     */
    public function publicProfile($id): View
    {
        $user = User::with(['city', 'reviewsReceived.reviewer'])->findOrFail($id);
        
        // Active tasks by this user
        $activeTasks = Advertisement::where('employer_id', $user->id)->open()->latest()->take(3)->get();

        return view('pages.public-profile', [
            'user' => $user,
            'activeTasks' => $activeTasks,
            'reviews' => $user->reviewsReceived()->latest()->get(),
        ]);
    }

    /**
     * AJAX City Search endpoint.
     */
    public function searchCities(Request $request): JsonResponse
    {
        $search = $request->string('q', '');
        if (strlen($search) < 2) return response()->json([]);

        $cities = City::where('name', 'like', $search . '%')
            ->orWhere('name', 'like', '% ' . $search . '%')
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'county_id']);

        return response()->json($cities);
    }

    /**
     * Helper to store a profile review.
     */
    public function storeReview(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        Review::create([
            'reviewer_id' => Auth::id(),
            'target_user_id' => $id,
            'stars' => $request->input('stars'),
            'comment' => $request->input('comment'),
        ]);

        return back()->with('success', __('Review submitted successfully.'));
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
