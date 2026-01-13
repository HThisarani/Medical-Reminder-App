<?php
include '../includes/db.php';
include '../includes/twilio.php';
include '../includes/navbar.php';

date_default_timezone_set('Asia/Colombo');
$now = date("H:i");

$reminders_sent = 0;
$low_stock_alerts = 0;
$medicines_found = [];
$errors = [];

// Use prepared statement to fetch medicines
$stmt = $db->prepare("SELECT * FROM medicines WHERE time_1 = ? OR time_2 = ?");
$stmt->bind_param("ss", $now, $now);
$stmt->execute();
$meds = $stmt->get_result();

while ($med = $meds->fetch_assoc()) {
    $medicines_found[] = $med['name'];

    // Fetch family members with prepared statement
    $member_stmt = $db->prepare("SELECT phone_number FROM family_members WHERE family_id = ?");
    $member_stmt->bind_param("i", $med['family_id']);
    $member_stmt->execute();
    $members = $member_stmt->get_result();

    $phone_numbers = [];
    while ($m = $members->fetch_assoc()) {
        $phone_numbers[] = $m['phone_number'];
    }

    // Send reminder messages
    foreach ($phone_numbers as $phone) {
        try {
            sendMessage($phone, "⏰ Time to take " . $med['name']);
            $reminders_sent++;
        } catch (Exception $e) {
            $errors[] = "Failed to send reminder for " . $med['name'] . " to " . $phone;
        }
    }

    // Check for low stock and send alerts
    if ($med['remaining_quantity'] <= $med['low_stock_level']) {
        foreach ($phone_numbers as $phone) {
            try {
                sendMessage($phone, "⚠️ LOW STOCK: " . $med['name'] . " - only " . $med['remaining_quantity'] . " remaining!");
                $low_stock_alerts++;
            } catch (Exception $e) {
                $errors[] = "Failed to send low stock alert for " . $med['name'] . " to " . $phone;
            }
        }
    }

    $member_stmt->close();
}

$stmt->close();
$db->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Reminders - MediTrack</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 30px auto 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 2em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95em;
        }

        .current-time {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.2em;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
        }

        .stat-icon {
            font-size: 3em;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.95em;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            color: #333;
            font-size: 1.3em;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .medicine-list {
            background: #f8f9ff;
            border-radius: 10px;
            padding: 20px;
        }

        .medicine-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .medicine-item:last-child {
            margin-bottom: 0;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 1.1em;
        }

        .no-data-icon {
            font-size: 4em;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .error-list {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 10px;
        }

        .error-item {
            padding: 10px;
            margin-bottom: 10px;
            background: white;
            border-radius: 5px;
            color: #856404;
        }

        .error-item:last-child {
            margin-bottom: 0;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }

        .btn {
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .success-banner {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 30px;
            border: 2px solid #28a745;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 25px;
            }

            h1 {
                font-size: 1.5em;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>
            <span>🔔</span>
            Medicine Reminders
        </h1>
        <p class="subtitle">Automatic reminder check system</p>

        <div class="current-time">
            🕐 Current Time: <?= date("h:i A") ?> (<?= $now ?>)
        </div>

        <?php if ($reminders_sent > 0 || $low_stock_alerts > 0): ?>
            <div class="success-banner">
                ✓ Reminder check completed successfully!
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">💊</div>
                <div class="stat-number"><?= count($medicines_found) ?></div>
                <div class="stat-label">Medicines Due</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📱</div>
                <div class="stat-number"><?= $reminders_sent ?></div>
                <div class="stat-label">Reminders Sent</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⚠️</div>
                <div class="stat-number"><?= $low_stock_alerts ?></div>
                <div class="stat-label">Low Stock Alerts</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">
                <span>💊</span>
                <span>Medicines Found</span>
            </div>
            <?php if (count($medicines_found) > 0): ?>
                <div class="medicine-list">
                    <?php foreach ($medicines_found as $med_name): ?>
                        <div class="medicine-item">
                            <span>✓</span>
                            <strong><?= htmlspecialchars($med_name) ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <div class="no-data-icon">😴</div>
                    <p>No medicines scheduled for this time</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if (count($errors) > 0): ?>
            <div class="section">
                <div class="section-title">
                    <span>⚠️</span>
                    <span>Errors</span>
                </div>
                <div class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <div class="error-item">
                            ✗ <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="action-buttons">
            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
            <a href="check_reminders.php" class="btn btn-primary">🔄 Check Again</a>
        </div>
    </div>
</body>

</html>