<?php
session_start();

// Verify if user is logged in
if (!isset($_SESSION['server_url'], $_SESSION['port'], $_SESSION['password']) &&
    (!isset($_COOKIE['server_url'], $_COOKIE['server_password']))) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Get server details from session or cookies
$server_url = $_SESSION['server_url'] ?? $_COOKIE['server_url'];
$port = $_SESSION['port'] ?? '8080';
$password = $_SESSION['password'] ?? $_COOKIE['server_password'];

// Get action from request
$action = $_GET['action'] ?? null;

// Helper function to perform cURL requests
function makeApiRequest($endpoint, $method = 'GET', $query_data = [], $post_data = [])
{
    global $server_url, $port;

    // Construct GET query string
    $url = "{$server_url}:{$port}{$endpoint}?" . http_build_query($query_data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

    // Add POST data if method is POST
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    }

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ["succeeded" => false, "message" => $error];
    }

    return json_decode($response, true);
}

// Handle API actions
switch ($action) {
    case 'online_players':
        $response = makeApiRequest('/player/list', 'GET', ['password' => $password]);
        echo json_encode([
            'succeeded' => $response['succeeded'],
            'players' => $response['data'] ?? [],
            'message' => $response['message'] ?? ''
        ]);
        break;

    case 'banned_players':
        $response = makeApiRequest('/player/banlist', 'GET', ['password' => $password]);
        echo json_encode([
            'succeeded' => $response['succeeded'],
            'banned' => $response['data'] ?? [],
            'message' => $response['message'] ?? ''
        ]);
        break;

    case 'kick':
        $unique_id = $_GET['unique_id'] ?? null;
        if (!$unique_id) {
            echo json_encode(["succeeded" => false, "message" => "Missing unique_id"]);
            exit();
        }

        // Send password and unique_id via GET and POST
        $response = makeApiRequest(
            '/player/kick',
            'POST',
            ['password' => $password, 'unique_id' => $unique_id],
            ['password' => $password, 'unique_id' => $unique_id]
        );

        echo json_encode($response);
        break;

    case 'ban':
        $unique_id = $_GET['unique_id'] ?? null;
        if (!$unique_id) {
            echo json_encode(["succeeded" => false, "message" => "Missing unique_id"]);
            exit();
        }

        // Send password and unique_id via GET and POST
        $response = makeApiRequest(
            '/player/ban',
            'POST',
            ['password' => $password, 'unique_id' => $unique_id],
            ['password' => $password, 'unique_id' => $unique_id]
        );

        echo json_encode($response);
        break;

    case 'unban':
        $unique_id = $_GET['unique_id'] ?? null;
        if (!$unique_id) {
            echo json_encode(["succeeded" => false, "message" => "Missing unique_id"]);
            exit();
        }

        // Send password and unique_id via GET and POST
        $response = makeApiRequest(
            '/player/unban',
            'POST',
            ['password' => $password, 'unique_id' => $unique_id],
            ['password' => $password, 'unique_id' => $unique_id]
        );

        echo json_encode($response);
        break;

    default:
        http_response_code(400);
        echo json_encode(["error" => "Invalid action"]);
        break;
}
?>
