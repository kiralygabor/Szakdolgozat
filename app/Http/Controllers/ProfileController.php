<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('pages.profile', [
            'user' => Auth::user(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $user->fill($request->safe()->except('avatar'));

        if ($request->hasFile('avatar')) {
            $this->replaceAvatar($request, $user);
        }

        $user->save();

        return redirect()->route('profile', ['tab' => 'profile'])
            ->with('success', __('profile_page.profile.update_success'));
    }

    public function updateNotifications(Request $request): RedirectResponse
    {
        $request->validate([
            'tracked_categories'   => 'nullable|array',
            'tracked_categories.*' => 'integer|exists:categories,id',
        ]);

        $user = Auth::user();

        $user->email_notifications = $request->has('email_notifications');
        $user->email_task_digest = $request->has('email_task_digest');
        $user->email_direct_quotes = $request->has('email_direct_quotes');

        if ($user->email_task_digest) {
            $user->trackedCategories()->sync($request->input('tracked_categories', []));
        } else {
            $user->trackedCategories()->detach();
        }

        $user->save();

        return redirect()->route('profile', ['tab' => 'notification'])
            ->with('success', __('Settings updated.'));
    }

    public function updateSettings(\App\Http\Requests\Profile\UpdateSettingsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        Auth::user()->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $this->deleteAvatarFromStorage($user);

        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index')
            ->with('success', __('Account deleted successfully.'));
    }

    public function sendManualDigest(): RedirectResponse
    {
        return redirect()->route('profile', ['tab' => 'notification'])
            ->with('info', __('Manual digest sent.'));
    }

    // ── Private Helpers ──────────────────────────────────────

    private function replaceAvatar(Request $request, User $user): void
    {
        $this->deleteAvatarFromStorage($user);

        $user->avatar = $request->file('avatar')->store('avatars', 'public');
    }

    private function deleteAvatarFromStorage(User $user): void
    {
        $isStoredFile = !empty($user->avatar)
            && !str_starts_with($user->avatar, 'assets/')
            && !str_starts_with($user->avatar, 'http');

        if ($isStoredFile) {
            Storage::disk('public')->delete($user->avatar);
        }
    }
}
