<?php
include('../includes/header.php');
include('../config/db.php');

$message = "";
$request_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing record details
$request = null;
if ($request_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM service_requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $request = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (!$request) {
    echo "<div class='alert alert-danger'>Service request not found.</div>";
    exit;
}

// Fetch dropdown data for the form
try {
    $customers = $conn->query("SELECT id, name FROM customers");
    $vehicles = $conn->query("SELECT id, reg_number, model FROM vehicles");
    $services = $conn->query("SELECT id, service_name FROM services");
} catch (mysqli_sql_exception $e) {
    error_log("Dropdown fetch failed: " . $e->getMessage());
}

// Handle Update Submission
if (isset($_POST['update'])) {
    $customer_id = $_POST['customer_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $service_id = $_POST['service_id'];
    $service_date = $_POST['service_date'];
    $status = $_POST['status'];

    try {
        $update_stmt = $conn->prepare("UPDATE service_requests SET customer_id = ?, vehicle_id = ?, service_id = ?, service_date = ?, status = ? WHERE id = ?");
        $update_stmt->bind_param("iiissi", $customer_id, $vehicle_id, $service_id, $service_date, $status, $request_id);
        
        if ($update_stmt->execute()) {
            $message = "<div class='alert alert-success'>Service request updated successfully!</div>";
            // Refresh local request data
            $request['customer_id'] = $customer_id;
            $request['vehicle_id'] = $vehicle_id;
            $request['service_id'] = $service_id;
            $request['service_date'] = $service_date;
            $request['status'] = $status;
        }
        $update_stmt->close();
    } catch (mysqli_sql_exception $e) {
        $message = "<div class='alert alert-danger'>Update failed: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="container mt-4">
    <h2>Edit Service Request #<?php echo $request_id; ?></h2>
    <?php echo $message; ?>
    
    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" class="form-control" required>
                <?php while($row = $customers->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $request['customer_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Vehicle</label>
            <select name="vehicle_id" class="form-control" required>
                <?php while($row = $vehicles->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $request['vehicle_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['reg_number'] . " - " . $row['model']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Service Type</label>
            <select name="service_id" class="form-control" required>
                <?php while($row = $services->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $request['service_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['service_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Service Date</label>
            <input type="date" name="service_date" class="form-control" value="<?php echo $request['service_date']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" <?php echo ($request['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="completed" <?php echo ($request['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
            </select>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update Request</button>
        <a href="../dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>