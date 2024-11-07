<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wazifa</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/app_style.css') }}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Wazifa
                </a>
                @auth
                @if(auth()->user()->status == "admin") <!-- Check if user is admin -->
                    <a class="nav-link" href="{{ route('users.index') }}" style="font-size: 24px; font-weight: bold; margin-left: 20px;">Users</a>
                @endif
                @endauth
                <a class="nav-link" href="{{ route('posts.index') }}" style="font-size: 24px; font-weight: bold; margin-left: 20px;">Posts</a> <!-- Add this line -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>
                    <ul class="navbar-nav">
                        @auth
                            <li class="nav-item dropdown d-flex align-items-center" style="font-size: 25px">
                                <a id="messagesDropdown" class="nav-link notification-bell" href="{{ route('messages.index') }}" role="button">
                                    <i class="fas fa-envelope"></i>
                                    <span id="messageCount" class="badge">{{ auth()->user()->receivedMessages->where('is_read', 0)->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown d-flex align-items-center" style="font-size: 25px">
                                <a id="notificationDropdown" class="nav-link notification-bell" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    <span id="notificationCount" class="badge">{{ auth()->user()->notifications->where('is_read', 0)->count() }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                                    <div class="notification-header">Notifications</div>
                                    @foreach(auth()->user()->notifications as $notification)
                                    <div class="notification-item {{ !isset($notification->data['is_read']) || !$notification->data['is_read'] ? 'unread' : '' }}" id="notification-{{ $notification->id }}">
                                        <a href="{{ route('posts.show', $notification->data['post_id']) }}" onclick="markAsRead('{{ $notification->id }}')">
                                            {{ $notification->data['message'] ?? 'Notification content is missing.' }}
                                        </a>
                                        <button class="delete-notification" onclick="deleteNotification('{{ $notification->id }}')">X</button>
                                    </div>
                                    @endforeach
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ asset('storage/profile_images/' . Auth::user()->image) }}" alt="Profile Image" class="profile-image">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}">Profile</a> <!-- Link to user profile page -->
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
        <div class="footer">
            <ul class="footer-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="mailto:mostafaessam9511@gmail.com?subject=Hello%20There&body=I%20hope%20this%20message%20finds%20you%20well.">Contact</a></li>
                <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
            </ul>
            <p>&copy; 2024 Your Company. All Rights Reserved.</p>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app_style.js') }}"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/messages/show.css') }}"> --}}
</body>
</html>