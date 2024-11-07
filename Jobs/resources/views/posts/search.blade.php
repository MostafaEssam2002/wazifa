@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/posts/search.css') }}">
<div class="container mt-4">
    <h1 class="mb-4">Search Results</h1>
    @if ($posts->isEmpty())
        <p>No posts found for your search query.</p>
    @else
        @foreach ($posts as $post)
            @if ($post->status == 'approved' || Auth::user()->status == "admin")
                <div class="post-section">
                    <div class="post-header" onclick="window.location='{{ route('posts.show', $post->id) }}'" style="cursor: pointer;">
                        <div class="post-title">
                            <div class="user-info">
                                @if($post->user && $post->user->image)
                                    <img src="{{ asset('storage/profile_images/' . $post->user->image) }}" alt="User Picture">
                                @else
                                    <img src="{{ asset('path/to/default/avatar.png') }}" alt="Default User Picture">
                                @endif
                                <div>
                                    <p class="post-title-text">{{ $post->title }}</p>
                                    <p class="post-meta">By {{ $post->user ? $post->user->name : 'Unknown User' }} | {{ $post->created_at->format('F j, Y') }} at {{ $post->created_at->format('g:i A') }}</p>
                                </div>
                            </div>
                            <p class="post-title-text">{{ $post->title }}</p>
                        </div>
                        <p class="post-content">{{ $post->content }}</p>
                    </div>
                    @if($post->image)
                        <div class="post-image">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image">
                        </div>
                    @endif
                    {{-- <div class="like-comment-section">
                        <div class="like-button">
                            <form action="{{ route('posts.like', $post->id) }}" method="POST" onsubmit="return likePost(event, '{{ route('posts.like', $post->id) }}')">
                                @csrf
                                <button type="submit" class="like-btn">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </button>
                            </form>
                            <button onclick="toggleSections({{ $post->id }}, 'comments')" class="comment-btn">
                                <i class="fa-solid fa-comment"></i>
                            </button>
                        </div>
                        <div class="like-comment-counts">
                            <button onclick="toggleSections({{ $post->id }}, 'likes')" class="count-btn">
                                Likes: {{ $post->likes->count() }}
                            </button>
                            <button onclick="toggleSections({{ $post->id }}, 'comments')" class="count-btn">
                                Comments: {{ $post->comments->count() }}
                            </button>
                        </div>
                    </div> --}}
                    <div id="commentSection{{ $post->id }}" class="comment-section" style="display: none;">
                        <h4>Comments</h4>
                        @foreach($post->comments as $comment)
                            <div class="comment">
                                <div class="comment-user">
                                    <img src="{{ asset('storage/profile_images/' . $comment->user->image) }}" alt="User Picture">
                                    <a href="{{ route('users.show', $comment->user->id) }}">
                                        <p class="comment-user-name">{{ $comment->user ? $comment->user->name : 'Anonymous' }}</p>
                                    </a>
                                </div>
                                <p class="comment-content">{{ $comment->content }}</p>
                                @if($comment->attachment)
                                    <div class="comment-attachment">
                                        <a href="{{ asset('storage/' . $comment->attachment) }}" target="_blank">View Attachment</a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <form action="{{ route('posts.storeComment', $post->id) }}" method="POST" class="comment-form">
                            @csrf
                            <textarea name="content" rows="3" placeholder="Write your comment here..." required></textarea>
                            <button type="submit" class="submit-comment-btn">
                                <i class="fa-solid fa-paper-plane"></i>  
                            </button>
                        </form>
                    </div>
                    <div id="likeSection{{ $post->id }}" class="like-section" style="display: none;">
                        <h4>Likes</h4>
                        @foreach($post->likes as $like)
                            <div class="like-user">
                                <img src="{{ asset('storage/profile_images/' . $like->user->image) }}" alt="User Picture">
                                <a href="{{ route('users.show', $like->user->id) }}">
                                    <p>{{ $like->user ? $like->user->name : 'Anonymous' }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if(auth()->user()->status == 'admin' && $post->status == 'pending')
                        <div class="admin-actions">
                            <form action="{{ route('posts.approve', $post->id) }}" method="POST">
                                @csrf
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    @endif
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
</div>

@endsection