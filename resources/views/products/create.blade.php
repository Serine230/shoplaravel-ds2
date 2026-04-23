@extends('layouts.app')
@section('title', 'Ajouter un produit')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Ajouter un produit</h1>
        <p class="text-gray-500 mt-1">Remplissez les informations pour mettre votre produit en vente.</p>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @include('products._form')

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="btn-primary !px-8 !py-3.5 text-base">
                <i data-lucide="upload-cloud" class="w-5 h-5"></i> Publier le produit
            </button>
            <a href="{{ route('products.mine') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">Annuler</a>
        </div>
    </form>
</div>
@endsection
