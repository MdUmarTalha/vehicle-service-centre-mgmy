<?php
include('../config/db.php');

if (isset($_GET['id'])) {
    $request_id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("DELETE FROM service_requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
        
        if ($stmt->execute()) {
            header("Location: ../dashboard.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        error_log("Delete failed: " . $e->getMessage());
        echo "Error: Could not delete record.";
    }
} else {
    header("Location: ../dashboard.php");
    exit();
}
?>