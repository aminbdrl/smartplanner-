<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage = 'profile';
$pageTitle = 'Manage Bank Account - SmartWills';

$userId = $_SESSION['user_id'] ?? $_SESSION['portal']['user']['id'] ?? 0;
$error = '';

if ($userId > 0) {
    $escapedId = (int)$userId;
    $res = $db->query("SELECT * FROM users WHERE id = $escapedId LIMIT 1");
    $userData = ($res && $res->num_rows > 0) ? $res->rows[0] : [];
}

$bankName    = $userData['bank_name'] ?? '';
$bankAccount = $userData['bank_account_no'] ?? '';
$bankHolder  = $userData['bank_account_holder'] ?? '';
$bankSwift   = $userData['bank_swift'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bankName   = trim($_POST['bank_name'] ?? '');
    $accountNo  = trim($_POST['bank_account_no'] ?? '');
    $holder     = trim($_POST['bank_account_holder'] ?? '');
    $swift      = trim($_POST['bank_swift'] ?? '');

    $escapedBank   = $db->escape($bankName);
    $escapedAcc    = $db->escape($accountNo);
    $escapedHolder = $db->escape($holder);
    $escapedSwift  = $db->escape($swift);
    $escapedId     = (int)$userId;

    $updated = $db->query("UPDATE users SET 
        bank_name = '$escapedBank', 
        bank_account_no = '$escapedAcc', 
        bank_account_holder = '$escapedHolder', 
        bank_swift = '$escapedSwift' 
        WHERE id = $escapedId");

    if ($updated) {
        header("Location: profile.php");
        exit;
    } else {
        $error = 'Failed to update bank details. Please try again.';
    }
}

include __DIR__ . '/layouts/header.php';
?>
<div class="wrapper">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/layouts/topbar.php'; ?>

        <div class="content" style="max-width: 600px; margin: 2rem auto;">
            <div class="profile-card" style="background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <h2 style="margin-bottom: 1.5rem;">Manage Bank Account</h2>

                <?php if ($error): ?>
                    <div style="color: red; margin-bottom: 1rem;"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="edit_bank.php">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bank Name</label>
                        <input type="text" name="bank_name" value="<?php echo htmlspecialchars($bankName); ?>" placeholder="e.g. Maybank" style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Account Number</label>
                        <input type="text" name="bank_account_no" value="<?php echo htmlspecialchars($bankAccount); ?>" placeholder="e.g. 1234 5678 9012" style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Account Holder Name</label>
                        <input type="text" name="bank_account_holder" value="<?php echo htmlspecialchars($bankHolder); ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">SWIFT Code</label>
                        <input type="text" name="bank_swift" value="<?php echo htmlspecialchars($bankSwift); ?>" placeholder="e.g. MBBEMYKL" style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <a href="profile.php" class="btn btn-secondary" style="padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; background: #6c757d; color: white;">Cancel</a>
                        <button type="submit" class="btn btn-danger" style="padding: 0.75rem 1.5rem; border-radius: 6px; background: #8B0000; color: white; border: none; cursor: pointer;">Save Bank Details</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>