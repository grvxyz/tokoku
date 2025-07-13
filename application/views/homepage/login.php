<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Marketplace</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="main">
    <div class="container">
        <div class="login-box mx-auto">
            <h4 class="text-center mb-4">Login Pembeli</h4>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
            <?php endif; ?>

            <form action="<?= site_url('home/act_login') ?>" method="post">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3">Belum punya akun? <a href="<?= site_url('home/register') ?>">Daftar di sini</a></p>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
