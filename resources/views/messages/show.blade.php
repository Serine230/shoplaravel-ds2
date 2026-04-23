{{-- resources/views/messages/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Messages')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col" style="height: 70vh;">
        {{-- Header --}}
        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-100 bg-gray-50">
            <a href="{{ route('messages.index') }}" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <img src="{{ $user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-bold text-gray-900">{{ $user->name }}</p>
                <p class="text-xs text-gray-400">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4" id="messages-container">
            @foreach($messages as $msg)
                <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%]">
                        <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                            {{ $msg->sender_id === auth()->id()
                                ? 'bg-indigo-600 text-white rounded-br-none'
                                : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                            {{ $msg->body }}
                        </div>
                        <p class="text-xs text-gray-400 mt-1 {{ $msg->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                            {{ $msg->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
            @endforeach

            @if($messages->isEmpty())
                <div class="text-center py-10 text-gray-400">
                    <i data-lucide="message-circle" class="w-12 h-12 mx-auto mb-3 text-gray-200"></i>
                    <p>Démarrez la conversation !</p>
                </div>
            @endif
        </div>

        {{-- Input --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-white">
            <form action="{{ route('messages.send', $user) }}" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="body" required autofocus
                       class="input-field flex-1" placeholder="Écrire un message..."
                       autocomplete="off">
                <button type="submit" class="btn-primary !px-4">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
</script>
@endpush
@endsection
