document.addEventListener("DOMContentLoaded", function() {
    fetchUsers();
    function fetchUsers() {
        fetch('http://127.0.0.1:8000/api/users')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const usersTableBody = document.querySelector('#usersTable tbody');
                usersTableBody.innerHTML = ''; // Clear existing data

                data.forEach(user => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.status}</td>
                        <td>
                            <a href="/users/${user.id}" class="btn btn-info btn-sm">View</a>
                            <a href="/users/${user.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">Delete</button>
                        </td>
                    `;
                    usersTableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching users:', error);
                showAlert(`Error fetching users: ${error.message}`, 'danger');
            });
    }

    window.deleteUser = function(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch(`/api/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token for security
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    showAlert('User deleted successfully.', 'success');
                    fetchUsers(); // Refresh the user list
                } else {
                    showAlert('Failed to delete user.', 'danger');
                }
            })
            .catch(error => {
                console.error('Error deleting user:', error);
                showAlert('Error deleting user.', 'danger');
            });
        }
    };

    function showAlert(message, type) {
        const alertDiv = document.getElementById('alert');
        const alertMessage = document.getElementById('alert-message');
        alertMessage.textContent = message;
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.display = 'block';
        setTimeout(() => {
            alertDiv.style.display = 'none';
        }, 3000);
    }
});