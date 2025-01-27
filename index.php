<?php
// Start session to handle login state
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    setcookie("server_url", "", time() - 3600, "/");
    setcookie("server_password", "", time() - 3600, "/");
    header("Location: index.php");
    exit();
}

// Check if already logged in (using cookies or session)
if (isset($_SESSION['logged_in']) || (isset($_COOKIE['server_url']) && isset($_COOKIE['server_password']))) {
    header("Location: dashboard.php");
    exit();
}

// Handle login form submission
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $server_url = $_POST['server_url'] ?? '';
    $port = $_POST['port'] ?? '8080';
    $password = $_POST['password'] ?? '';
    $stay_logged_in = isset($_POST['stay_logged_in']);

    // Validate inputs
    if (filter_var($server_url, FILTER_VALIDATE_URL) && !empty($password)) {
        // Test API connection
        $api_url = "{$server_url}:{$port}/player/count/?password={$password}";
        $response = @file_get_contents($api_url);
        $data = json_decode($response, true);

        if ($data && $data['succeeded']) {
            // Successful login
            $_SESSION['logged_in'] = true;
            $_SESSION['server_url'] = $server_url;
            $_SESSION['port'] = $port;
            $_SESSION['password'] = $password;

            if ($stay_logged_in) {
                setcookie("server_url", $server_url, time() + (86400 * 7), "/");
                setcookie("server_password", $password, time() + (86400 * 7), "/");
            }

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid server URL, port, or password.";
        }
    } else {
        $error = "Please enter a valid URL and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motor Town Server Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="dark-theme">
    <div class="login-container">
        <h1>Motor Town Server Login</h1>
        <form method="POST">
            <label for="server_url">Server URL:</label>
            <input type="text" id="server_url" name="server_url" placeholder="http://your-server-ip" required>
            
            <label for="port">Port:</label>
            <input type="text" id="port" name="port" placeholder="8080" value="8080">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter server password" required>

            <div class="checkbox-container">
                <input type="checkbox" id="stay_logged_in" name="stay_logged_in">
                <label for="stay_logged_in">Stay logged in</label>
            </div>

            <button type="submit">Login</button>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>
