<?php
 
namespace App\Http\Controllers;
use App\Models\Pages;
use App\Models\Advertisment;
use App\Models\Category;
use App\Models\City;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
 
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
        return view('pages.category');
    }
    public function tasks(Request $request): View
    {
        $query = Advertisment::query()->with(['category', 'employer.city']);
 
        // Search by city name
        if ($request->filled('q')) {
            $search = trim($request->string('q'));
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

        // Type filter (optional placeholder: in-person/remote) - skipping until schema supports
 
        // Sorting
        $sort = $request->string('sort', 'recent');
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
                'q' => $request->string('q')->toString(),
                'category' => $request->input('category'),
                'sort' => $sort,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'distance' => (int) $request->input('distance', 20),
            ],
        ]);
    }
    public function howitworks(): View
    {
        return view('pages.howitworks');
    }
 
}