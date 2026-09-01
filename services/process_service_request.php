<?php
include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $customer_id = intval($_POST['customer_id']);
    $vehicle_id = intval($_POST['vehicle_id']);
    $service_id = intval($_POST['service_id']);
    $service_date = $_POST['service_date'];
    $status = 'pending'; // Default status as per schema

    // Basic validation
    if (empty($customer_id) || empty($vehicle_id) || empty($service_id) || empty($service_date)) {
        die("Error: All fields are required.");
    }

    try {
        // Prepare the SQL statement to prevent injection
        $sql = "INSERT INTO service_requests (customer_id, vehicle_id, service_id, service_date, status) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiss", $customer_id, $vehicle_id, $service_id, $service_date, $status);

        if ($stmt->execute()) {
            // Success: Redirect back to the dashboard
            header("Location: ../dashboard.php?msg=request_added");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        error_log("Insert service request failed: " . $e->getMessage());
        echo "An error occurred while saving the request. Please try again.";
    }
} else {
    // Redirect if accessed directly without POST
    header("Location: add_service_request.php");
    exit();
}
?>