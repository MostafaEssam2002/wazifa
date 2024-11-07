    setInterval(function() {
        $.ajax({
            url: "{{ route('messages.fetch', $user->id) }}",
            method: 'GET',
            success: function(data) {
                updateChatBox(data);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching messages:", error);
            }
        });
    }, 5000);
    function updateChatBox(messages) {
        console.log(messages);
        const chatBox = $('.chat-box');
        chatBox.empty();
        messages.forEach(function(msg) {
            const messageWrapper = $('<div class="message-wrapper"></div>');
            const profileIcon = $('<img>').attr('src', `{{ asset('storage/profile_images') }}/${msg.from_user_id == auth()->id() ? '{{ auth()->user()->image }}' : '{{ $user->image }}'}`)
                .addClass('profile-icon');
            const messageDiv = $('<div class="message"></div>').addClass(msg.from_user_id == auth()->id() ? 'sent' : 'received').html(`
                <p>${msg.body}</p>
                <small class="message-time">${new Date(msg.created_at).toLocaleString()}</small>
            `);
            messageWrapper.append(profileIcon).append(messageDiv);
            chatBox.append(messageWrapper);
        });
    }