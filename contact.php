<?php
require_once 'api/db_connect.php';
$page_title = "Hubungi Kami";
include 'templates/header.php';

// Ambil informasi kontak dari database
$result = $conn->query("SELECT * FROM contact_info LIMIT 1");
$contact_info = $result->fetch_assoc();

$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $message_sent = true;
        } else {
            $error_message = "Gagal mengirim pesan. Silakan coba lagi.";
        }
        $stmt->close();
    } else {
        $error_message = "Harap lengkapi semua kolom.";
    }
}
?>

<div class="container">
    <h1 class="section-title">Hubungi Kami</h1>
    <div class="contact-info">
        <h2>Informasi Kontak</h2>
        <p><strong>Alamat:</strong> <?php echo htmlspecialchars($contact_info['address']); ?></p>
        <p><strong>Telepon:</strong> <?php echo htmlspecialchars($contact_info['phone']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($contact_info['email']); ?></p>
        <p><strong>Jam Buka:</strong> <?php echo htmlspecialchars($contact_info['opening_hours']); ?></p>
    </div>

    <div class="contact-form">
        <h2>Hubungi Saya</h2>
        <?php if ($message_sent): ?>
            <div class="alert alert-success">Terima kasih! Pesan Anda telah terkirim.</div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="contact.php" method="POST">
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Pesan</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn-primary">Kirim Pesan</button>
        </form>
    </div>

    <div class="map-container">
        <h2>Peta Lokasi</h2>
        <iframe src="<?php echo htmlspecialchars($contact_info['map_url']); ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>

<?php
include 'templates/footer.php';
?>
