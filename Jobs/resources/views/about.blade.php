@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Profile Picture Section -->
        <div class="col-md-4 text-center">
            <img src="{{ asset('storage/images/mo.jpg') }}" alt="Profile Picture" class="img-fluid rounded-circle mb-4" style="max-width: 200px;">
        </div>
        
        <!-- About Me Content Section -->
        <div class="col-md-8">
            <h1 class="display-4 mb-4">About Me</h1>
            <p class="lead">
                Hello! I'm Mostafa Essam, a passionate Laravel developer with a love for building dynamic web applications and solving complex problems. I have experience in developing Passport APIs, working with databases, and creating responsive front-end designs.
            </p>
            <p>
                My journey in web development began with my curiosity about how websites functioned behind the scenes. Over the years, I have honed my skills in Laravel, working on various full-stack projects, and gained expertise in integrating third-party APIs, optimizing performance, and ensuring security.
            </p>
            <p>
                I am particularly interested in advancing my knowledge of cloud computing and building scalable applications. When I'm not coding, you can find me exploring new tech trends, learning about AI advancements, or enjoying a good football match.
            </p>
            <p>
                Feel free to connect with me through LinkedIn, GitHub, or via email at <a href="mailto:mostafaessam9511@gmail.com">mostafaessam9511@gmail.com</a> . I look forward to collaborating on exciting projects and sharing ideas!
            </p>
            
        </div>
    </div>
</div>
@endsection
