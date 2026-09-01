<?php
include('../config/db.php');

header('Content-Type: application/json');

if (isset($_GET['customer_id'])) {
    $customer_id = intval($_GET['customer_id']);
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, reg_number, model FROM vehicles WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
    
    echo json_encode($vehicles);
    $stmt->close();
} else {
    echo json_encode([]);
}
?>