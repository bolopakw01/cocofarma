<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        $avatarUrl = null;
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            $avatarUrl = asset('storage/' . ltrim($user->avatar_path, '/'));
        }

        $roleOptions = [
            'super_admin' => 'Super Admin',
            'admin' => 'Administrator',
        ];

        return view('admin.pages.profile.edit', [
            'user' => $user,
            'avatarUrl' => $avatarUrl,
            'lastUpdatedDiff' => optional($user->updated_at)->diffForHumans() ?? 'Baru saja',
            'roleOptions' => $roleOptions,
        ]);
    }

    public function update(AdminProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if ($user->role !== 'super_admin') {
            unset($data['role']);
        }

        if (isset($data['password']) && $data['password']) {
            $user->password = Hash::make($data['password']);
        }
        unset($data['password']);

        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $filename = Str::uuid()->toString() . '.' . $avatarFile->getClientOriginalExtension();
            $storedPath = $avatarFile->storeAs('avatars', $filename, 'public');

            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $data['avatar_path'] = $storedPath;
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('backoffice.profile.edit')->with('status', 'Profil berhasil diperbarui.');
    }
}
