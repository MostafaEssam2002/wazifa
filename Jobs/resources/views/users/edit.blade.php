@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/users/edit.css') }}">
<div class="container mt-4">
    <h1 class="mb-4">Edit User</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control">
            <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <!-- عرض حقل status فقط إذا كان المستخدم الحالي هو admin -->
        @if (Auth::user()->status === 'admin')
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="admin" {{ $user->status == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="candidate" {{ $user->status == 'candidate' ? 'selected' : '' }}>Candidate</option>
                    <option value="employer" {{ $user->status == 'employer' ? 'selected' : '' }}>Employer</option>
                </select>
            </div>
        @endif

        <div class="form-group">
            <label for="image">Profile Image</label>
            <input type="file" name="image" id="image" class="form-control">
            @if ($user->image)
                <img src="{{ asset('storage/profile_images/' . $user->image) }}" alt="Profile Image" class="mt-2 profile-image">
            @endif
        </div>

        <!-- إضافة زرار رفع CV -->
        <div class="form-group">
            <label for="cv">Upload CV</label>
            <input type="file" name="cv" id="cv" class="form-control">
            @if ($user->cv)
                <a href="{{ asset('storage/cvs/' . $user->cv) }}" target="_blank" class="d-block mt-2">View Current CV</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>

 
@endsection
