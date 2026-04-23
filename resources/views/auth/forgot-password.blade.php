@extends('layouts.app')
@section('title', 'Mot de passe oublié')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="key" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Mot de passe oublié</h1>
            <p class="text-gray-500 mt-2">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        </div>
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            @if(session('status'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 text-sm mb-5">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="input-field @error('email') border-red-400 @enderror"
                           placeholder="vous@exemple.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full justify-center !py-3.5">
                    <i data-lucide="mail" class="w-4 h-4"></i> Envoyer le lien
                </button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-5">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">← Retour à la connexion</a>
            </p>
        </div>
    </div>
</div>
@endsection
