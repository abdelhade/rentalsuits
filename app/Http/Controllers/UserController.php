<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // If the user wants an admin-only restriction for users:
            new Middleware('role:admin', only: ['index', 'create', 'store', 'edit', 'update', 'destroy']),
        ];
    }

    public function index()
    {
        // Get users with their permissions
        $users = User::with('permissions', 'roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $permissions = Permission::all();
        $roles = Role::all();
        return view('users.create', compact('permissions', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
            'permissions' => 'nullable|array',
            'roles' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }
        
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit(User $user)
    {
        $permissions = Permission::all();
        $roles = Role::all();
        // Pluck IDs for easy checking
        $userPermissions = $user->permissions->pluck('name')->toArray();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        return view('users.edit', compact('user', 'permissions', 'roles', 'userPermissions', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'permissions' => 'nullable|array',
            'roles' => 'nullable|array',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('users.index')->with('success', 'تم تعديل بيانات المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return back()->withErrors('لا يمكنك حذف حسابك الحالي.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }
}
