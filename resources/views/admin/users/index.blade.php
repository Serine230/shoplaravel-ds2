@extends('layouts.admin')
@section('title', 'Utilisateurs')
@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <form method="GET" class="flex gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher..."
                   class="input-field max-w-xs">
            <button type="submit" class="btn-primary">Chercher</button>
        </form>
    </div>
    <table class="w-full admin-table">
        <thead><tr><th>Utilisateur</th><th>Email</th><th>Rôle</th><th>Produits</th><th>Commandes</th><th>Inscrit le</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" alt="" class="w-9 h-9 rounded-full object-cover">
                        <span class="font-semibold">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="text-gray-500 text-sm">{{ $user->email }}</td>
                <td>
                    <span class="{{ $user->isAdmin() ? 'badge-purple' : 'badge-blue' }}">
                        {{ $user->isAdmin() ? 'Admin' : 'User' }}
                    </span>
                </td>
                <td>{{ $user->products_count }}</td>
                <td>{{ $user->orders_count }}</td>
                <td class="text-gray-400 text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                <td>
                    <form action="{{ route('admin.users.role', $user) }}" method="POST" class="inline">
                        @csrf @method('PUT')
                        <select name="role" onchange="this.form.submit()"
                                class="text-xs border border-gray-200 rounded-lg px-2 py-1 focus:outline-none
                                       {{ $user->id === auth()->id() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <option value="user"  {{ $user->role === 'user'  ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </form>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Supprimer ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Suppr.</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
