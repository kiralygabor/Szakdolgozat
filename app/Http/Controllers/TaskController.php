<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService) {}

    public function index(Request $request): View
    {
        $query = Advertisement::open()->with(['category', 'employer.city', 'offers']);

        if ($request->filled('q')) {
            $query->search(trim($request->query('q')));
        }

        $minPrice = max(5, (int) $request->input('min_price', 5));
        $maxPrice = min(5000, (int) $request->input('max_price', 5000));
        $query->whereBetween('price', [$minPrice, $maxPrice]);

        $tasks = $query->latest()->paginate(20)->withQueryString();

        return view('pages.tasks', [
            'tasks' => $tasks,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'filters' => array_merge($request->all(), [
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
            ]),
        ]);
    }

    public function create(Request $request): View
    {
        $categories = Category::with('jobs')->orderBy('name')->get();
        $targetUser = $request->filled('for_user')
            ? User::find($request->input('for_user'))
            : null;

        return view('pages.post-task', compact('categories', 'targetUser'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->taskService->createTask($request->validated(), Auth::id());

        return redirect()->route('my-tasks')
            ->with('success', __('Task posted successfully!'));
    }

    public function show(Advertisement $task): View
    {
        return view('pages.task-details', compact('task'));
    }

    public function update(StoreTaskRequest $request, Advertisement $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return back()->with('success', __('Task updated successfully.'));
    }

    public function destroy(Advertisement $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return back()->with('success', __('Task removed successfully.'));
    }

    public function complete(Request $request, Advertisement $task): RedirectResponse
    {
        $this->authorize('complete', $task);

        $task->update(['status' => TaskStatus::Completed]);

        if ($request->filled('stars')) {
            $this->storeCompletionReview($request, $task);
        }

        return back()->with('success', __('Task marked as completed!'));
    }

    private function storeCompletionReview(Request $request, Advertisement $task): void
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        if ($task->hasEmployee()) {
            Review::create([
                'reviewer_id' => Auth::id(),
                'target_user_id' => $task->employee_id,
                'stars' => $request->input('stars'),
                'comment' => $request->input('comment'),
            ]);
        }
    }
}
