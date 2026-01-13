<?php
include '../includes/db.php';
include '../includes/navbar.php';

// Use prepared statement for security
$stmt = $db->prepare("SELECT * FROM medicines WHERE family_id = ? ORDER BY name ASC");
$family_id = 1;
$stmt->bind_param("i", $family_id);
$stmt->execute();
$meds = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Dashboard</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 100px;
        }

        h1 {
            color: #333;
            margin-bottom: 25px;
            font-size: 2em;
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:hover {
            background: #f8f9ff;
        }

        .low-stock {
            background: #fff3cd !important;
        }

        .low-stock td {
            color: #856404;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            display: inline-block;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .action-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-links a {
            padding: 6px 14px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-taken {
            background: #28a745;
            color: white;
        }

        .btn-taken:hover {
            background: #218838;
        }

        .btn-edit {
            background: #17a2b8;
            color: white;
        }

        .btn-edit:hover {
            background: #138496;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 1.5em;
            }

            table {
                font-size: 0.9em;
            }

            th,
            td {
                padding: 10px 8px;
            }

            .action-links {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-actions">
            <h1>💊 Medicine Dashboard</h1>
            <a href="add_medicine.php" class="btn btn-primary">+ Add Medicine</a>
        </div>

        <?php if ($meds->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Remaining Quantity</th>
                            <th>Times per Day</th>
                            <th>Low Stock Level</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($med = $meds->fetch_assoc()):
                            $isLowStock = $med['remaining_quantity'] <= $med['low_stock_level'];
                            $rowClass = $isLowStock ? 'low-stock' : '';
                        ?>
                            <tr class="<?= $rowClass ?>">
                                <td><strong><?= htmlspecialchars($med['name']) ?></strong></td>
                                <td><?= htmlspecialchars($med['remaining_quantity']) ?></td>
                                <td><?= htmlspecialchars($med['times_per_day']) ?>×</td>
                                <td><?= htmlspecialchars($med['low_stock_level']) ?></td>
                                <td>
                                    <?php if ($isLowStock): ?>
                                        <span class="status-badge badge-warning">⚠️ Low Stock</span>
                                    <?php else: ?>
                                        <span class="status-badge badge-success">✓ In Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-links">
                                        <a href="take_medicine.php?id=<?= $med['id'] ?>" class="btn-taken">TAKEN</a>
                                        <a href="edit_medicine.php?id=<?= $med['id'] ?>" class="btn-edit">EDIT</a>
                                        <a href="delete_medicine.php?id=<?= $med['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($med['name']) ?>?')">DELETE</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h2>No Medicines Found</h2>
                <p>Start by adding your first medicine to track.</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
<?php
$stmt->close();
$db->close();
?>