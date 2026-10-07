<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage = 'profile';
$pageTitle = 'My Profile - SmartWills';
$pageStyles = ['profile.css'];

// Fetch current user details from Database
$userId = $_SESSION['user_id'] ?? $_SESSION['portal']['user']['id'] ?? 0;
$userData = null;

if ($userId > 0) {
    $escapedId = (int)$userId;
    $res = $db->query("SELECT * FROM users WHERE id = $escapedId LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $userData = $res->rows[0];
    }
}

// Map user values with fallbacks
$fullName    = $userData['name'] ?? ($_SESSION['user_name'] ?? 'User');
$email       = $userData['email'] ?? ($_SESSION['user_email'] ?? '-');
$phone       = $userData['phone'] ?? '';
$role        = !empty($userData['role']) ? ucfirst($userData['role']) : 'Admin';

$bankName    = $userData['bank_name'] ?? '';
$bankAccount = $userData['bank_account_no'] ?? '';
$bankHolder  = $userData['bank_account_holder'] ?? '';
$bankSwift   = $userData['bank_swift'] ?? '';

// Generate Avatar Initials
$words = explode(' ', trim($fullName));
$initials = '';
foreach ($words as $w) {
    if (!empty($w)) {
        $initials .= strtoupper($w[0]);
    }
}
$avatarInitials = substr($initials, 0, 2) ?: 'U';

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>

        <div class="content">
            <header class="profile-header">
                <div class="avatar"><?php echo htmlspecialchars($avatarInitials); ?></div>
                <div class="greeting">
                    <h1>My Profile</h1>
                    <p><i class="fas fa-user-check"></i> Welcome back, <?php echo htmlspecialchars($fullName); ?></p>
                </div>
            </header>

            <section class="profile-grid" aria-label="Profile information">
                <!-- Personal Information -->
                <article class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-id-card"></i>
                        Personal Information
                    </div>
                    <div class="info-row">
                        <span class="label">FULL NAME</span>
                        <span class="value"><?php echo htmlspecialchars($fullName); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">EMAIL</span>
                        <span class="value"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($email); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">PHONE</span>
                        <span class="value"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($phone ?: 'Not set'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">ROLE</span>
                        <span class="value"><i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($role); ?></span>
                    </div>
                    <div class="btn-group">
                        <a href="edit_profile.php" class="btn btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-edit"></i>
                            Update Profile
                        </a>
                    </div>
                </article>

                <!-- Bank Account Information -->
                <article class="profile-card">
                    <div class="card-header">
                        <i class="fas fa-university"></i>
                        Bank Account Information
                    </div>
                    <div class="info-row">
                        <span class="label">BANK NAME</span>
                        <span class="value"><i class="fas fa-building"></i> <?php echo htmlspecialchars($bankName ?: 'Not set'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">ACCOUNT NUMBER</span>
                        <span class="value"><i class="fas fa-credit-card"></i> <?php echo htmlspecialchars($bankAccount ?: 'Not set'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">ACCOUNT HOLDER</span>
                        <span class="value"><?php echo htmlspecialchars($bankHolder ?: 'Not set'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">SWIFT CODE</span>
                        <span class="value"><?php echo htmlspecialchars($bankSwift ?: 'Not set'); ?></span>
                    </div>
                    <div class="btn-group">
                        <a href="edit_bank.php" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-cog"></i>
                            Manage Account
                        </a>
                    </div>
                </article>
            </section>
        </div>
    </main>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>
