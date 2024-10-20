<?php
include 'connection-pdo.php'; // Make sure this file contains the correct PDO connection setup

$post_id = $_POST['post_id'];
$update_field = $_POST['update_field'];
$increment = $_POST['increment'];

if ($update_field && is_numeric($increment)) {
    $query = "UPDATE tblposts SET $update_field = $update_field + ? WHERE post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $increment, $post_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
}

$conn->close();
?>
