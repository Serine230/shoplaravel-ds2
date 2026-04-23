@extends('layouts.app')
@section('title', 'Page introuvable — 404')
@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="w-32 h-32 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-8">
            <i data-lucide="search-x" class="w-16 h-16 text-indigo-300"></i>
        </div>
        <h1 class="text-8xl font-extrabold text-indigo-100 mb-4">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Page introuvable</h2>
        <p class="text-gray-500 mb-8 leading-relaxed">
            La page que vous cherchez n'existe pas ou a été déplacée.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="btn-primary !px-8 !py-3.5">
                <i data-lucide="home" class="w-5 h-5"></i> Retour à l'accueil
            </a>
            <a href="{{ route('products.index') }}" class="btn-outline !px-8 !py-3.5">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i> Voir le catalogue
            </a>
        </div>
    </div>
</div>
@endsection
