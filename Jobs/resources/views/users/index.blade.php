@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/users/index.css') }}">
    <div class="container mt-4">
        <h1 class="mb-4">Users List</h1>
        <div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
            <span id="alert-message"></span>
            <button type="button" class="btn-close" aria-label="Close"></button>
        </div>
        <table class="table table-striped table-bordered" id="usersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- User data will be populated here by JavaScript -->
            </tbody>
        </table>

        <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
    </div>

    <script src="{{ asset('js/users/index.js') }}"></script>
@endsection
