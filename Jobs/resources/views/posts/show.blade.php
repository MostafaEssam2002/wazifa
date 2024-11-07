@extends('layouts.app') 
@section('content')
<link rel="stylesheet" href="{{ asset('css/posts/show.css') }}">
<div class="post-container">
    <!-- Post Header -->
    <div class="post-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <!-- User Image and Post Title -->
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                @if($post->user && $post->user->image)
                    <img src="{{ asset('storage/profile_images/' . $post->user->image) }}" alt="User Picture" class="user-image">
                @else
                    <img src="{{ asset('path/to/default/avatar.png') }}" alt="Default User Picture" class="user-image">
                @endif
                <div>
                    <p class="post-title">{{ $post->title }}</p>
                    <a href="{{ route('users.show', $post->user->id) }}" style="text-decoration: none">
                        <p class="post-meta">By {{ $post->user ? $post->user->name : 'Unknown User' }} | {{ $post->created_at->format('F j, Y') }} at {{ $post->created_at->format('g:i A') }}</p>
                    </a>
                </div>
            </div>
            @if(auth()->user()->id == $post->user_id || auth()->user()->status == 'admin')
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; background: transparent; color: #b89b59;">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">Edit</a></li>
                    <li>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item">Delete</button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif
        </div>
    </div>
    <!-- Post Content -->
    <p class="post-content">{{ $post->content }}</p>    
    @if($post->image)
        <div>
            <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="post-image">
        </div>
    @endif
    <!-- Like and Comment Section -->
    <div class="like-comment-buttons">
        <div style="display: flex; gap: 10px;">
            <!-- Like Button -->
            <form action="{{ route('posts.like', $post->id) }}" method="POST" style="display: inline;" onsubmit="return likePost(event, '{{ route('posts.like', $post->id) }}')">
                @csrf
                <button type="submit" class="like-button" style="font-size: 32px; color: {{ $post->isLikedByUser(auth()->user()->id) ? 'red' : 'cornflowerblue' }}; border: none; cursor: pointer;">
                    <i class="fa-solid fa-thumbs-up"></i>
                </button>
            </form>      
            <!-- Comment Button -->
            <button class="comment-button" onclick="toggleCommentSection()" style=""> 
                <i class="fa-solid fa-comment"></i>
            </button>
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="likes-count-button" onclick="toggleLikeList()">
                Likes: {{ $post->likes->count() }}
            </button>
            <button class="comments-count-button" onclick="toggleCommentSection()">
                Comments: {{ $post->comments->count() }}
            </button>
        </div>
    </div>
    <!-- List of Users Who Liked the Post -->
    <div id="likeList" style="display: none;">
        <h4 style="color: #b89b59;">Users who liked this post:</h4>
        @foreach($post->likes as $like)
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('storage/profile_images/' . $like->user->image) }}" alt="User Picture" class="comment-user-image">
                <a style="text-decoration: none" href="{{ route('users.show', $like->user->id) }}">
                    <p style="color: #d1d1d1;">{{ $like->user ? $like->user->name : 'Anonymous' }}</p>
                </a>
            </div>
        @endforeach
    </div>
    <!-- Comment Form (Hidden by Default) -->
    <div id="commentSection" style="display: none;">
        <div class="comment-form">
            <form action="{{ route('posts.storeComment', $post->id) }}" method="POST" style="display: flex; align-items: center; gap: 10px; width: 100%">
                @csrf
                <textarea name="content" rows="3" class="form-control" placeholder="Write your comment here..." required style="resize: none; border-radius: 5px; width: 100%; height: 60px;"></textarea>
                <button type="submit" class="btn btn-secondary btn-sm" style="white-space: nowrap; font-size: 18px; background-color: transparent; color: #fff; border: none; padding: 5px 10px; cursor: pointer; display: flex; align-items: center;">
                    <i class="fa-solid fa-paper-plane" style="margin-right: 5px;"></i>  
                </button>
            </form>
        </div>
        <div style="margin-top: 30px;">
            @foreach($post->comments as $comment)
                <div class="comment-container">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ asset('storage/profile_images/' . $comment->user->image) }}" alt="User Picture" class="comment-user-image">
                        <a style="text-decoration: none" href="{{ route('users.show', $comment->user->id) }}">
                            <p class="comment-user-name">{{ $comment->user ? $comment->user->name : 'Anonymous' }}</p>
                        </a>
                    </div>
                    <p class="comment-content">{{ $comment->content }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
    <script src="{{ asset('js/posts/show.js') }}"></script>
@endsection
