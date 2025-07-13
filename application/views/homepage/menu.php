<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color:#ffffff ;
            font-family: Arial, sans-serif;
        }

        /* Navbar/Header */
        .navbar {
            background-color:  #00a96e;
        }

        .navbar .btn-menu {
            font-size: 24px;
            color: white;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            right: -250px;
            width: 250px;
            height: 100%;
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
            transition: right 0.3s ease;
            z-index: 10;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            color: #333;
            padding: 10px 0;
            text-decoration: none;
            font-weight: 600;
        }

        .sidebar a:hover {
            background-color: #f0f0f0;
        }

        .sidebar.active {
            right: 0;
        }

        /* Overlay Blur */
        .overlay {
            position: fixed;
            top: 0;
            right: 0;
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

        .close-btn {
            font-size: 28px;
            float: right;
            cursor: pointer;
        }

        /* Konten Utama */
        .main-content {
            padding: 20px;
            margin-top: 60px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('home'); ?>">
                 <img src="<?= base_url('uploads/icons/logo1.png') ?>" style="height: 50px; margin-right: 10px;">
                Marketplace
            </a>
            <button class="btn btn-menu" type="button" onclick="openMenu()">☰</button>
        </div>
    </nav>


</body>
</html>
