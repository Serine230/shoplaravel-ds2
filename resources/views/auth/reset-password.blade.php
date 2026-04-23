@extends('layouts.app')
@section('title', 'Réinitialisation du mot de passe')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="lock" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Nouveau mot de passe</h1>
        </div>
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required
                           class="input-field" readonly>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nouveau mot de passe</label>
                    <input type="password" name="password" required minlength="8" class="input-field" placeholder="Minimum 8 caractères">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirmer</label>
                    <input type="password" name="password_confirmation" required class="input-field" placeholder="••••••••">
                </div>
                <button type="submit" class="btn-primary w-full justify-center !py-3.5">
                    <i data-lucide="check" class="w-4 h-4"></i> Réinitialiser
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
