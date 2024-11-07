@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/posts/index.css') }}">
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3" style="border-radius: 10px">
            <div class="sidebar">
                <div>
                    <div style="padding-left: 20px">
                        @if (Auth::user()->status=="admin" || Auth::user()->status=="employer")
                            <h3>Create new post</h3>
                            <a href="{{ route('posts.create') }}" class="btn btn-success mt-3">Create New Post</a>
                        @endif
                    </div>
                </div>
                <br>
                <h3>Categories</h3>
                <form method="GET" action="{{ route('posts.index') }}" id="filterForm">
                    @foreach($categories as $category)
                        <div class="form-check">
                            <input 
                                type="checkbox" 
                                class="" 
                                name="category[]" 
                                value="{{ $category->id }}"
                                id="category{{ $category->id }}"
                                @if(request()->has('category') && in_array($category->id, request()->input('category'))) checked @endif
                            >
                            <label class="form-check-label" for="category{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary mt-2">Filter</button>
                    <button type="button" class="btn btn-danger mt-2" id="clearButton">Clear</button> <!-- Clear button -->
                </form>

                <h3 class="mt-4">Search Jobs</h3>
                <form action="/search" method="GET" style="padding-left: 20px">
                    <div class="search-bar">
                        <input type="text" name="query" placeholder="Search Jobs (e.g. Sales in Maadi)" class="form-control">
                        <button type="submit" class="btn btn-primary mt-2">Search Jobs</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main content for posts -->
        <div class="col-md-9">
            {{-- @if (Auth::user()->status=="admin" || Auth::user()->status=="employer")
                <a href="{{ route('posts.create') }}" class="btn btn-success mt-3">Create New Post</a>
            @endif --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($posts->count())
                @foreach ($posts as $post)
                    @if ($post->status == 'approved' || Auth::user()->status == "admin")
                        <div class="post-section bg-dark text-white p-4 mb-4 rounded" style="max-width: 600px; margin: 20px auto;">
                            <!-- Upper part of the post that navigates to the post details -->
                            <div class="post-header" onclick="window.location='{{ route('posts.show', $post->id) }}'" style="cursor: pointer;">
                                <div class="border-bottom border-warning mb-3 pb-3">
                                    <div class="d-flex align-items-center mb-3">
                                        @if($post->user && $post->user->image)
                                            <img src="{{ asset('storage/profile_images/' . $post->user->image) }}" alt="User Picture" class="rounded-circle me-2" style="width: 50px; height: 50px;">
                                        @else
                                            <img src="{{ asset('path/to/default/avatar.png') }}" alt="Default User Picture" class="rounded-circle me-2" style="width: 50px; height: 50px;">
                                        @endif
                                        <div>
                                            <p class="m-0 text-warning">{{ $post->title }}</p>
                                            <p class="m-0 text-warning" style="font-size: 0.8rem;">By {{ $post->user ? $post->user->name : 'Unknown User' }} | {{ $post->created_at->format('F j, Y') }} at {{ $post->created_at->format('g:i A') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-warning m-0">{{ $post->title }}</p>
                                </div>
                                <p class="mb-4" style="line-height: 1.6; text-align: left;">{{ $post->content }}</p>
                            </div>
                            
                            @if($post->image)
                                <div class="my-3">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-fluid border border-warning rounded">
                                </div>
                            @endif
                            @if(auth()->user()->status == 'admin' && $post->status == 'pending')
                                <div class="text-center mt-3">
                                    <form action="{{ route('posts.approve', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                        {{-- <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button> --}}
                                    </form>
                                    <form action="{{ route('posts.reject', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        {{-- <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button> --}}
                                        <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                    </form>
                                </div>
                            @endif
                            <!-- Like and Comment Section -->
                            <div class="text-center mt-3 d-flex justify-content-center gap-2">
                                <!-- Like Button and Count -->
                                <div class="d-flex gap-2">
                                    <form action="{{ route('posts.like', $post->id) }}" method="POST" onsubmit="return likePost(event, '{{ route('posts.like', $post->id) }}')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fa-solid fa-thumbs-up"></i>
                                        </button>
                                    </form>
                                    <button onclick="toggleCommentForm({{ $post->id }})" class="btn btn-outline-primary">
                                        <i class="fa-solid fa-comment"></i>
                                    </button>
                                </div>
                                <div class="d-flex gap-2">
                                    <!-- Like Count Display -->
                                    <button onclick="toggleLikeList()" class="btn btn-outline-light">
                                        Likes: {{ $post->likes->count() }}
                                    </button>
                                    <!-- Comment Count Display -->
                                    <button onclick="toggleCommentForm()" class="btn btn-outline-light">
                                        Comments: {{ $post->comments->count() }}
                                    </button>
                                </div>
                            </div>
                            <!-- Comment Form (Hidden by Default) -->
                            <div id="commentForm{{ $post->id }}" class="mt-3" style="display: none;">
                                <form action="{{ route('posts.storeComment', $post->id) }}" method="POST" class="d-flex align-items-center gap-2 w-100">
                                    @csrf
                                    <textarea name="content" rows="3" class="form-control" placeholder="Write your comment here..." required></textarea>
                                    <!-- Submit Comment Button with FontAwesome Icon -->
                                    <button type="submit" class="btn btn-secondary" style="background: transparent;border: none;font-size: 40px">
                                        <i class="fa-solid fa-paper-plane me-1"></i>  
                                    </button>
                                </form>
                            </div>
                            <!-- Comment List -->
                            {{-- <div class="comments mt-2">
                                @foreach($post->comments as $comment)
                                    <div class="alert alert-light" style="border-radius: 5px;">
                                        <strong>{{ $comment->user ? $comment->user->name : 'Unknown User' }}:</strong>
                                        <p>{{ $comment->content }}</p>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                @endforeach
                            </div> --}}
                        </div>
                    @endif
                @endforeach
                <div class="d-flex justify-content-center">
                    {{ $posts->links() }} <!-- Pagination Links -->
                </div>
            @else
                <div class="alert alert-info">No posts found.</div>
            @endif
        </div>
    </div>
</div>
<script src="{{ asset('js/posts/index.js') }}"></script>
@endsection
