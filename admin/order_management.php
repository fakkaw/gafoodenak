<?php
$page_title = "Manajemen Pesanan";
include '../templates/admin_header.php';
require_once '../api/db_connect.php';

// Proses update status

// Ambil semua pesanan
$orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
$statuses = ['Pesanan Diterima', 'Sedang Disiapkan', 'Dalam Perjalanan', 'Tiba di Tujuan'];
?>

<section>
    <h2>Daftar Pesanan</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Alamat</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($order = $orders->fetch_assoc()): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($order['customer_address']); ?></td>
                <td>Rp <?php echo number_format($order['total_amount'], 2, ',', '.'); ?></td>
                <td class="order-status-display" data-order-id="<?php echo $order['id']; ?>"><?php echo htmlspecialchars($order['status']); ?></td>
                <td class="actions">
                    <input type="hidden" class="order-id" value="<?php echo $order['id']; ?>">
                    <select name="status" class="status-select">
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo $status; ?>" <?php echo ($order['status'] == $status) ? 'selected' : ''; ?>>
                                <?php echo $status; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <a href="chat.php?order_id=<?php echo $order['id']; ?>" class="btn-secondary">Chat</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.status-select');

    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const orderId = this.closest('td').querySelector('.order-id').value;
            const newStatus = this.value;

            fetch('../api/update_order_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status pesanan berhasil diperbarui!');
                    // Update the displayed status in the table
                    const statusDisplayElement = document.querySelector(`.order-status-display[data-order-id="${orderId}"]`);
                    if (statusDisplayElement) {
                        statusDisplayElement.textContent = newStatus;
                    }
                } else {
                    alert('Gagal memperbarui status pesanan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui status pesanan.');
            });
        });
    });
});
</script>

<?php include '../templates/admin_footer.php'; ?>