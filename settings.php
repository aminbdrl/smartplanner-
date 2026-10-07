<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage = 'settings';
$pageTitle = 'Settings - SmartWills';
$pageStyles = ['settings.css'];
$pageScripts = ['settings.js'];

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>

        <div class="content">
            <header class="settings-header">
                <div class="icon-box"><i class="fas fa-sliders-h"></i></div>
                <div class="greeting">
                    <h1>Settings</h1>
                    <p><i class="fas fa-cog"></i> Manage your account preferences and security</p>
                </div>
            </header>

            <section class="settings-grid" aria-label="Account settings">
                <article class="settings-card">
                    <div class="card-header">
                        <i class="fas fa-lock"></i>
                        Security
                    </div>
                    <form class="settings-form" data-demo-message="Password updated (demo)">
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-key"></i> Current Password</span>
                            <input type="password" placeholder="Enter your current password" required>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-lock"></i> New Password</span>
                            <input type="password" placeholder="Enter a strong new password" required>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-check-circle"></i> Confirm Password</span>
                            <input type="password" placeholder="Re-enter your new password" required>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Password
                            </button>
                        </div>
                    </form>
                </article>

                <article class="settings-card">
                    <div class="card-header">
                        <i class="fas fa-user-edit"></i>
                        Personal Information
                    </div>
                    <form class="settings-form" data-demo-message="Personal information saved (demo)">
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-user"></i> Full Name</span>
                            <input type="text" placeholder="Enter your full name" value="John Doe">
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-birthday-cake"></i> Date of Birth</span>
                            <input type="date" value="1990-01-01">
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-id-badge"></i> Account ID</span>
                            <input type="text" value="SW-10001" readonly>
                        </div>
                        <div class="setting-row">
                            <span class="label"><i class="fas fa-envelope"></i> Email</span>
                            <input type="email" placeholder="Enter your email" value="john.doe@example.com">
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </article>
            </section>
        </div>
    </main>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>
