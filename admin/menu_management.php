<?php
$page_title = "Manajemen Menu";
include '../templates/admin_header.php';
require_once '../api/db_connect.php';

// Direktori untuk menyimpan gambar
$upload_dir = '../assets/uploads/';

// Proses form (tambah/edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $id = $_POST['id'] ?? null;
    $image_url = $_POST['current_image_url'] ?? null; // Ambil URL gambar saat ini jika ada

    // Tangani upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid('menu_') . '.' . $file_ext;
            $destination = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp_name, $destination)) {
                $image_url = 'assets/uploads/' . $new_file_name;
            } else {
                echo "<div class=\"error\">Gagal mengupload gambar.</div>";
            }
        } else {
            echo "<div class=\"error\">Hanya file JPG, JPEG, PNG, GIF yang diizinkan.</div>";
        }
    }

    if ($id) { // Update
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image_url=? WHERE id=?");
        $stmt->bind_param("ssdss", $name, $description, $price, $image_url, $id);
    } else { // Insert
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $image_url);
    }
    $stmt->execute();
    header("Location: menu_management.php");
    exit();
}

// Proses hapus
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Hapus gambar terkait jika ada
    $result = $conn->query("SELECT image_url FROM products WHERE id=$id");
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        if ($product['image_url'] && file_exists('../' . $product['image_url'])) {
            unlink('../' . $product['image_url']);
        }
    }
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: menu_management.php");
    exit();
}

// Ambil data untuk form edit
$edit_product = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM products WHERE id=$id");
    $edit_product = $result->fetch_assoc();
}

// Ambil semua produk
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<section>
    <h2><?php echo $edit_product ? 'Edit' : 'Tambah'; ?> Menu Makanan</h2>
    <form action="menu_management.php" method="POST" enctype="multipart/form-data" class="menu-form">
        <input type="hidden" name="id" value="<?php echo $edit_product['id'] ?? ''; ?>">
        <input type="hidden" name="current_image_url" value="<?php echo $edit_product['image_url'] ?? ''; ?>">
        <div class="form-group">
            <label for="name">Nama Makanan</label>
            <input type="text" id="name" name="name" value="<?php echo $edit_product['name'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="3"><?php echo $edit_product['description'] ?? ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="price">Harga</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $edit_product['price'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Foto Makanan</label>
            <input type="file" id="image" name="image">
            <?php if ($edit_product && $edit_product['image_url']): ?>
                <p>Gambar saat ini:</p>
                <img src="../<?php echo htmlspecialchars($edit_product['image_url']); ?>" alt="Current Image" style="max-width: 100px; height: auto; margin-top: 5px;">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn-primary"><?php echo $edit_product ? 'Update' : 'Tambah'; ?> Menu</button>
        <?php if ($edit_product): ?>
            <a href="menu_management.php" class="btn-secondary">Batal Edit</a>
        <?php endif; ?>
    </form>
</section>

<section>
    <h2>Daftar Menu</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $products->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td>
                    <?php if ($row['image_url']): ?>
                        <img src="../<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                    <?php else: ?>
                        Tidak ada foto
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>Rp <?php echo number_format($row['price'], 2, ',', '.'); ?></td>
                <td class="actions">
                    <a href="menu_management.php?edit=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                    <a href="menu_management.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus menu ini?')" class="btn-delete">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

<?php include '../templates/admin_footer.php'; ?>
