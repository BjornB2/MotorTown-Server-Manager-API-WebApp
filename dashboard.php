<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['logged_in']) && (!isset($_COOKIE['server_url']) || !isset($_COOKIE['server_password']))) {
    header("Location: index.php");
    exit();
}

// Retrieve server details from session or cookies
$server_url = $_SESSION['server_url'] ?? $_COOKIE['server_url'];
$port = $_SESSION['port'] ?? '8080';
$password = $_SESSION['password'] ?? $_COOKIE['server_password'];

// Check for logout
if (isset($_GET['logout'])) {
    session_destroy();
    setcookie("server_url", "", time() - 3600, "/");
    setcookie("server_password", "", time() - 3600, "/");
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motor Town Server Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
</head>
<body class="dark-theme">
    <header>
        <h1>Motor Town Server Management</h1>
        <button onclick="window.location.href='index.php?logout=true';">Logout</button>
    </header>
    <main>
        <div class="tabs">
            <button class="tab-button active" data-tab="online-players">Online Players</button>
            <button class="tab-button" data-tab="banned-players">Banned Players</button>
        </div>
        <div id="tab-content">
            <!-- Online Players Tab -->
            <div class="tab-panel" id="online-players" style="display: block;">
                <h2>Online Players</h2>
                <ul id="players-list">
                    <!-- Populated dynamically via AJAX -->
                </ul>
            </div>

            <!-- Banned Players Tab -->
            <div class="tab-panel" id="banned-players">
                <h2>Banned Players</h2>
                <ul id="banned-list">
                    <!-- Populated dynamically via AJAX -->
                </ul>
            </div>
        </div>
    </main>

    <footer>
        <button id="debug-toggle">Debug Info</button>
        <div id="debug-box" style="display: none;">
            <pre id="debug-output"></pre>
        </div>
    </footer>
</body>
</html>
