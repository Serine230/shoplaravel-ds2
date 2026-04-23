@extends('layouts.app')
@section('title', 'Accès refusé — 403')
@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="w-32 h-32 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-8">
            <i data-lucide="shield-x" class="w-16 h-16 text-red-400"></i>
        </div>
        <h1 class="text-8xl font-extrabold text-red-200 mb-4">403</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Accès refusé</h2>
        <p class="text-gray-500 mb-8 leading-relaxed">
            Vous n'avez pas les droits nécessaires pour accéder à cette page.
            Cette section est réservée aux administrateurs.
        </p>
        <a href="{{ route('home') }}" class="btn-primary !px-8 !py-3.5">
            <i data-lucide="home" class="w-5 h-5"></i> Retour à l'accueil
        </a>
    </div>
</div>
@endsection
