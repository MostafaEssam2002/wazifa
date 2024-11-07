<!-- resources/views/send_email.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Send Email</h2>
    <form action="{{ route('send.email') }}" method="POST">
        @csrf
        <input type="hidden" name="receiver_email" value="{{ $receiverEmail }}">
        <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>
@endsection
