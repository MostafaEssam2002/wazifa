<!DOCTYPE html>
<html>
<head>
    <title>New Post Approval Request</title>
</head>
<body>
    <h1>New Post Approval Request</h1>
    <p>A new post has been created and requires your approval.</p>

    <h2>Post Details:</h2>
    <p><strong>Title:</strong> {{ $post->title }}</p>
    <p><strong>Content:</strong> {{ $post->content }}</p>

    <!-- Approval and Rejection Links -->
    <a href="{{ route('posts.approve', $post->id) }}" style="color:green;">Approve Post</a> |
    <a href="{{ route('posts.reject', $post->id) }}" style="color:red;">Reject Post</a>
</body>
</html>
