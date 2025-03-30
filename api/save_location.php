session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    die('Unauthorized');
}