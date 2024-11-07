function deleteNotification(notificationId) {
    fetch(`/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`notification-${notificationId}`).remove();
            const notificationCountElement = document.getElementById('notificationCount');
            const currentCount = parseInt(notificationCountElement.textContent);
            if (currentCount > 0) {
                notificationCountElement.textContent = currentCount - 1;
                if (currentCount - 1 === 0) {
                    notificationCountElement.style.display = "none";
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
const messages_number = document.getElementById("messageCount");
const notification_number = document.getElementById("notificationCount");
if(messages_number.textContent == "0"){
    messages_number.style.display = "none";
}
if(notification_number.textContent == "0"){
    notification_number.style.display = "none";
}