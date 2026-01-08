<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Advertisment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    /**
     * Store a new advertisement (task).
     */
    public function store(StoreTaskRequest $request)
{
    $validated = $request->validated();

    // Force location to "Online" when task is online
    if (($validated['task_type'] ?? null) === 'online') {
        $validated['location'] = 'Online';
    }

    $photos = [];
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('task-photos', 'public');
            $photos[] = $path;
        }
    }

    $advert = new Advertisment();
    $advert->fill($validated);
    $advert->photos = $photos ?: null;
    $advert->employer_id = Auth::id();
    $advert->expiration_date = now()->addDays(30);
    $advert->status = 'open';
    $advert->save();

    return redirect()->route('my-tasks')->with('success', 'Your task has been posted successfully!');
}

    /**
     * Update an existing advertisement.
     */
    public function update(StoreTaskRequest $request, Advertisment $advertisement)
    {
        // Only the owner can update
        if (Auth::id() !== $advertisement->employer_id) {
            abort(403);
        }

    $validated = $request->validated();

        // Force location to "Online" when task is online
        if (($validated['task_type'] ?? null) === 'online') {
            $validated['location'] = 'Online';
        }

        // Merge photos if provided
        $photos = $advertisement->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('task-photos', 'public');
                $photos[] = $path;
            }
        }

        $advertisement->fill($validated);
        $advertisement->photos = $photos ?: null;
        $advertisement->save();

        return redirect()->back()->with('success', 'Task updated.');
    }

    /**
     * Remove an advertisement.
     */
    public function destroy(Advertisment $advertisement)
    {
        if (Auth::id() !== $advertisement->employer_id) {
            abort(403);
        }

        // optionally delete photos from storage
        if (is_array($advertisement->photos)) {
            foreach ($advertisement->photos as $p) {
                Storage::disk('public')->delete($p);
            }
        }

        $advertisement->delete();

        return redirect()->route('my-tasks')->with('success', 'Task removed.');
    }

    /**
     * Mark an advertisement as completed.
     */
    public function complete(Request $request, Advertisment $advertisement)
    {
        if (Auth::id() !== $advertisement->employer_id) {
            abort(403);
        }

        $advertisement->update(['status' => 'completed']);

        // Check for review data
        if ($request->has('stars') && $request->filled('stars')) {
            $request->validate([
                'stars' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:500',
            ]);

            if ($advertisement->employee_id) {
                \App\Models\Review::create([
                    'reviewer_id' => Auth::id(),
                    'target_user_id' => $advertisement->employee_id,
                    'stars' => $request->input('stars'),
                    'comment' => $request->input('comment'),
                ]);
                return redirect()->back()->with('success', 'Task completed and review submitted!');
            }
        }

        return redirect()->back()->with('success', 'Task marked as completed!');
    }
}
