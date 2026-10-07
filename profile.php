<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage = 'profile';
$pageTitle = 'My Profile - SmartWills';
$pageStyles = ['profile.css'];
$pageScripts = ['profile.js'];

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>

        <div class="content">
            <header class="profile-header">
                <div class="avatar">JD</div>
                <div class="greeting">
                    <h1>My Profile</h1>
                    <p><i class="fas fa-user-check"></i> Welcome back, John Doe</p>
                </div>
            </header>

            <section class="profile-grid" aria-label="Profile information">
                <article class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-id-card"></i>
                        Personal Information
                    </div>
                    <div class="info-row">
                        <span class="label">Full Name</span>
                        <span class="value">John Doe</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Email</span>
                        <span class="value"><i class="fas fa-envelope"></i> john.doe@example.com</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Phone</span>
                        <span class="value"><i class="fas fa-phone"></i> +60 12-3456789</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Role</span>
                        <span class="value"><i class="fas fa-user-tag"></i> Administrator</span>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-primary" data-demo-message="Update Profile (demo)">
                            <i class="fas fa-edit"></i>
                            Update Profile
                        </button>
                    </div>
                </article>

                <article class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-university"></i>
                        Bank Account Information
                    </div>
                    <div class="info-row">
                        <span class="label">Bank Name</span>
                        <span class="value"><i class="fas fa-building"></i> Maybank</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Account Number</span>
                        <span class="value"><i class="fas fa-credit-card"></i> 1234 5678 9012</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Account Holder</span>
                        <span class="value">John Doe</span>
                    </div>
                    <div class="info-row">
                        <span class="label">SWIFT Code</span>
                        <span class="value">MBBEMYKL</span>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-outline" data-demo-message="Manage Bank Account (demo)">
                            <i class="fas fa-cog"></i>
                            Manage Account
                        </button>
                    </div>
                </article>
            </section>
        </div>
    </main>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>
