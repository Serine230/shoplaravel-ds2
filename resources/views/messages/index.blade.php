@extends('layouts.app')
@section('title', 'Messagerie')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-3xl font-extrabold text-gray-900 mb-8 flex items-center gap-3">
        <i data-lucide="message-circle" class="w-8 h-8 text-indigo-600"></i>
        Messagerie
    </h1>

    @if($contacts->isEmpty())
        <div class="text-center py-24 bg-white rounded-3xl border border-gray-100">
            <i data-lucide="message-circle" class="w-16 h-16 text-gray-200 mx-auto mb-4"></i>
            <h2 class="text-xl font-bold text-gray-600 mb-2">Aucune conversation</h2>
            <p class="text-gray-400">Contactez un vendeur depuis la fiche d'un produit.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden divide-y divide-gray-50">
            @foreach($contacts as $contact)
                <a href="{{ route('messages.show', $contact) }}"
                   class="flex items-center gap-4 px-6 py-4 hover:bg-indigo-50/40 transition-colors">
                    <img src="{{ $contact->avatar_url }}" alt="{{ $contact->name }}"
                         class="w-12 h-12 rounded-full object-cover border-2 border-indigo-100">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900">{{ $contact->name }}</p>
                        <p class="text-sm text-gray-400 truncate">{{ $contact->email }}</p>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-gray-300"></i>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
