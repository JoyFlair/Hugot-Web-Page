<?php
header('Content-Type: application/json');
include 'db_connect.php'; // Include your database connection file

$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];

// Insert share record
$sql = "INSERT INTO tblshares (post_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $post_id, $user_id);
$result = $stmt->execute();

if ($result) {
    // Update total shares count
    $sql_count = "UPDATE tblposts SET total_shares = total_shares + 1 WHERE post_id = ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param('i', $post_id);
    $stmt_count->execute();

    $response = array('status' => 'success');
} else {
    $response = array('status' => 'error', 'message' => 'Failed to save share');
}

echo json_encode($response);
?>
