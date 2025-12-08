<?php
session_start();
require_once 'api/db_connect.php';

$page_title = "Lacak Pesanan";
include 'templates/header.php';

$order_details = null;
$error = null;
$current_status_index = -1;
$statuses_map = [
    'Pesanan Diterima' => ['emoji' => '✅', 'label' => 'Pesanan Diterima'],
    'Sedang Disiapkan' => ['emoji' => '👨‍🍳', 'label' => 'Makanan Sedang Dimasak'],
    'Dalam Perjalanan' => ['emoji' => '🚴‍♂️', 'label' => 'Kurir Sedang Mengantar'],
    'Tiba di Tujuan' => ['emoji' => '📍', 'label' => 'Pesanan Tiba']
];
$status_keys = array_keys($statuses_map);

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    if (!empty($order_id)) {
        $stmt = $conn->prepare("SELECT id, status, order_date, customer_phone, total_amount, payment_method FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $order_details = $result->fetch_assoc();
            $current_status_index = array_search($order_details['status'], $status_keys);
        } else {
            $error = "Nomor pesanan tidak ditemukan.";
        }
    }
}
?>

<section class="track-page">
    <h2>Lacak Status Pesanan Anda</h2>
    <form action="track.php" method="GET" class="track-form">
        <div class="form-group">
            <label for="order_id">Masukkan Nomor Pesanan</label>
            <input type="text" id="order_id" name="order_id" value="<?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?>" required>
        </div>
        <button type="submit" class="btn-primary">Lacak</button>
    </form>

    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($order_details): ?>
        <div class="order-tracking-details">
            <h3>Pesanan #<?php echo htmlspecialchars($order_details['id']); ?></h3>

            <div class="payment-details-summary">
                <h4>Detail Pembayaran</h4>
                <p><strong>Total Pembayaran:</strong> Rp <?php echo number_format($order_details['total_amount'], 0, ',', '.'); ?></p>
                <p><strong>Metode Pembayaran:</strong> <?php echo htmlspecialchars($order_details['payment_method']); ?></p>
            </div>
            
            <!-- Progress Bar Status -->
            <div class="progress-bar">
                <?php foreach ($status_keys as $index => $status_key): ?>
                    <div class="progress-step <?php echo ($index <= $current_status_index) ? 'active' : ''; ?>">
                        <div class="step-icon"><?php echo $statuses_map[$status_key]['emoji']; ?></div>
                        <div class="step-label"><?php echo $statuses_map[$status_key]['label']; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Current Status Message -->
            <div class="current-status-message">
                <?php
                    $status_message = "";
                    switch ($order_details['status']) {
                        case 'Pesanan Diterima':
                            $status_message = "Pesananmu telah diterima dan sedang menunggu konfirmasi. ✅";
                            break;
                        case 'Sedang Disiapkan':
                            $status_message = "Pesananmu sedang disiapkan oleh koki kami. 👨‍🍳";
                            break;
                        case 'Dalam Perjalanan':
                            $status_message = "Kurir sedang dalam perjalanan mengantar pesananmu. 🚴‍♂️";
                            break;
                        case 'Tiba di Tujuan':
                            $status_message = "Pesananmu telah tiba di tujuan! Selamat menikmati. 📍";
                            break;
                    }
                ?>
                <p><strong>Status Saat Ini:</strong> <?php echo $status_message; ?></p>
            </div>

            <!-- Tampilan Tambahan -->
            <div class="additional-info">
                <div class="eta-info">
                    <p><strong>Estimasi Waktu Tiba:</strong> 
                    <?php
                        if ($order_details['status'] == 'Tiba di Tujuan') {
                            echo "Pesanan sudah tiba.";
                        } elseif ($order_details['status'] == 'Dalam Perjalanan') {
                            // Contoh sederhana: 15-30 menit setelah diantar
                            echo "15-30 menit lagi.";
                        } elseif ($order_details['status'] == 'Sedang Disiapkan') {
                            echo "Akan diupdate setelah pesanan selesai dimasak.";
                        } else {
                            echo "Akan diupdate setelah pesanan dikonfirmasi.";
                        }
                    ?></p>
                </div>
                <div class="contact-courier">
                    <button type="button" class="btn-primary" id="hubungi-kurir-btn">Hubungi Kurir</button>
                    <p class="small-text">*Fitur ini akan aktif saat kurir sudah dalam perjalanan.</p>
                </div>

                <!-- Chat Box -->
                <div id="chat-box" style="display: none; margin-top: 20px; border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                    <div id="chat-messages" style="height: 200px; overflow-y: scroll; border-bottom: 1px solid #ccc; margin-bottom: 10px; padding: 5px;">
                        <!-- Messages will be loaded here -->
                    </div>
                    <form id="chat-form">
                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_details['id']); ?>">
                        <input type="hidden" name="sender" value="user">
                        <textarea name="message" placeholder="Ketik pesan Anda..." required style="width: 100%; padding: 8px; border-radius: 3px; border: 1px solid #ccc;"></textarea>
                        <button type="submit" class="btn-primary" style="margin-top: 10px;">Kirim</button>
                    </form>
                </div>

            </div>

        </div>
    <?php endif; ?>

</section>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const hubungiKurirBtn = document.getElementById('hubungi-kurir-btn');
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const chatMessages = document.getElementById('chat-messages');
    const orderId = "<?php echo htmlspecialchars($order_details['id']); ?>";
    let messagePolling;
    let statusPolling;

    const statusesMap = {
        'Pesanan Diterima': { emoji: '✅', label: 'Pesanan Diterima' },
        'Sedang Disiapkan': { emoji: '👨‍🍳', label: 'Makanan Sedang Dimasak' },
        'Dalam Perjalanan': { emoji: '🚴‍♂️', label: 'Kurir Sedang Mengantar' },
        'Tiba di Tujuan': { emoji: '📍', label: 'Pesanan Tiba' }
    };
    const statusKeys = Object.keys(statusesMap);

    // Function to update the UI based on order status
    function updateOrderStatusUI(currentStatus) {
        // Update progress bar
        const progressSteps = document.querySelectorAll('.progress-step');
        const currentStatusIndex = statusKeys.indexOf(currentStatus);

        progressSteps.forEach((step, index) => {
            if (index === currentStatusIndex) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        // Update current status message
        const currentStatusMessageElement = document.querySelector('.current-status-message p strong');
        let statusMessageText = "";
        switch (currentStatus) {
            case 'Pesanan Diterima':
                statusMessageText = "Pesananmu telah diterima dan sedang menunggu konfirmasi. ✅";
                break;
            case 'Sedang Disiapkan':
                statusMessageText = "Pesananmu sedang disiapkan oleh koki kami. 👨‍🍳";
                break;
            case 'Dalam Perjalanan':
                statusMessageText = "Kurir sedang dalam perjalanan mengantar pesananmu. 🚴‍♂️";
                break;
            case 'Tiba di Tujuan':
                statusMessageText = "Pesananmu telah tiba di tujuan! Selamat menikmati. 📍";
                break;
            default:
                statusMessageText = "Status tidak diketahui.";
        }
        currentStatusMessageElement.nextSibling.textContent = ` ${statusMessageText}`;

        // Update Hubungi Kurir button state
        const allowedStatusForChat = ['Dalam Perjalanan', 'Tiba di Tujuan'];
        if (allowedStatusForChat.includes(currentStatus)) {
            hubungiKurirBtn.disabled = false;
            hubungiKurirBtn.style.backgroundColor = ''; // Reset to default
            hubungiKurirBtn.style.cursor = ''; // Reset to default
        } else {
            hubungiKurirBtn.disabled = true;
            hubungiKurirBtn.style.backgroundColor = '#ccc';
            hubungiKurirBtn.style.cursor = 'not-allowed';
        }
    }

    // Function to fetch order status and update UI
    function fetchOrderStatusAndUpdateUI() {
        fetch(`api/get_order_status.php?order_id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateOrderStatusUI(data.status);
                }
            })
            .catch(error => console.error('Error fetching order status:', error));
    }

    // Initial UI update on load
    updateOrderStatusUI("<?php echo $order_details['status']; ?>");

    // Poll for order status every 10 seconds
    statusPolling = setInterval(fetchOrderStatusAndUpdateUI, 10000);

    hubungiKurirBtn.addEventListener('click', function() {
        if (chatBox.style.display === 'none') {
            chatBox.style.display = 'block';
            fetchMessages();
            messagePolling = setInterval(fetchMessages, 5000); // Poll every 5 seconds
        } else {
            chatBox.style.display = 'none';
            clearInterval(messagePolling);
        }
    });

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const messageTextarea = chatForm.querySelector('textarea[name="message"]');
        const message = messageTextarea.value.trim();

        if (message) {
            sendMessage(message);
            messageTextarea.value = '';
        }
    });

    function fetchMessages() {
        fetch(`api/get_messages.php?order_id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderMessages(data.messages);
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
    }

    function sendMessage(message) {
        fetch('api/send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                order_id: orderId,
                sender: 'user',
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

    function renderMessages(messages) {
        chatMessages.innerHTML = '';
        messages.forEach(msg => {
            const messageElement = document.createElement('div');
            messageElement.style.marginBottom = '10px';
            
            let senderLabel = '';
            if (msg.sender === 'user') {
                senderLabel = 'Anda';
                messageElement.style.textAlign = 'right';
            } else {
                senderLabel = 'Admin';
                messageElement.style.textAlign = 'left';
            }

            messageElement.innerHTML = `
                <div style="font-weight: bold; font-size: 0.9em; color: #555;">${senderLabel}</div>
                <div style="background-color: #f1f1f1; padding: 8px; border-radius: 5px; display: inline-block; max-width: 70%; color: #000;">${msg.message}</div>
                <div style="font-size: 0.7em; color: #999;">${new Date(msg.timestamp).toLocaleTimeString()}</div>
            `;
            chatMessages.appendChild(messageElement);
        });
        chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom
    }
});
</script>

<?php include 'templates/footer.php'; ?>