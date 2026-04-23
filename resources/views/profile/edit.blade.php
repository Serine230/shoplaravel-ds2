@extends('layouts.app')
@section('title', 'Modifier mon profil')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('profile.show') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900">Modifier le profil</h1>
    </div>

    <div class="space-y-6">

        {{-- ═══ Infos générales ═══ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-5 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-indigo-500"></i>
                Informations personnelles
            </h2>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-green-700 text-sm flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>{{ session('success') }}
                    </div>
                @endif

                {{-- Avatar --}}
                <div x-data="{ preview: '{{ $user->avatar_url }}' }">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Photo de profil</label>
                    <div class="flex items-center gap-5">
                        <img :src="preview" alt="" class="w-20 h-20 rounded-full object-cover border-4 border-indigo-100">
                        <label class="cursor-pointer">
                            <input type="file" name="avatar" accept="image/*" class="sr-only"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                            <span class="btn-outline !px-4 !py-2 text-sm">
                                <i data-lucide="upload" class="w-4 h-4"></i> Changer la photo
                            </span>
                        </label>
                    </div>
                    @error('avatar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nom complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="input-field @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="input-field @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="input-field" placeholder="+216 XX XXX XXX">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Adresse</label>
                    <textarea name="address" rows="2" class="input-field resize-none"
                              placeholder="Votre adresse de livraison par défaut">{{ old('address', $user->address) }}</textarea>
                </div>

                <button type="submit" class="btn-primary">
                    <i data-lucide="save" class="w-4 h-4"></i> Enregistrer les modifications
                </button>
            </form>
        </div>

        {{-- ═══ Changer mot de passe ═══ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-5 flex items-center gap-2">
                <i data-lucide="lock" class="w-5 h-5 text-indigo-500"></i>
                Changer le mot de passe
            </h2>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf @method('PUT')

                @if($errors->has('current_password'))
                    <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-red-700 text-sm">
                        {{ $errors->first('current_password') }}
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mot de passe actuel</label>
                    <input type="password" name="current_password" required
                           class="input-field @error('current_password') border-red-400 @enderror"
                           placeholder="••••••••">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nouveau mot de passe</label>
                        <input type="password" name="password" required minlength="8"
                               class="input-field @error('password') border-red-400 @enderror"
                               placeholder="Minimum 8 caractères">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirmer</label>
                        <input type="password" name="password_confirmation" required
                               class="input-field" placeholder="••••••••">
                    </div>
                </div>
                @error('password')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror

                <button type="submit" class="btn-primary">
                    <i data-lucide="shield-check" class="w-4 h-4"></i> Mettre à jour le mot de passe
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
