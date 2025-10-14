<?php
 
namespace App\Http\Controllers;
use App\Models\Pages;
use App\Models\Advertisment;
use App\Models\Category;
use App\Models\City;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
 
class PagesController extends Controller
{
    public function index() : View
    {
        return view('pages.index');
    }
 
    public function mainpage(): View
    {
        return view('pages.mainpage');
    }
     public function profile(): View
    {
        return view('pages.profile');
    }
 
    public function category(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('pages.category', [
            'categories' => $categories,
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
        ]);
    }

    public function searchCities(Request $request): JsonResponse
    {
        $search = $request->string('q', '');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $q = (string) $search;

        // Return DISTINCT names that contain the query, exact match (case-insensitive) first
        $cities = City::selectRaw('MIN(id) as id, name')
            ->where('name', 'like', "%{$q}%")
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
 
}