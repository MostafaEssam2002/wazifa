function toggleCommentForm(postId) {
    const commentForm = document.getElementById(`commentForm${postId}`);
    commentForm.style.display = commentForm.style.display === 'none' ? 'block' : 'none';
}

// Clear button functionality
document.getElementById('clearButton').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
    document.getElementById('filterForm').submit();
});

function likePost(event, route) {
    event.preventDefault(); // Prevent default form submission
    // Handle the like logic here, e.g., using AJAX
    // For example, you could use fetch or XMLHttpRequest
    console.log('Liked post at route:', route);
}