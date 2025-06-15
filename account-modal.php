<!-- Account Modal -->
<div class="account-modal" id="accountModal">
    <div class="account-modal-content">
        <div class="modal-close-btn"><i class="bi bi-x"></i></div>

        <?php if (isset($_SESSION['no_telp'])): ?>
            <!-- User is logged in -->
            <div class="user-info">
                <h3> Profil Pengguna</h3>
                <div class="user-details">
                    <p><strong>Nama:</strong> <?php echo $_SESSION['nama_pengguna']; ?></p>
                    <p><strong>No. Telepon:</strong> <?php echo $_SESSION['no_telp']; ?></p>
                    <?php if (!empty($_SESSION['maps'])): ?>
                        <p><strong>Lokasi:</strong> <a href="<?php echo $_SESSION['maps']; ?>" target="_blank">Lihat di Google Maps</a></p>
                    <?php endif; ?>
                </div>

                <div class="user-actions">
                    <a href="edit-profile.php" class="btn btn-edit">Edit Profil</a>
                    <a href="logout.php" class="btn btn-logout">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <!-- User is not logged in -->
            <div class="login-options">
                <h3>Akun PDP Printing</h3>
                <p>Silakan login atau daftar untuk melanjutkan</p>

                <div class="button-group">
                    <a href="login.php" class="btn btn-login">Login</a>
                    <a href="register.php" class="btn btn-register">Daftar</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .account-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .account-modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 30px;
        width: 400px;
        border-radius: 10px;
        position: relative;
    }

    .modal-close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .user-info h3,
    .login-options h3 {
        text-align: center;
        margin-bottom: 20px;
        color: var(--satu-color);
    }

    .user-details {
        margin-bottom: 20px;
    }

    .user-details p {
        margin: 10px 0;
    }

    .user-actions,
    .button-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn {
        display: inline-block;
        padding: 10px 15px;
        text-align: center;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background-color: #4CAF50;
        color: white;
    }

    .btn-history {
        background-color: #2196F3;
        color: white;
    }

    .btn-logout {
        background-color: #f44336;
        color: white;
    }

    .btn-login,
    .btn-register {
        background-color: var(--satu-color);
        color: white;
    }

    .login-options p {
        text-align: center;
        margin-bottom: 20px;
    }
</style>