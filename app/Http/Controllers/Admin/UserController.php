<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = User::query(); 
            if ($request->has('search') && $request->search != '') { 
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%'); 
            } 
            $users = $query->latest()->paginate(15);
            return view('admin.users.index', compact('users'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to retrieve users: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.users.edit', compact('user'));
        } catch (\Throwable $th) {
            return redirect()->route('admin.users.index')->with('error', 'User not found.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8',
                'is_admin' => 'boolean',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to update user: ' . $th->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to delete user: ' . $th->getMessage());
        }
    }
}
