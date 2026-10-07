<?php
ob_start();
session_start();

require_once __DIR__ . '/../includes/bootstrap.php';

header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? $_SESSION['portal']['user']['id'] ?? 0;

if ($userId <= 0) {
    echo json_encode(['error' => 'Unauthorized user session.']);
    exit;
}

$action = $_GET['action'] ?? '';

// Handle Personal Info Update
if ($action === 'update_profile') {
    $name  = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($name)) {
        echo json_encode(['error' => 'Full Name is required.']);
        exit;
    }

    $escapedName  = $db->escape($name);
    $escapedPhone = $db->escape($phone);
    $escapedId    = (int)$userId;

    $sql = "UPDATE users SET name = '$escapedName', phone = '$escapedPhone' WHERE id = $escapedId";
    $updated = $db->query($sql);

    if ($updated) {
        $_SESSION['user_name'] = $name;
        if (isset($_SESSION['portal']['user'])) {
            $_SESSION['portal']['user']['name'] = $name;
        }
        echo json_encode(['success' => 'Profile updated successfully!']);
    } else {
        echo json_encode(['error' => 'Failed to save profile.']);
    }
    exit;
}

// Handle Bank Account Info Update
if ($action === 'update_bank') {
    $bankName   = trim($_POST['bank_name'] ?? '');
    $accountNo  = trim($_POST['bank_account_no'] ?? '');
    $holder     = trim($_POST['bank_account_holder'] ?? '');
    $swift      = trim($_POST['bank_swift'] ?? '');

    $escapedBank   = $db->escape($bankName);
    $escapedAcc    = $db->escape($accountNo);
    $escapedHolder = $db->escape($holder);
    $escapedSwift  = $db->escape($swift);
    $escapedId     = (int)$userId;

    $sql = "UPDATE users SET 
        bank_name = '$escapedBank', 
        bank_account_no = '$escapedAcc', 
        bank_account_holder = '$escapedHolder', 
        bank_swift = '$escapedSwift' 
        WHERE id = $escapedId";

    $updated = $db->query($sql);

    if ($updated) {
        echo json_encode(['success' => 'Bank details updated successfully!']);
    } else {
        echo json_encode(['error' => 'Failed to save bank details.']);
    }
    exit;
}

echo json_encode(['error' => 'Invalid action specified.']);
exit;