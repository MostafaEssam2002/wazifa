function likePost(event, url) {
    event.preventDefault(); // Prevent default form submission
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        // Handle success or error response
        console.log(data);
        location.reload(); // Reload the page to reflect changes
    })
    .catch(error => console.error('Error:', error));
}

function toggleCommentSection() {
    var commentSection = document.getElementById('commentSection');
    var likeList = document.getElementById('likeList');
    if (commentSection.style.display === 'none') {
        commentSection.style.display = 'block';
        likeList.style.display = 'none'; // Hide likes
    } else {
        commentSection.style.display = 'none'; // Hide comments
    }
}

function toggleLikeList() {
    var likeList = document.getElementById('likeList');
    var commentSection = document.getElementById('commentSection');
    if (likeList.style.display === 'none') {
        likeList.style.display = 'block';
        commentSection.style.display = 'none'; // Hide comments
    } else {
        likeList.style.display = 'none'; // Hide likes
    }
}