@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/messages/show.css') }}">
<div class="containerm">
    <h2>
        Chat with 
        <a href="{{ route('users.show', $user->id) }}" class="d-flex align-items-center">
            <img src="{{ asset('storage/profile_images/' . $user->image) }}" alt="{{ $user->name }}'s Profile Picture" class="profile-image">
            {{ $user->name }}
        </a>
    </h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="chat-box">
        @foreach($messages as $msg)
            <div class="message-wrapper {{ $msg->from_user_id == auth()->id() ? 'sent-wrapper' : 'received-wrapper' }}">
                <img src="{{ asset('storage/profile_images/' . ($msg->from_user_id == auth()->id() ? auth()->user()->image : $user->image)) }}" class="profile-icon">
                <div class="message {{ $msg->from_user_id == auth()->id() ? 'sent' : 'received' }}" onclick="toggleTime(this)">
                    <p>{{ $msg->body }}</p>
                    <small class="message-time">{{ $msg->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        @endforeach
    </div>
    <form action="{{ route('messages.reply', $message->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea id="body" name="body" class="form-control" rows="3" required autofocus></textarea>
            <button type="submit" class="send-button"> 
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/messages/show.js') }}"></script>

