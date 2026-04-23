@extends('layouts.app')
@section('title', 'Inscription')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <span class="text-white font-extrabold text-2xl">SL</span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Créer un compte</h1>
            <p class="text-gray-500 mt-2">Rejoignez des milliers d'utilisateurs.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                        <ul class="space-y-1">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="input-field" placeholder="Prénom Nom">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="input-field" placeholder="vous@exemple.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mot de passe</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" required minlength="8"
                               class="input-field pr-12" placeholder="Minimum 8 caractères">
                        <button type="button" @click="show = !show" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                            <i :data-lucide="show ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" required class="input-field" placeholder="••••••••">
                </div>

                <button type="submit" class="btn-primary w-full justify-center !py-3.5 !text-base">
                    <i data-lucide="user-plus" class="w-5 h-5"></i> Créer mon compte
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Déjà inscrit ?
                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</div>
@endsection
