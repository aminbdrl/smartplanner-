<?php
require_once __DIR__ . '/includes/bootstrap.php';

smartwills_require_login();

$activePage = 'profile';
$pageTitle = 'Edit Profile - SmartWills';

$userId = $_SESSION['user_id'] ?? $_SESSION['portal']['user']['id'] ?? 0;
$error = '';
$success = '';

if ($userId > 0) {
    $escapedId = (int)$userId;
    $res = $db->query("SELECT * FROM users WHERE id = $escapedId LIMIT 1");
    $userData = ($res && $res->num_rows > 0) ? $res->rows[0] : [];
}

$fullName = $userData['name'] ?? ($_SESSION['user_name'] ?? '');
$phone    = $userData['phone'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($name)) {
        $error = 'Full Name is required.';
    } else {
        $escapedName  = $db->escape($name);
        $escapedPhone = $db->escape($phone);
        $escapedId    = (int)$userId;

        $updated = $db->query("UPDATE users SET name = '$escapedName', phone = '$escapedPhone' WHERE id = $escapedId");

        if ($updated) {
            $_SESSION['user_name'] = $name;
            header("Location: profile.php");
            exit;
        } else {
            $error = 'Failed to update profile. Please try again.';
        }
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
                <h2 style="margin-bottom: 1.5rem;">Update Personal Information</h2>

                <?php if ($error): ?>
                    <div style="color: red; margin-bottom: 1rem;"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="edit_profile.php">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($fullName); ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="+60 12-3456789" style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <a href="profile.php" class="btn btn-secondary" style="padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; background: #6c757d; color: white;">Cancel</a>
                        <button type="submit" class="btn btn-danger" style="padding: 0.75rem 1.5rem; border-radius: 6px; background: #8B0000; color: white; border: none; cursor: pointer;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>