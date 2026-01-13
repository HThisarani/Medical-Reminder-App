<?php include '../includes/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTrack - Family Medicine Reminder</title>
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

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            padding: 60px 20px;
        }

        .hero-content {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        h1 {
            font-size: 3em;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .tagline {
            font-size: 1.3em;
            color: #666;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 50px 0;
            text-align: left;
        }

        .feature-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
            padding: 30px;
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }

        .feature-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.3em;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
            font-size: 0.95em;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .btn {
            padding: 18px 40px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.6);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin: 50px 0;
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 1em;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .hero-content {
                padding: 40px 25px;
            }

            h1 {
                font-size: 2em;
            }

            .logo {
                font-size: 60px;
            }

            .tagline {
                font-size: 1.1em;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .stats {
                flex-direction: column;
                gap: 20px;
            }

            .stat-number {
                font-size: 2em;
            }
        }

        .wave {
            animation: wave 2s infinite;
            display: inline-block;
        }

        @keyframes wave {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(20deg);
            }

            75% {
                transform: rotate(-20deg);
            }
        }
    </style>
</head>

<body>
    <div class="hero-container">
        <div class="hero-content">
            <div class="logo">💊</div>
            <h1>Welcome to MediTrack</h1>
            <p class="tagline">
                Your Family's Smart Medicine Reminder System<br>
                Never miss a dose, keep your loved ones healthy and safe
            </p>

            <div class="cta-buttons">
                <a href="dashboard.php" class="btn btn-primary">
                    📊 Go to Dashboard
                    <span>→</span>
                </a>
                <a href="add_medicine.php" class="btn btn-secondary">
                    ➕ Add Medicine
                </a>
            </div>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Monitoring</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Reliable</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">∞</div>
                    <div class="stat-label">Peace of Mind</div>
                </div>
            </div>

            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">⏰</div>
                    <h3>Smart Reminders</h3>
                    <p>Get timely notifications for medicine doses. Never forget to take your medication again.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Track Inventory</h3>
                    <p>Monitor your medicine stock levels and get alerts when supplies are running low.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">👨‍👩‍👧‍👦</div>
                    <h3>Family Care</h3>
                    <p>Manage medications for your entire family in one convenient place.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔔</div>
                    <h3>Custom Schedules</h3>
                    <p>Set personalized reminder times that fit your daily routine perfectly.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>