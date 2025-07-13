<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #ffffff;
        }

        .navbar-top {
            background-color: #00a96e;
            padding: 10px 20px;
        }

        .navbar-top .menu-btn {
            font-size: 24px;
            color: white;
            cursor: pointer;
            float: right;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            display: none;
            z-index: 9;
        }

        .overlay.active {
            display: block;
        }

        /* Sidebar */
 .sidebar {
    position: fixed;
    top: 0;
    right: -280px;
    width: 280px;
    height: 100vh;
    background-color: #ffffff;
    box-shadow: 2px 0 8px rgba(0,0,0,0.2);
    transition: right 0.3s ease;
    z-index: 10;
    padding: 20px;
    overflow-y: auto;
}

        .sidebar.active {
            right: 0;
        }

        .sidebar .close-btn {
            font-size: 26px;
            float: right;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .sidebar a {
            display: block;
            color: #333;
            padding: 10px 0;
            text-decoration: none;
            font-weight: 600;
        }

        .sidebar a:hover {
            background-color: #f1f1f1;
        }

        .sidebar hr {
            margin: 15px 0;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .search-bar select,
        .search-bar input {
            flex: 1;
        }
    </style>
</head>
<body>



    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
            <h4>Selamat datang, <strong><?= $_SESSION['nama']; ?></strong></h4>
        <span class="close-btn" onclick="closeMenu()">×</span>
        <h5 class="mt-4">Navigasi</h5>
        <a href="<?= site_url('home'); ?>">🏠 Home</a>
        <a href="#">📘 About</a>
        <a href="#">🛍️ Cara Beli</a>
        <a href="<?= site_url('cart'); ?>">🛒 Cart</a>

        <?php if ($this->session->userdata('id_pembeli')): ?>
            <a href="<?= site_url('toko'); ?>">🏪 Toko</a>
        <?php endif; ?>

        <hr>

        <h5>Cari Produk</h5>
        <form class="search-bar">
            <select class="form-select">
                <option value="">Kategori</option>
                <option value="elektronik">Elektronik</option>
                <option value="fashion">Fashion</option>
            </select>
            <input type="text" class="form-control" placeholder="Cari...">
        </form>
        <button class="btn btn-success w-100 mt-2" type="submit">🔍 Cari</button>

        <hr>

        <h5>Akun</h5>
        <?php if ($this->session->userdata('logged_in_pembeli')): ?>
            <a href="<?= site_url('cart') ?>">🛒 Cart</a>
            <a href="<?= site_url('home/edit_profile') ?>">👤 Edit Profile</a>
            <a href="<?= site_url('home/logout') ?>">🚪 Logout</a>
        <?php elseif (current_url() == site_url('home/register')): ?>
            <a href="<?= site_url('home/login') ?>">🔑 Login</a>
        <?php elseif (current_url() == site_url('home/login')): ?>
            <a href="<?= site_url('home/register') ?>">📝 Register</a>
        <?php else: ?>
            <a href="<?= site_url('home/login') ?>">🔑 Login</a>
            <a href="<?= site_url('home/register') ?>">📝 Register</a>
        <?php endif; ?>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="overlay" onclick="closeMenu()"></div>

    <script>
        function openMenu() {
            document.getElementById('sidebar').classList.add('active');
            document.getElementById('overlay').classList.add('active');
        }

        function closeMenu() {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
        }
    </script>

</body>
</html>
