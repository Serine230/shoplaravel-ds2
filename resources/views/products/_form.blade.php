{{-- ════════ SHARED PRODUCT FORM ════════ --}}

@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
            <p class="font-semibold text-red-700">Veuillez corriger les erreurs suivantes :</p>
        </div>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-sm text-red-600">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- SECTION 1: Informations de base --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2">
        <i data-lucide="info" class="w-5 h-5 text-indigo-500"></i>
        Informations générales
    </h2>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Titre du produit <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $product->title ?? '') }}"
               class="input-field @error('title') border-red-400 @enderror"
               placeholder="Ex: iPhone 15 Pro Max 256GB Noir" required>
        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description <span class="text-red-500">*</span></label>
        <textarea name="description" rows="5" required
                  class="input-field resize-none @error('description') border-red-400 @enderror"
                  placeholder="Décrivez votre produit en détail : état, caractéristiques, inclusions...">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- SECTION 2: Prix & Stock --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2 mb-5">
        <i data-lucide="tag" class="w-5 h-5 text-indigo-500"></i>
        Prix & Stock
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Prix (DT) <span class="text-red-500">*</span></label>
            <div class="relative">
                <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}"
                       step="0.01" min="0" required
                       class="input-field pl-12 @error('price') border-red-400 @enderror"
                       placeholder="0.00">
                <span class="absolute left-4 top-3 text-gray-400 font-semibold text-sm">DT</span>
            </div>
            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ancien prix (DT)</label>
            <div class="relative">
                <input type="number" name="old_price" value="{{ old('old_price', $product->old_price ?? '') }}"
                       step="0.01" min="0"
                       class="input-field pl-12"
                       placeholder="0.00">
                <span class="absolute left-4 top-3 text-gray-400 font-semibold text-sm">DT</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Pour afficher une promotion</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stock <span class="text-red-500">*</span></label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}"
                   min="0" required
                   class="input-field @error('stock') border-red-400 @enderror"
                   placeholder="0">
            @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

{{-- SECTION 3: Catégories (Multi-select) --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2 mb-5">
        <i data-lucide="folder" class="w-5 h-5 text-indigo-500"></i>
        Catégories <span class="text-red-500">*</span>
        <span class="text-sm font-normal text-gray-400 ml-1">(sélection multiple)</span>
    </h2>

    @error('categories') <p class="text-red-500 text-xs mb-3">{{ $message }}</p> @enderror

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach($categories as $cat)
            @php
                $selected = old('categories')
                    ? in_array($cat->id, old('categories'))
                    : (isset($product) && $product->categories->contains($cat->id));
            @endphp
            <label class="relative cursor-pointer">
                <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                       {{ $selected ? 'checked' : '' }}
                       class="peer sr-only">
                <div class="border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50
                            rounded-xl p-3 text-sm text-center font-medium text-gray-600 peer-checked:text-indigo-700
                            hover:border-indigo-300 transition-all duration-150">
                    {{ $cat->name }}
                    <div class="absolute top-2 right-2 hidden peer-checked:block">
                        <i data-lucide="check-circle" class="w-4 h-4 text-indigo-500"></i>
                    </div>
                </div>
            </label>
        @endforeach
    </div>
</div>

{{-- SECTION 4: Images --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="imagePreview()">
    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2 mb-5">
        <i data-lucide="image" class="w-5 h-5 text-indigo-500"></i>
        Images
    </h2>

    {{-- Main image --}}
    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Image principale</label>

        @if(isset($product) && $product->image)
            <div class="mb-3">
                <img src="{{ $product->image_url }}" alt="" class="h-32 rounded-xl object-cover border border-gray-200">
                <p class="text-xs text-gray-400 mt-1">Image actuelle — téléversez une nouvelle pour la remplacer</p>
            </div>
        @endif

        <label class="block border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/50 transition-all duration-200"
               x-bind:class="mainPreview ? 'border-indigo-400 bg-indigo-50' : ''">
            <input type="file" name="image" accept="image/*" class="sr-only" @change="previewMain($event)">
            <template x-if="!mainPreview">
                <div>
                    <i data-lucide="upload-cloud" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                    <p class="text-sm font-medium text-gray-600">Cliquez pour téléverser</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — max 2 Mo</p>
                </div>
            </template>
            <template x-if="mainPreview">
                <img :src="mainPreview" class="h-40 mx-auto object-contain rounded-xl">
            </template>
        </label>
    </div>

    {{-- Gallery images --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Galerie (jusqu'à 5 images)</label>
        <label class="block border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/50 transition-all duration-200">
            <input type="file" name="images[]" accept="image/*" multiple class="sr-only" @change="previewGallery($event)">
            <i data-lucide="images" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
            <p class="text-sm font-medium text-gray-600">Ajouter des images supplémentaires</p>
        </label>

        <div class="flex flex-wrap gap-3 mt-3">
            <template x-for="(img, i) in galleryPreviews" :key="i">
                <div class="relative">
                    <img :src="img" class="w-20 h-20 object-cover rounded-xl border border-gray-200">
                    <button type="button" @click="galleryPreviews.splice(i, 1)"
                            class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">✕</button>
                </div>
            </template>
        </div>

        {{-- Existing gallery --}}
        @if(isset($product) && $product->images)
            <div class="flex flex-wrap gap-3 mt-3">
                @foreach($product->images as $img)
                    <img src="{{ asset('storage/' . $img) }}" class="w-20 h-20 object-cover rounded-xl border border-gray-200">
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- SECTION 5: Options --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2 mb-5">
        <i data-lucide="settings" class="w-5 h-5 text-indigo-500"></i>
        Options
    </h2>

    <div class="flex flex-col sm:flex-row gap-6">
        <label class="flex items-center gap-3 cursor-pointer">
            <div class="relative">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                       {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-gray-200 peer-checked:bg-indigo-600 rounded-full transition-colors"></div>
                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
            </div>
            <span class="text-sm font-semibold text-gray-700">Produit actif (visible sur le catalogue)</span>
        </label>
    </div>
</div>

@push('scripts')
<script>
function imagePreview() {
    return {
        mainPreview: null,
        galleryPreviews: [],
        previewMain(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = evt => this.mainPreview = evt.target.result;
            reader.readAsDataURL(file);
        },
        previewGallery(e) {
            const files = Array.from(e.target.files).slice(0, 5);
            this.galleryPreviews = [];
            files.forEach(f => {
                const r = new FileReader();
                r.onload = ev => this.galleryPreviews.push(ev.target.result);
                r.readAsDataURL(f);
            });
        }
    }
}
</script>
@endpush
