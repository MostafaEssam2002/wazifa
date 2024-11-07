@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/welcome_style.css') }}">
<section class="hero-section" >
    <div>
        <h1 class="display-4">Find the Best Jobs in Egypt</h1>
        <p>Searching for vacancies & career opportunities? Wazifa helps you in your job search in Egypt.</p>
        <form action="/search" method="GET">
            <div class="search-bar">
                <input type="text" name="query" placeholder="Search Jobs (e.g. Sales in Maadi)">
                <button type="submit" class="btn btn-primary">Search Jobs</button>
            </div>
        </form>
    </div>
</section>
<section>
    <div class="slider">
        <div class="slides">
            <div class="slide" style="background-image: url('{{ asset('storage/images/google.png') }}'); background-size:contain;background-repeat: no-repeat"></div>
            <div class="slide" style="background-image: url('{{ asset('storage/images/microsoft.png') }}'); background-size:contain;background-repeat: no-repeat"></div>
            <div class="slide" style="background-image: url('{{ asset('storage/images/oracle.png') }}'); background-size:contain;background-repeat: no-repeat"></div>
            <div class="slide" style="background-image: url('{{ asset('storage/images/facebook.png') }}'); background-size:contain;background-repeat: no-repeat"></div>
            <div class="slide" style="background-image: url('{{ asset('storage/images/linkedin.png') }}'); background-size:contain;background-repeat: no-repeat"></div>
            <div class="slide" style="background-image: url('{{ asset('storage/images/alibaba.png') }}'); background-size:contain;background-repeat: no-repeat"></div>
            <div class="slide" style="background-image: url('{{ asset('storage/images/sony.png') }}'); background-size:contain;background-repeat: no-repeat"></div>
        </div>
        <button class="prev" onclick="prevSlide()">&#10094;</button>
        <button class="next" onclick="nextSlide()">&#10095;</button>
    </div>
</section>
<script src="{{ asset('js/welcome.js') }}"></script>
@endsection
