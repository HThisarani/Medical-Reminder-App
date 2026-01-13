<?php
include '../includes/db.php';
// Don't include navbar - this page only redirects

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php?error=no_id");
    exit;
}

$id = intval($_GET['id']); // Sanitize input

// Use prepared statement for security
$stmt = $db->prepare("SELECT * FROM medicines WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: dashboard.php?error=not_found");
    exit;
}

$med = $result->fetch_assoc();
$stmt->close();

// Calculate new remaining quantity
$new_remain = $med['remaining_quantity'] - $med['dosage'];

// Prevent negative quantity
if ($new_remain < 0) {
    header("Location: dashboard.php?error=insufficient");
    exit;
}

// Update with prepared statement
$update_stmt = $db->prepare("UPDATE medicines SET remaining_quantity = ? WHERE id = ?");
$update_stmt->bind_param("ii", $new_remain, $id);

if ($update_stmt->execute()) {
    $update_stmt->close();
    $db->close();

    // Check if stock is low
    $is_low_stock = $new_remain <= $med['low_stock_level'];

    if ($is_low_stock) {
        header("Location: dashboard.php?success=taken&low_stock=1&medicine=" . urlencode($med['name']));
    } else {
        header("Location: dashboard.php?success=taken&medicine=" . urlencode($med['name']));
    }
    exit;
} else {
    $update_stmt->close();
    $db->close();
    header("Location: dashboard.php?error=update_failed");
    exit;
}
