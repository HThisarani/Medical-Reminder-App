<?php
// Enhanced Navbar for all pages
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    nav {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 1000;
    }

    /* Add padding to body to prevent content from hiding under fixed navbar */
    body {
        padding-top: 70px;
    }

    .nav-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    .nav-brand {
        font-size: 24px;
        font-weight: bold;
        color: white;
        text-decoration: none;
        padding: 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .nav-brand:hover {
        opacity: 0.9;
    }

    .nav-links {
        display: flex;
        list-style: none;
        gap: 5px;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        padding: 20px 20px;
        display: inline-block;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        border-radius: 0;
    }

    .nav-links a::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background: white;
        transition: width 0.3s ease;
    }

    .nav-links a:hover::before {
        width: 80%;
    }

    .nav-links a:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .nav-links a.active {
        background: rgba(255, 255, 255, 0.2);
        font-weight: 600;
    }

    .nav-links a.active::before {
        width: 80%;
    }

    /* Mobile menu toggle */
    .menu-toggle {
        display: none;
        flex-direction: column;
        cursor: pointer;
        padding: 10px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .menu-toggle:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .menu-toggle span {
        width: 25px;
        height: 3px;
        background: white;
        margin: 3px 0;
        transition: all 0.3s ease;
        border-radius: 3px;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        .nav-container {
            flex-wrap: wrap;
        }

        .menu-toggle {
            display: flex;
        }

        .nav-links {
            flex-direction: column;
            width: 100%;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            gap: 0;
        }

        .nav-links.active {
            max-height: 500px;
        }

        .nav-links a {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-links a::before {
            display: none;
        }

        .nav-brand {
            padding: 15px 0;
        }
    }

    /* Icon styles */
    .nav-icon {
        font-size: 18px;
        margin-right: 5px;
    }

    /* Notification badge */
    .notification-badge {
        background: #ff4757;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 11px;
        margin-left: 5px;
        font-weight: bold;
    }
</style>

<nav>
    <div class="nav-container">
        <a href="../pages/index.php" class="nav-brand">
            <span>💊</span>
            <span>MediTrack</span>
        </a>

        <div class="menu-toggle" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <ul class="nav-links" id="navLinks">
            <li>
                <a href="../pages/index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                    <span class="nav-icon">🏠</span>Home
                </a>
            </li>
            <li>
                <a href="../pages/dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                    <span class="nav-icon">📊</span>Dashboard
                </a>
            </li>
            <li>
                <a href="../pages/add_medicine.php" class="<?= $current_page == 'add_medicine.php' ? 'active' : '' ?>">
                    <span class="nav-icon">➕</span>Add Medicine
                </a>
            </li>
            <li>
                <a href="../pages/check_reminders.php" class="<?= $current_page == 'check_reminders.php' ? 'active' : '' ?>">
                    <span class="nav-icon">🔔</span>Reminders
                </a>
            </li>
        </ul>
    </div>
</nav>

<script>
    function toggleMenu() {
        const navLinks = document.getElementById('navLinks');
        navLinks.classList.toggle('active');
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const nav = document.querySelector('nav');
        const menuToggle = document.querySelector('.menu-toggle');
        const navLinks = document.getElementById('navLinks');

        if (!nav.contains(event.target)) {
            navLinks.classList.remove('active');
        }
    });

    // Close menu when window is resized to desktop size
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            document.getElementById('navLinks').classList.remove('active');
        }
    });
</script>