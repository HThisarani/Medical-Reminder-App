<?php
include '../includes/db.php';
include '../includes/navbar.php';

$success_message = '';
$error_message = '';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php?error=no_id");
    exit;
}

$id = intval($_GET['id']); // Sanitize input

// Fetch medicine details with prepared statement
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

// Handle form submission
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $total = $_POST['total_quantity'];
    $remaining = $_POST['remaining_quantity'];
    $dosage = $_POST['dosage'];
    $times = $_POST['times_per_day'];
    $time1 = $_POST['time_1'];
    $time2 = $_POST['time_2'] ?? null;
    $low = $_POST['low_stock_level'];

    // Use prepared statement for update
    $update_stmt = $db->prepare("UPDATE medicines SET
        name = ?,
        total_quantity = ?,
        remaining_quantity = ?,
        dosage = ?,
        times_per_day = ?,
        time_1 = ?,
        time_2 = ?,
        low_stock_level = ?
        WHERE id = ?");

    $update_stmt->bind_param("sisissisi", $name, $total, $remaining, $dosage, $times, $time1, $time2, $low, $id);

    if ($update_stmt->execute()) {
        $success_message = "Medicine updated successfully!";

        // Refresh medicine data
        $refresh_stmt = $db->prepare("SELECT * FROM medicines WHERE id = ?");
        $refresh_stmt->bind_param("i", $id);
        $refresh_stmt->execute();
        $med = $refresh_stmt->get_result()->fetch_assoc();
        $refresh_stmt->close();
    } else {
        $error_message = "Error updating medicine. Please try again.";
    }

    $update_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medicine - MediTrack</title>
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
            padding-top: 100px;
        }

        .container {
            max-width: 600px;
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

        .message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 500;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .success-message {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 2px solid #28a745;
        }

        .error-message {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 2px solid #dc3545;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
            font-size: 0.95em;
        }

        label small {
            font-weight: 400;
            color: #888;
            font-size: 0.9em;
        }

        input[type="text"],
        input[type="number"],
        input[type="time"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        input:hover {
            border-color: #b0b0b0;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
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

        .time-fields {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .time-fields h3 {
            color: #667eea;
            font-size: 1.1em;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            font-size: 0.9em;
            color: #856404;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
                padding-top: 90px;
            }

            .container {
                padding: 25px;
            }

            h1 {
                font-size: 1.5em;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .btn-group {
                flex-direction: column-reverse;
            }
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>
            <span>✏️</span>
            Edit Medicine
        </h1>
        <p class="subtitle">Update the details of <?= htmlspecialchars($med['name']) ?></p>

        <?php if ($success_message): ?>
            <div class="message success-message">
                ✓ <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="message error-message">
                ✗ <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            ⚠️ Be careful when editing remaining quantity. Changes will affect stock tracking.
        </div>

        <form method="post" action="">
            <div class="form-group full-width">
                <label for="name">Medicine Name *</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($med['name']) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="total_quantity">Total Quantity *</label>
                    <input type="number" id="total_quantity" name="total_quantity" min="1" value="<?= htmlspecialchars($med['total_quantity']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="remaining_quantity">Remaining Quantity *</label>
                    <input type="number" id="remaining_quantity" name="remaining_quantity" min="0" value="<?= htmlspecialchars($med['remaining_quantity']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="dosage">Dosage per Time *</label>
                    <input type="number" id="dosage" name="dosage" min="1" value="<?= htmlspecialchars($med['dosage']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="times_per_day">Times per Day *</label>
                    <input type="number" id="times_per_day" name="times_per_day" min="1" max="6" value="<?= htmlspecialchars($med['times_per_day']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="low_stock_level">Low Stock Alert Level *</label>
                <input type="number" id="low_stock_level" name="low_stock_level" min="1" value="<?= htmlspecialchars($med['low_stock_level']) ?>" required>
            </div>

            <div class="time-fields">
                <h3>🕐 Reminder Times</h3>

                <div class="form-group">
                    <label for="time_1">First Reminder Time *</label>
                    <input type="time" id="time_1" name="time_1" value="<?= htmlspecialchars($med['time_1']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="time_2">Second Reminder Time <small>(optional)</small></label>
                    <input type="time" id="time_2" name="time_2" value="<?= htmlspecialchars($med['time_2'] ?? '') ?>">
                </div>
            </div>

            <div class="btn-group">
                <a href="dashboard.php" class="btn btn-secondary">← Cancel</a>
                <button type="submit" name="submit" class="btn btn-primary">Update Medicine →</button>
            </div>
        </form>
    </div>

    <script>
        // Auto-focus first input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('name').focus();
        });

        // Clear success message after 5 seconds
        const successMsg = document.querySelector('.success-message');
        if (successMsg) {
            setTimeout(() => {
                successMsg.style.animation = 'fadeOut 0.5s ease';
                setTimeout(() => successMsg.remove(), 500);
            }, 5000);
        }

        // Warn if remaining quantity is greater than total quantity
        const totalInput = document.getElementById('total_quantity');
        const remainingInput = document.getElementById('remaining_quantity');

        function checkQuantity() {
            const total = parseInt(totalInput.value);
            const remaining = parseInt(remainingInput.value);

            if (remaining > total) {
                remainingInput.setCustomValidity('Remaining quantity cannot exceed total quantity');
            } else {
                remainingInput.setCustomValidity('');
            }
        }

        totalInput.addEventListener('input', checkQuantity);
        remainingInput.addEventListener('input', checkQuantity);
    </script>
</body>

</html>
<?php
$db->close();
?>