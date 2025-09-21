<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', 5); // Default 5, bisa diubah via parameter

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
        return view('admin.pages.master-user.create-master-user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement store logic
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
        return view('admin.pages.master-user.edit-master-user', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update logic
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
