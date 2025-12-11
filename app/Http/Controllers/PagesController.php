<?php
 
namespace App\Http\Controllers;

use App\Models\Pages;
use App\Models\Advertisment;
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
                $jobs = Advertisment::where('categories_id', $category->id)
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
        $query = Advertisment::query()->with(['category', 'employer.city']);

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

        if ($request->filled('city_search')) {
            $search = trim((string) $request->query('city_search'));
            $query->whereHas('employer.city', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
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
                $query->join('users as u', 'u.id', '=', 'advertisments.employer_id')
                      ->join('cities as c', 'c.id', '=', 'u.city_id')
                      ->orderBy('c.name')
                      ->select('advertisments.*');
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

        return view('pages.profile', [
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'birthdate'  => ['nullable', 'date', 'before:today'],
            'city_id'    => ['nullable', 'integer', 'exists:cities,id'],
            'avatar'     => ['nullable', 'image', 'max:5120'], // max 5MB
        ]);

        $user->first_name = $data['first_name'];
        $user->last_name  = $data['last_name'];
        $user->birthdate  = $data['birthdate'] ?? null;
        $user->city_id    = $data['city_id'] ?? null;

        if ($request->hasFile('avatar')) {
            // delete old avatar if exists
            if (!empty($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil sikeresen frissítve.');
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
        $query = Advertisment::query()->with(['category', 'employer.city']);

        // Multi-search by q (title, description, category name, city name)
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

        // Explicit city filter (set from Work Type dropdown live search)
        if ($request->filled('city_search')) {
            $search = trim((string) $request->query('city_search'));
            $query->whereHas('employer.city', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $categoryId = (int) $request->input('category');
            if ($categoryId > 0) {
                $query->where('categories_id', $categoryId);
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
                // Requires geo data; fallback to alphabetical city for now
                $query->join('users as u', 'u.id', '=', 'advertisments.employer_id')
                      ->join('cities as c', 'c.id', '=', 'u.city_id')
                      ->orderBy('c.name')
                      ->select('advertisments.*');
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

        $missingProfileSteps = [];
        $user = Auth::user();

        if ($user) {
            if (empty($user->first_name) || empty($user->last_name)) {
                $missingProfileSteps[] = 'Add your name';
            }
            if (empty($user->birthdate)) {
                $missingProfileSteps[] = 'Add your date of birth';
            }
            if (empty($user->phone_number)) {
                $missingProfileSteps[] = 'Verify your mobile number';
            }
            if (empty($user->city_id)) {
                $missingProfileSteps[] = 'Add your home suburb';
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
                'sort' => $sort,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'type' => $type,
            ],
            'missingProfileSteps' => $missingProfileSteps,
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

    public function howitworks(): View
    {
        return view('pages.howitworks');
    }

    public function myTasks(Request $request): View
    {
        $userId = Auth::id();

        $query = Advertisment::query()
            ->with(['category', 'employer.city', 'offers.user'])
            ->withCount('offers')
            ->where('employer_id', $userId);

        // Filter by task status (posted, pending, completed)
        $status = $request->string('status', 'posted');
        switch ($status) {
            case 'pending':
                // Tasks with pending offers (you'd need to add this logic based on your data structure)
                $query->where('status', 'pending');
                break;
            case 'completed':
                // Completed tasks
                $query->where('status', 'completed');
                break;
            case 'posted':
            default:
                // Posted tasks (open/active tasks)
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
            'filters' => [
                'q' => (string) $request->query('q', ''),
                'status' => $status,
            ],
        ]);
    }
    
    public function notifications(): View
    {
        return view('pages.notifications');
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
        $advertisement = new Advertisment();
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
        $task = Advertisment::with(['category', 'employer.city', 'offers.user'])->findOrFail($id);

        // Increment view count
        $task->increment('views');

        $missingProfileSteps = [];
        $user = Auth::user();

        if ($user) {
            if (empty($user->first_name) || empty($user->last_name)) {
                $missingProfileSteps[] = 'Add your name';
            }
            if (empty($user->birthdate)) {
                $missingProfileSteps[] = 'Add your date of birth';
            }
            if (empty($user->phone_number)) {
                $missingProfileSteps[] = 'Verify your mobile number';
            }
            if (empty($user->city_id)) {
                $missingProfileSteps[] = 'Add your home suburb';
            }
        }

        return view('pages.task-details', [
            'task' => $task,
            'missingProfileSteps' => $missingProfileSteps,
        ]);
    }

    public function markAllRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}