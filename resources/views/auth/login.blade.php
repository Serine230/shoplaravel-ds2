{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')
@section('title', 'Connexion')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <span class="text-white font-extrabold text-2xl">SL</span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Connexion</h1>
            <p class="text-gray-500 mt-2">Bienvenue ! Connectez-vous à votre compte.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="input-field @error('email') border-red-400 @enderror"
                           placeholder="vous@exemple.com">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="text-sm font-semibold text-gray-700">Mot de passe</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:underline">Mot de passe oublié ?</a>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" required
                               class="input-field pr-12"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                            <i :data-lucide="show ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="text-indigo-600 rounded">
                    <span class="text-sm text-gray-600">Se souvenir de moi</span>
                </label>

                <button type="submit" class="btn-primary w-full justify-center !py-3.5 !text-base">
                    <i data-lucide="log-in" class="w-5 h-5"></i> Se connecter
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Créer un compte</a>
            </p>
        </div>
    </div>
</div>
@endsection
