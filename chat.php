<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tenant Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, rgba(0, 118, 255, 0.6), rgba(255, 255, 255, 0.6)), url('your-image.jpg');
            background-size: cover;
            background-position: center;
            animation: backgroundAnimation 15s infinite alternate;
        }

        @keyframes backgroundAnimation {
            0% {
                background-position: center;
            }
            100% {
                background-position: top left;
            }
        }

        #chat-container {
            display: flex;
            width: 80%;
            max-width: 1000px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        #tenant-list {
            width: 30%;
            background-color: #f7f7f7;
            border-right: 1px solid #ddd;
            padding: 10px;
            overflow-y: auto;
        }

        .tenant {
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            background-color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .tenant:hover {
            background-color: #e0e0e0;
        }

        .tenant.selected {
            background-color: #0078ff;
            color: white;
        }

        #chat-box {
            width: 70%;
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            height: 400px;
        }

        .message {
            display: flex;
            margin-bottom: 15px;
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message.received {
            justify-content: flex-start;
        }

        .message-content {
            max-width: 80%;
            padding: 10px;
            border-radius: 20px;
            font-size: 14px;
        }

        .sent .message-content {
            background-color: #0078ff;
            color: white;
            border-bottom-right-radius: 0;
        }

        .received .message-content {
            background-color: #e5e5ea;
            color: black;
            border-bottom-left-radius: 0;
        }

        .message-time {
            font-size: 10px;
            color: #aaa;
            margin-top: 5px;
        }

        .input-area {
            display: flex;
            padding: 10px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
        }

        #message-input {
            width: 85%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-right: 10px;
        }

        #send-btn {
            background-color: #0078ff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
        }

        #send-btn:hover {
            background-color: #005bb5;
        }

        .chat-header {
            background-color: #0078ff;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div id="chat-container">
    <!-- Tenant List -->
    <div id="tenant-list">
        <!-- List of tenants will be dynamically populated -->
    </div>

    <!-- Chat Box -->
    <div id="chat-box">
        <div class="chat-header" id="chat-header">
            Select a tenant to chat
        </div>
        <div class="input-area">
            <textarea id="message-input" placeholder="Type a message..."></textarea>
            <button id="send-btn">&#8594;</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let selectedTenantId = null;

    // Fetch list of tenants to populate the chat list
    function fetchTenantList() {
        $.ajax({
            url: 'fetchTenants.php',
            type: 'GET',
            success: function (data) {
                const tenants = JSON.parse(data);
                let tenantListHtml = '';
                tenants.forEach(tenant => {
                    tenantListHtml += `
                        <div class="tenant" data-id="${tenant.id}">
                            ${tenant.firstname} ${tenant.lastname}
                        </div>
                    `;
                });
                $('#tenant-list').html(tenantListHtml);
            }
        });
    }

    // Fetch messages for the selected tenant
    function fetchMessages() {
        if (selectedTenantId) {
            $.ajax({
                url: 'fetchMessages.php',
                type: 'GET',
                data: { sender_id: 1, receiver_id: selectedTenantId },
                success: function (data) {
                    const messages = JSON.parse(data);
                    let chatBox = '';
                    messages.forEach(msg => {
                        const date = new Date(msg.created_at).toLocaleTimeString(); // Formatting time
                        if (msg.sender_id == 1) {
                            chatBox += `
                                <div class="message sent">
                                    <div class="message-content">
                                        ${msg.message}
                                        <div class="message-time">${date}</div>
                                    </div>
                                </div>
                            `;
                        } else {
                            chatBox += `
                                <div class="message received">
                                    <div class="message-content">
                                        ${msg.message}
                                        <div class="message-time">${date}</div>
                                    </div>
                                </div>
                            `;
                        }
                    });
                    $('#chat-box').html(chatBox);
                    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight); // Auto-scroll
                }
            });
        }
    }

    // Send a new message
    $('#send-btn').on('click', function () {
        const message = $('#message-input').val();
        if (message.trim() !== '' && selectedTenantId) {
            $.ajax({
                url: 'sendMessage.php',
                type: 'POST',
                data: {
                    sender_id: 1,
                    receiver_id: selectedTenantId,
                    message: message
                },
                success: function () {
                    $('#message-input').val('');
                    fetchMessages(); // Refresh the messages
                }
            });
        }
    });

    // Handle tenant selection
    $(document).on('click', '.tenant', function () {
        selectedTenantId = $(this).data('id');
        $('.tenant').removeClass('selected');
        $(this).addClass('selected');
        $('#chat-header').text('Chatting with ' + $(this).text());
        fetchMessages();
    });

    // Call fetchTenantList on page load
    fetchTenantList();
</script>

</body>
</html>
