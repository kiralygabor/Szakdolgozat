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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TaskController extends Controller
{
    private const MIN_BOUND_PRICE = 5;
    private const MAX_BOUND_PRICE = 5000;
    private const ITEMS_PER_PAGE = 20;

    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(Request $request): View
    {
        $minPrice = $this->resolvePrice($request->input('min_price'), self::MIN_BOUND_PRICE);
        $maxPrice = $this->resolvePrice($request->input('max_price'), self::MAX_BOUND_PRICE);

        $tasks = Advertisement::open()
            ->with(['category', 'employer.city', 'offers'])
            ->applyFilters($request->query('q'), $minPrice, $maxPrice)
            ->forCategory($request->input('category'))
            ->latest()
            ->paginate(self::ITEMS_PER_PAGE)
            ->withQueryString();

        return view('pages.tasks', [
            'tasks' => $tasks,
            'categories' => $this->getCategoriesForFilter(),
            'filters' => array_merge($request->all(), compact('minPrice', 'maxPrice')),
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

        $view = $request->filled('employee_id') ? 'direct' : 'posted';

        return redirect()->route('my-tasks', ['view' => $view])
            ->with('success', __('Task posted successfully!'));
    }

    public function show(Advertisement $task): View
    {
        if (Auth::check() && Auth::id() !== $task->employer_id) {
            \App\Models\AdvertisementView::firstOrCreate([
                'advertisement_id' => $task->id,
                'user_id' => Auth::id(),
            ]);
        } elseif (!Auth::check()) {
            // For guests, we still just increment the raw views or use session-based tracking
            // For now, let's just stick to the account-based distinct tracking requested
            $task->increment('views');
        }

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

    public function revertCompletion(Advertisement $task): RedirectResponse
    {
        $this->authorize('update', $task);

        if (!$task->isCompleted()) {
            return back()->with('error', __('Only completed tasks can be reverted.'));
        }

        $task->update(['status' => TaskStatus::Assigned]);
        
        // Optionally delete the review if it exists
        Review::where('reviewer_id', Auth::id())
            ->where('target_user_id', $task->employee_id)
            ->where('created_at', '>=', $task->updated_at->subMinutes(5)) // Only if recent? Or just delete.
            ->delete();

        return back()->with('success', __('Task status reverted to in-progress.'));
    }

    public function complete(Request $request, Advertisement $task): RedirectResponse
    {
        $this->authorize('complete', $task);

        if (!$task->isAssigned()) {
            return back()->with('error', __('Only assigned tasks can be marked as completed.'));
        }

        $this->taskService->completeTask($task, $request->only(['stars', 'comment']));

        return back()->with('success', __('Task marked as completed!'));
    }

    public function reviewEmployer(\App\Http\Requests\Task\ReviewEmployerRequest $request, Advertisement $task): RedirectResponse
    {
        $this->authorize('review', $task);

        $validated = $request->validated();

        Review::create([
            'reviewer_id' => Auth::id(),
            'target_user_id' => $task->employer_id,
            'stars' => $request->input('stars'),
            'comment' => $request->input('comment'),
        ]);

        return back()->with('success', __('Review submitted!'));
    }

    private function resolvePrice(?string $value, int $default): int
    {
        return $value ? (int) $value : $default;
    }

    private function getCategoriesForFilter()
    {
        return Category::orderBy('name')->get(['id', 'name']);
    }
}
