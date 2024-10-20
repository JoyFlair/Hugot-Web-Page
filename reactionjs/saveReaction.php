<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
include 'connection-pdo.php'; // Make sure this file contains the correct PDO connection setup

$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];
$reaction_type = $_POST['reaction_type'];
$increment = $_POST['increment'];

$response = array('status' => 'error', 'message' => 'Invalid request');

if ($increment == 1) {
    // Insert a new reaction
    $sql = "INSERT INTO tblreactions (post_id, user_id, reaction_type, reaction_date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $post_id, $user_id, $reaction_type);

    if ($stmt->execute()) {
        $response = array('status' => 'success');
    } else {
        $response['message'] = 'Failed to insert reaction';
    }
} elseif ($increment == -1) {
    // Remove an existing reaction
    $sql = "DELETE FROM tblreactions WHERE post_id = ? AND user_id = ? AND reaction_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $post_id, $user_id, $reaction_type);

    if ($stmt->execute()) {
        $response = array('status' => 'success');
    } else {
        $response['message'] = 'Failed to delete reaction';
    }
}

echo json_encode($response);
$conn->close();
?>
