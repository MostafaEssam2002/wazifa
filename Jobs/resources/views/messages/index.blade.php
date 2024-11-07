@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/messages/index.css') }}">
<div class="containerm">
    <h2>Chats</h2>
    @if($receivedMessages->isEmpty())
        <p>No messages received.</p>
    @else
        <ul class="list-group">
            @foreach($receivedMessages as $message)
                <li class="list-group-item">
                    <a href="{{ route('messages.show', $message->id) }}" class="d-flex align-items-center">
                        <div class="media">
                            <div class="media-left">
                                <img src="{{ asset('storage/profile_images/' . $message->sender->image) }}" class="mr-3 rounded-circle" alt="{{ $message->sender->name }}" width="50" height="50">
                                <h5 class="mt-0">{{ $message->sender->name }}</h5>
                            </div>
                            <div class="media-body">
                                <p>{{ $message->subject }}</p>
                                <small>{{ $message->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
 

@endsection
