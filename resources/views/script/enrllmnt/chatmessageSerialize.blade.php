<script>
    const currentUserId = $('meta[name="current-user-id"]').attr('content') || null;
    let chatReceiverId = null;
    let chatInterval = null;

    // Open chat
    $(document).on('click', '#sendChatEvalButton', function() {
        chatReceiverId = $(this).data('receiver-id');
        const receiverName = $(this).data('receiver-name') || 'User';

        $('#chatModalLabel').text('Chat with ' + receiverName);
        $('#chatMessages').html('<div class="text-center"><small>Loading messages...</small></div>');

        loadMessages();

        const chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
        chatModal.show();

        // Auto-refresh every 5 seconds
        if (chatInterval) clearInterval(chatInterval);
        chatInterval = setInterval(loadMessages, 5000);

        // Cleanup on close
        $('#chatModal').off('hidden.bs.modal').on('hidden.bs.modal', function () {
            clearInterval(chatInterval);
            chatInterval = null;
            chatReceiverId = null;
        });
    });

    // Load messages
    function loadMessages() {
        if (!chatReceiverId || !currentUserId) return;

        $.get("{{ route('fetchMessages', '') }}/" + chatReceiverId)
            .done(function(data) {
                let html = '';
                if (data.length === 0) {
                    html = '<div class="text-center text-muted"><small>No messages yet. Start the conversation!</small></div>';
                }

                data.forEach(msg => {
                    const isMe = parseInt(msg.sender_id) === parseInt(currentUserId);
                    const name = isMe ? 'You' : (msg.sender?.name || 'User');
                    const time = moment(msg.created_at).format('MMM D, h:mm A');

                    if (isMe) {
                        html += `
                        <div class="direct-chat-msg right mb-3">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right text-muted">${name}</span>
                                <span class="direct-chat-timestamp float-left text-muted">${time}</span>
                            </div>
                            <div class="direct-chat-text bg-primary text-white float-right">${escapeHtml(msg.message)}</div>
                        </div>`;
                    } else {
                        html += `
                        <div class="direct-chat-msg mb-3">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left">${name}</span>
                                <span class="direct-chat-timestamp float-right text-muted">${time}</span>
                            </div>
                            <div class="direct-chat-text bg-light border">${escapeHtml(msg.message)}</div>
                        </div>`;
                    }
                });

                $('#chatMessages').html(html);
                scrollToBottom();
            })
            .fail(function() {
                $('#chatMessages').html('<div class="text-danger text-center">Failed to load messages.</div>');
            });
    }

    // Send message
    $('#sendMessageButton').on('click', sendMessage);
    $('#chatInput').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const message = $('#chatInput').val().trim();
        if (!message || !chatReceiverId) return;

        $.post("{{ route('sendMessage') }}", {
            receiver_id: chatReceiverId,
            message: message,
            _token: $('meta[name="csrf-token"]').attr('content')
        })
        .done(function(res) {
            if (res.success) {
                $('#chatInput').val('');
                loadMessages();
            } else {
                alert('Failed to send message.');
            }
        })
        .fail(function() {
            alert('Network error. Please try again.');
        });
    }

    function scrollToBottom() {
        const elem = $('#chatMessages')[0];
        elem.scrollTop = elem.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>