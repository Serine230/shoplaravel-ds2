@extends('layouts.app')
@section('title', 'Vérifiez votre email')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-10 text-center">
            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="mail-check" class="w-10 h-10 text-indigo-600"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 mb-3">Vérifiez votre email</h1>
            <p class="text-gray-500 mb-6 leading-relaxed">
                Un lien de vérification a été envoyé à <strong>{{ auth()->user()->email }}</strong>.
                Cliquez sur le lien pour activer votre compte.
            </p>

            @if(session('message'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-green-700 text-sm mb-5">
                    {{ session('message') }}
                </div>
            @endif

            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary w-full justify-center !py-3.5 mb-4">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Renvoyer le lien
                </button>
            </form>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
