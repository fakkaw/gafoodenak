<?php
require_once '../api/db_connect.php';

$page_title = "Manajemen Kontak";
include '../templates/admin_header.php';

// Proses form jika ada data yang di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $map_url = $_POST['map_url'];
    $opening_hours = $_POST['opening_hours'];

    // Update informasi kontak di database
    $stmt = $conn->prepare("UPDATE contact_info SET address = ?, phone = ?, email = ?, map_url = ?, opening_hours = ? WHERE id = 1");
    $stmt->bind_param("sssss", $address, $phone, $email, $map_url, $opening_hours);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Informasi kontak berhasil diperbarui.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui informasi kontak.</div>";
    }
    $stmt->close();
}

// Ambil informasi kontak terkini dari database
$result = $conn->query("SELECT * FROM contact_info LIMIT 1");
$contact_info = $result->fetch_assoc();

?>

<div class="container">
    <h2>Manajemen Informasi Kontak</h2>
    <p>Gunakan form di bawah ini untuk mengedit informasi yang tampil di halaman Kontak.</p>

    <form action="contact_management.php" method="POST">
        <div class="form-group">
            <label for="address">Alamat</label>
            <textarea id="address" name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($contact_info['address']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="phone">Telepon</label>
            <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($contact_info['phone']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($contact_info['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="map_url">URL Peta</label>
            <textarea id="map_url" name="map_url" class="form-control" rows="3" required><?php echo htmlspecialchars($contact_info['map_url']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="opening_hours">Jam Buka</label>
            <input type="text" id="opening_hours" name="opening_hours" class="form-control" value="<?php echo htmlspecialchars($contact_info['opening_hours']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <section class="mt-5">
        <h2>Pesan dari Pengunjung</h2>
        <?php
        $messages_result = $conn->query("SELECT * FROM contact_messages ORDER BY sent_at DESC");
        if ($messages_result->num_rows > 0) {
            echo "<table class='data-table'>";
            echo "<thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Pesan</th><th>Dikirim Pada</th></tr></thead>";
            echo "<tbody>";
            while($message = $messages_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($message['id']) . "</td>";
                echo "<td>" . htmlspecialchars($message['name']) . "</td>";
                echo "<td>" . htmlspecialchars($message['email']) . "</td>";
                echo "<td>" . htmlspecialchars($message['message']) . "</td>";
                echo "<td>" . htmlspecialchars($message['sent_at']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>Tidak ada pesan dari pengunjung.</p>";
        }
        ?>
    </section>
</div>

<?php
include '../templates/admin_footer.php';
?>
