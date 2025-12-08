<?php
$page_title = "Chat Pelanggan";
include '../templates/admin_header.php';
require_once '../api/db_connect.php';

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if (!$order_id) {
    echo "<p>Order ID tidak ditemukan.</p>";
    include '../templates/admin_footer.php';
    exit;
}
?>

<section>
    <h2>Chat untuk Pesanan #<?php echo htmlspecialchars($order_id); ?></h2>

    <div id="chat-box" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px; max-width: 800px; margin: auto;">
        <div id="chat-messages" style="height: 400px; overflow-y: scroll; border-bottom: 1px solid #ccc; margin-bottom: 10px; padding: 5px;">
            <!-- Messages will be loaded here -->
        </div>
        <form id="chat-form">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
            <input type="hidden" name="sender" value="admin">
            <textarea name="message" placeholder="Ketik balasan Anda..." required style="width: 100%; padding: 8px; border-radius: 3px; border: 1px solid #ccc;"></textarea>
            <button type="submit" class="btn-primary" style="margin-top: 10px;">Kirim</button>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const chatMessages = document.getElementById('chat-messages');
    const orderId = chatForm.querySelector('input[name="order_id"]').value;
    let messagePolling;

    function fetchMessages() {
        fetch(`../api/get_messages.php?order_id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderMessages(data.messages);
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
    }

    function sendMessage(message) {
        fetch('../api/send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                order_id: orderId,
                sender: 'admin',
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchMessages(); // Refresh messages after sending
            }
        })
        .catch(error => console.error('Error sending message:', error));
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const messageTextarea = chatForm.querySelector('textarea[name="message"]');
        const message = messageTextarea.value.trim();

        if (message) {
            sendMessage(message);
            messageTextarea.value = '';
        }
    });

    function renderMessages(messages) {
        chatMessages.innerHTML = '';
        messages.forEach(msg => {
            const messageElement = document.createElement('div');
            messageElement.style.marginBottom = '10px';
            
            let senderLabel = '';
            if (msg.sender === 'admin') {
                senderLabel = 'Anda';
                messageElement.style.textAlign = 'right';
            } else {
                senderLabel = 'Pelanggan';
                messageElement.style.textAlign = 'left';
            }

            messageElement.innerHTML = `
                <div style="font-weight: bold; font-size: 0.9em; color: #555;">${senderLabel}</div>
                <div style="background-color: #e2f7ff; padding: 8px; border-radius: 5px; display: inline-block; max-width: 70%; color: #000;">${msg.message}</div>
                <div style="font-size: 0.7em; color: #999;">${new Date(msg.timestamp).toLocaleTimeString()}</div>
            `;
            chatMessages.appendChild(messageElement);
        });
        chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom
    }

    // Initial fetch and polling
    fetchMessages();
    messagePolling = setInterval(fetchMessages, 5000); // Poll every 5 seconds
});
</script>

<?php include '../templates/admin_footer.php'; ?>