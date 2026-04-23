<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount('products', 'orders')
            ->when(
                $request->q,
                fn($q, $s) => $q->where('name', 'like', "%$s%")
                                ->orWhere('email', 'like', "%$s%")
            )
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('products', 'orders.items');
        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, User $user)
    {
        abort_if(
            $user->id === auth()->id(),
            403,
            'Impossible de modifier votre propre rôle.'
        );

        $request->validate(['role' => 'required|in:user,admin']);
        $user->update(['role' => $request->role]);

        return back()->with('success', "Rôle de {$user->name} mis à jour : {$request->role}");
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Vous ne pouvez pas supprimer votre propre compte.');
        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }
}
