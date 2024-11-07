@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/messages/create.css') }}">

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h3>Chat with</h3>
            <ul class="list-group">
                @foreach($users as $user)
                    <li class="list-group-item d-flex align-items-center">
                        <img src="{{ asset('storage/profile_images/' . $user->image) }}" alt="{{ $user->name }}" class="rounded-circle mr-2" width="40" height="40">
                        <a href="{{ route('messages.create', ['to_user_id' => $user->id]) }}" class="ml-2" style="text-decoration: none; margin-left: 10px;">
                            {{ $user->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-9">
            <h2>Send New Mail</h2>
            <form action="{{ route('messages.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="to_user_id">To:</label>
                    <select name="to_user_id" class="form-control" required>
                        <option value="{{ $toUser->id }}" selected>{{ $toUser->name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="subject">Topic</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="body">Message</label>
                    <textarea name="body" class="form-control" rows="5" required></textarea>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

 
@endsection
