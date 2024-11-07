@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/posts/edit.css') }}">

<div class="container post-edit-container">
    <h1 class="mb-4">Edit Post</h1>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to edit an existing post -->
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf <!-- CSRF token for form security -->
        @method('PUT')

        <div class="form-group mb-3">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="content">Content:</label>
            <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content', $post->content) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="" disabled>Select a category</option>
                @foreach (\App\Models\PostCategory::all() as $category)
                    <option value="{{ $category->id }}" {{ (old('category_id', $post->category_id) == $category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="image">Image (optional):</label>
            <input type="file" name="image" id="image" class="form-control form-control-lg">
            @if ($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" alt="Current Image" class="img-fluid mt-2">
            @endif
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-success">Update Post</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
