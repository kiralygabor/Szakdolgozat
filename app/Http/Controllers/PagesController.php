<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\City;
use App\Models\Job;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    public function index() : View
    {
        // Get categories excluding "Renovations & Construction" and "Events"
        $excludedCategories = ['Renovations & Construction', 'Events', 'Renovation & Construction', 'Events & Creative'];
        $categories = Category::whereNotIn('name', $excludedCategories)
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                // Get 6 jobs for this category
                $jobs = Advertisement::where('categories_id', $category->id)
                    ->where('status', 'open')
                    ->with(['category', 'employer.city'])
                    ->orderByDesc('created_at')
                    ->limit(6)
                    ->get();

                return [
                    'category' => $category,
                    'jobs' => $jobs,
                ];
            })
            ->filter(function ($item) {
                // Only include categories that have at least one job
                return $item['jobs']->count() > 0;
            });

        return view('pages.index', [
            'categoriesWithJobs' => $categories,
        ]);
    }

    public function mainpage(Request $request): View
    {
        $query = Advertisement::where('status', 'open')->with(['category', 'employer.city']);

        if ($request->filled('q')) {
            $q = trim((string) $request->query('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('city_search')) {
            $search = trim((string) $request->query('city_search'));
            $query->where('location', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $categoryId = (int) $request->input('category');
            if ($categoryId > 0) {
                $query->where('categories_id', $categoryId);
            }
        }

        $minPrice = (int) $request->input('min_price', 1000);
        $maxPrice = (int) $request->input('max_price', 20000);
        $minPrice = max(1000, $minPrice);
        $maxPrice = min(20000, $maxPrice);
        if ($minPrice > $maxPrice) {
            [$minPrice, $maxPrice] = [$maxPrice, $minPrice];
        }
        if ($minPrice !== 1000 || $maxPrice !== 20000) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        $type = $request->string('type', 'all');
        if ($type === 'remote') {
            $query->where('location', 'like', '%remote%');
        } elseif ($type === 'in_person') {
            $query->where(function ($q) {
                $q->whereNull('location')
                  ->orWhere('location', '=','')
                  ->orWhere('location', 'not like', '%remote%');
            });
        }

        $sort = (string) $request->query('sort', 'recent');
        switch ($sort) {
            case 'closest':
                $query->orderBy('location', 'asc');
                break;
            case 'due':
                $query->orderBy('expiration_date');
                break;
            case 'lowest_price':
                $query->orderBy('price', 'asc');
                break;
            case 'highest_price':
                $query->orderBy('price', 'desc');
                break;
            case 'recent':
            default:
                $query->orderByDesc('created_at');
        }

        $tasks = $query->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('pages.mainpage', [
            'tasks' => $tasks,
            'categories' => $categories,
            'cities' => $cities,
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'city_search' => (string) $request->query('city_search', ''),
                'category' => $request->input('category'),
                'sort' => $sort,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'type' => $type,
            ],
        ]);
    }
     public function profile(): View
    {
        $user = Auth::user();
        $categories = Category::orderBy('name')->get();

        return view('pages.profile', [
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $request->validate([
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'birthdate'    => ['nullable', 'date', 'before:today'],
            'city_id'      => ['nullable', 'integer', 'exists:cities,id'],
            'avatar'       => ['nullable', 'image', 'max:5120'], // max 5MB
            'email_notifications' => ['nullable', 'boolean'],
            'email_task_digest' => ['nullable', 'boolean'],
            'tracked_categories' => ['nullable', 'array'],
            'tracked_categories.*' => ['integer', 'exists:categories,id'],
        ]);

        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->email        = $request->email;
        $user->phone_number = $request->phone_number;
        $user->birthdate    = $request->birthdate;
        $user->city_id      = $request->city_id;
        $user->email_notifications = $request->has('email_notifications');
        $user->email_task_digest = $request->has('email_task_digest');

        // Sync categories if digest is enabled
        if ($user->email_task_digest) {
            $user->trackedCategories()->sync($request->input('tracked_categories', []));
        } else {
            $user->trackedCategories()->detach();
        }

        if ($request->hasFile('avatar')) {
            // delete old avatar if exists and it's not the default asset or a Google URL
            if (!empty($user->avatar) && !str_starts_with($user->avatar, 'assets/') && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile')->with('success', __('profile_page.profile.update_success') ?? 'Profile updated successfully.');
    }

    public function deleteProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        // Delete avatar if exists and it's not a default asset or a Google URL
        if (!empty($user->avatar) && !str_starts_with($user->avatar, 'assets/') && !str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Perform deletion
        $user->delete();

        // Logout and clear session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index')->with('success', 'Account deleted successfully.');
    }

    public function category(): View
    {
        $categories = Category::orderBy('name')->get();
        $jobsByCategory = Job::whereIn('categories_id', $categories->pluck('id'))
            ->orderBy('name')
            ->get()
            ->groupBy('categories_id');

        return view('pages.category', [
            'categories' => $categories,
            'jobsByCategory' => $jobsByCategory,
        ]);
    }
    public function tasks(Request $request): View
    {
        $query = Advertisement::where('status', 'open')->with(['category', 'employer.city', 'offers']);

        // Multi-search by q (title, description, category name, city name)
        if ($request->filled('q')) {
            $q = trim((string) $request->query('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Explicit city filter (set from Work Type dropdown live search)
        if ($request->filled('city_search')) {
            $search = trim((string) $request->query('city_search'));
            $query->where('location', 'like', "%{$search}%");
        }

        // Filter by category
        if ($request->filled('category')) {
            $categoryId = (int) $request->input('category');
            if ($categoryId > 0) {
                $query->where('categories_id', $categoryId);
            }
        }

        // Filter by specific job
        if ($request->filled('job')) {
            $jobId = (int) $request->input('job');
            if ($jobId > 0) {
                $query->where('jobs_id', $jobId);
            }
        }

        // Filter by price range (clamped to 1000–20000)
        $minPrice = (int) $request->input('min_price', 1000);
        $maxPrice = (int) $request->input('max_price', 20000);
        $minPrice = max(1000, $minPrice);
        $maxPrice = min(20000, $maxPrice);
        if ($minPrice > $maxPrice) {
            // swap if inverted
            [$minPrice, $maxPrice] = [$maxPrice, $minPrice];
        }
        if ($minPrice !== 1000 || $maxPrice !== 20000) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        // Type filter (in_person | remote | all)
        $type = $request->string('type', 'all');
        if ($type === 'remote') {
            // Heuristic: location contains 'remote'
            $query->where('location', 'like', '%remote%');
        } elseif ($type === 'in_person') {
            $query->where(function ($q) {
                $q->whereNull('location')
                  ->orWhere('location', '=','')
                  ->orWhere('location', 'not like', '%remote%');
            });
        }

        // Sorting
        $sort = (string) $request->query('sort', 'recent');
        switch ($sort) {
            case 'closest':
                // Sort by location name A-Z
                $query->orderBy('location', 'asc');
                break;
            case 'due':
                $query->orderBy('expiration_date');
                break;
            case 'lowest_price':
                $query->orderBy('price', 'asc');
                break;
            case 'highest_price':
                $query->orderBy('price', 'desc');
                break;
            case 'recent':
            default:
                $query->orderByDesc('created_at');
        }

        $userId = Auth::id();
        if ($userId) {
            // Put tasks with my offers at the top
            $query->orderByRaw('(SELECT COUNT(*) FROM offers WHERE offers.advertisement_id = advertisements.id AND offers.user_id = ?) DESC', [$userId]);
        }

        $tasks = $query->paginate(20)->withQueryString();
        $categories = Category::with('jobs')->orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        $missingSteps = [];
        $user = Auth::user();

        if ($user) {
            if (empty($user->avatar)) {
                $missingSteps[] = 'Upload a profile picture';
            }
            if (empty($user->birthdate)) {
                $missingSteps[] = 'Add your date of birth';
            }
            if (empty($user->phone_number)) {
                $missingSteps[] = 'Verify your mobile';
            }
            if (empty($user->city_id)) {
                $missingSteps[] = 'Add your location';
            }
        }

        return view('pages.tasks', [
            'tasks' => $tasks,
            'categories' => $categories,
            'cities' => $cities,
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'city_search' => (string) $request->query('city_search', ''),
                'category' => $request->input('category'),
                'job' => $request->input('job'),
                'sort' => $sort,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'type' => $type,
            ],

            'missingSteps' => $missingSteps,
        ]);
    }

    public function searchCities(Request $request): JsonResponse
    {
        $countyId = (int) $request->input('county_id', 0);
        if ($countyId > 0) {
            $cities = City::where('county_id', $countyId)
                ->orderBy('name')
                ->get(['id', 'name']);
            return response()->json($cities);
        }

        $search = $request->string('q', '');
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $q = (string) $search;
        $cities = City::selectRaw('MIN(id) as id, name, MIN(county_id) as county_id')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', $q . '%')
                      ->orWhere('name', 'like', '% ' . $q . '%');
            })
            ->groupBy('name')
            ->orderByRaw('LOWER(name) = LOWER(?) DESC, name ASC', [$q])
            ->limit(20)
            ->get();

        return response()->json($cities);
    }

    public function myTasks(Request $request): View
    {
        $userId = Auth::id();
        $viewMode = $request->query('view', 'posted'); // 'posted' or 'applied'

        $query = Advertisement::query()
            ->with(['category', 'employer.city', 'offers.user'])
            ->withCount('offers');

        // Context Switch
        if ($viewMode === 'applied') {
            // Tasks I have applied to (where I have an offer)
            $query->whereHas('offers', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        } else {
            // Tasks I posted (default)
            $query->where('employer_id', $userId);
        }

        // Filter by task status (posted, pending, completed)
        $status = $request->string('status', '');

        // If no status is explicitly set, set default defaults per view mode
        if (empty($status)) {
            $status = 'posted'; // Default tab
        }

        switch ($status) {
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'completed':
                $query->where('status', 'completed'); 
                break;
            case 'posted':
            default:
                // For 'posted' tab, we usually mean 'open' tasks
                $query->where('status', 'open');
                break;
        }

        // Search functionality
        if ($request->filled('q')) {
            $q = trim((string) $request->query('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhereHas('category', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('employer.city', function ($city) use ($q) {
                        $city->where('name', 'like', "%{$q}%");
                    });
            });
        }

        // Sort by most recent first
        $query->orderByDesc('created_at');

        $tasks = $query->paginate(20)->withQueryString();

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

    public function notifications(): View
    {
        $notifications = Auth::user()->notifications()->latest()->get();
        return view('pages.notifications', compact('notifications'));
    }

    public function messages(): View
    {
        return view('pages.messages');
    }

    public function postTask(): View
    {
        $categories = Category::with(['jobs' => function($query) {
            $query->orderBy('name');
        }])->orderBy('name')->get();

        $otherJobId = Job::where('name', 'Other')->value('id');

        return view('pages.post-task', compact('categories', 'otherJobId'));
    }

    /**
     * Store a new task advertisement
     */
    public function storeTask(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('task-photos', 'public');
                $photos[] = $path;
            }
        }

        // Create the advertisement
        $advertisement = new Advertisement();
        $advertisement->fill($validated);
        $advertisement->photos = $photos;
        $advertisement->employer_id = Auth::id();
        $advertisement->created_at = now();
        $advertisement->expiration_date = now()->addDays(30); // Tasks expire in 30 days
        $advertisement->status = 'open';

        $advertisement->save();

        return redirect()->route('my-tasks')->with('success', 'Your task has been posted successfully!');
    }

    public function showTask($id): View
    {
        $task = Advertisement::with(['category', 'employer.city', 'offers.user'])->findOrFail($id);

        // Increment view count
        $task->increment('views');

        $missingSteps = [];
        $user = Auth::user();

        if ($user) {
            if (empty($user->avatar)) {
                $missingSteps[] = 'Upload a profile picture';
            }
            if (empty($user->birthdate)) {
                $missingSteps[] = 'Add your date of birth';
            }
            if (empty($user->phone_number)) {
                $missingSteps[] = 'Verify your mobile';
            }
            if (empty($user->city_id)) {
                $missingSteps[] = 'Add your location';
            }
        }

        return view('pages.task-details', [
            'task' => $task,
            'task' => $task,
            'missingSteps' => $missingSteps,
        ]);
    }

    public function markAllRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function publicProfile($id): View
    {
        $user = \App\Models\User::with(['city', 'reviewsReceived.reviewer'])->findOrFail($id);

        $canReview = false;
        if (Auth::check() && Auth::id() != $id) {
            $myId = Auth::id();
            $profileId = (int) $id;

            // Enforce: Reviews given <= Completed tasks together
            $completedTasksCount = \App\Models\Advertisement::whereIn('status', ['completed', 'Completed'])
                ->where(function($q) use ($myId, $profileId) {
                    $q->where(function($sq) use ($myId, $profileId) {
                        $sq->where('employer_id', $myId)->where('employee_id', $profileId);
                    })->orWhere(function($sq) use ($myId, $profileId) {
                        $sq->where('employer_id', $profileId)->where('employee_id', $myId);
                    });
                })->count();

            $givenReviewsCount = \App\Models\Review::where('reviewer_id', $myId)
                ->where('target_user_id', $profileId)
                ->count();

            $canReview = ($completedTasksCount > $givenReviewsCount);
        }

        return view('pages.public-profile', [
            'user' => $user,
            'reviews' => $user->reviewsReceived()->latest()->get(),
            'canReview' => $canReview
        ]);
    }

    public function storeReview(Request $request, $id): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::id() == $id) {
            return back()->with('error', 'You cannot review yourself.');
        }

        $myId = Auth::id();
        $targetId = (int) $id;

        // Double check limit in store method
        $completedTasksCount = \App\Models\Advertisement::whereIn('status', ['completed', 'Completed'])
            ->where(function($q) use ($myId, $targetId) {
                $q->where(function($sq) use ($myId, $targetId) {
                    $sq->where('employer_id', $myId)->where('employee_id', $targetId);
                })->orWhere(function($sq) use ($myId, $targetId) {
                    $sq->where('employer_id', $targetId)->where('employee_id', $myId);
                });
            })->count();

        $givenReviewsCount = \App\Models\Review::where('reviewer_id', $myId)
            ->where('target_user_id', $targetId)
            ->count();

        if ($givenReviewsCount >= $completedTasksCount) {
            return back()->with('error', 'You have already reviewed all completed tasks with this user.');
        }

        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:150',
        ]);

        \App\Models\Review::create([
             'reviewer_id' => Auth::id(),
             'target_user_id' => $id,
             'stars' => $request->stars,
             'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review posted!');
    }
}