@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/users/show.css') }}">
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $user->name }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>Email:</strong> 
                        <a href="{{ route('messages.create', ['to_user_id' => $user->id]) }}">{{ $user->email }}</a>
                    </p>
                    
                    <p><strong>Status:</strong> {{ $user->status }}</p>
                    @if($user->image)
                        <img src="{{ asset('storage/profile_images/' . $user->image) }}" class="img-fluid" alt="{{ $user->name }}">
                    @endif
                    @if($user->cv)
                        <p><strong>CV:</strong> 
                            <a href="{{ Storage::url('cvs/' . $user->cv) }}" target="_blank">Download CV</a>
                        </p>
                    @else
                        <p><strong>CV:</strong> Not Uploaded</p>
                    @endif
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            @if($posts->isEmpty())
                <p>No posts available.</p>
            @else
                @foreach ($posts as $post)
                    <div class="post-section">
                        <div class="post-header">
                            <div style="display: flex; align-items: center;">
                                @if($post->user && $post->user->image)
                                    <img src="{{ asset('storage/profile_images/' . $post->user->image) }}" alt="User Picture">
                                @else
                                    <img src="{{ asset('path/to/default/avatar.png') }}" alt="Default User Picture">
                                @endif
                                <div>
                                    <p style="margin: 0; color: #b89b59; font-size: 0.9rem;">{{ $post->title }}</p>
                                    <p style="margin: 0; color: #b89b59; font-size: 0.8rem;">By {{ $post->user ? $post->user->name : 'Unknown User' }} | {{ $post->created_at->format('F j, Y') }} at {{ $post->created_at->format('g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="post-content">{{ $post->content }}</p>
                        @if($post->image)
                            <div class="post-image">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image">
                            </div>
                        @endif
                        <div>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                            </form>
                        </div>
                        @if(auth()->user()->status == 'admin' && $post->status == 'pending')
                            <div style="text-align: center; margin-top: 20px;">
                                <form action="{{ route('posts.approve', $post->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
