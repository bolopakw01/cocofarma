<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', 10); // Default 10, bisa diubah via parameter

        if ($perPage === 'all') {
            // Return all results but wrap them in a paginator so the view stays compatible
            $allItems = User::query()->get();
            $currentPage = Paginator::resolveCurrentPage();
            $perPageCount = $allItems->count() ?: 1; // avoid zero
            $currentItems = $allItems->slice(($currentPage - 1) * $perPageCount, $perPageCount)->values();

            $users = new LengthAwarePaginator($currentItems, $allItems->count(), $perPageCount, $currentPage, [
                'path' => Paginator::resolveCurrentPath(),
                'query' => request()->query()
            ]);
        } else {
            $users = User::query()->paginate((int) $perPage)->appends(request()->query());
        }

        return view('admin.pages.master-user.index-master-user', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = collect([
            (object) ['name' => 'super_admin'],
            (object) ['name' => 'admin']
        ]);

        return view('admin.pages.master-user.create-master-user', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin',
            'status' => 'required|boolean',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $filename = Str::uuid()->toString() . '.' . $avatarFile->getClientOriginalExtension();
            $avatarPath = $avatarFile->storeAs('avatars', $filename, 'public');
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'phone' => $request->phone,
            'address' => $request->address,
            'avatar_path' => $avatarPath,
        ]);

        return redirect()->route('backoffice.master-user.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.pages.master-user.show-master-user', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $avatarUrl = null;
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            $avatarUrl = asset('storage/' . ltrim($user->avatar_path, '/'));
        }

        $roles = collect([
            (object) ['name' => 'super_admin'],
            (object) ['name' => 'admin']
        ]);

        return view('admin.pages.master-user.edit-master-user', compact('user', 'avatarUrl', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($id)->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id)->whereNull('deleted_at'),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin',
            'status' => 'required|boolean',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Handle avatar removal
        if ($request->input('remove_avatar') == '1') {
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $data['avatar_path'] = null;
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $avatarFile = $request->file('avatar');
            $filename = Str::uuid()->toString() . '.' . $avatarFile->getClientOriginalExtension();
            $data['avatar_path'] = $avatarFile->storeAs('avatars', $filename, 'public');
        }

        $user->update($data);

        return redirect()->route('backoffice.master-user.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Implement destroy logic
        return redirect()->route('backoffice.master-user.index')->with('success', 'User berhasil dihapus.');
    }
}
