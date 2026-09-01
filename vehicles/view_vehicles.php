<?php
include('../includes/header.php');
include('../config/db.php');

$customer_id = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : 0;

try {
    $query = "SELECT v.id, v.reg_number, v.model, c.name AS customer_name 
              FROM vehicles v 
              LEFT JOIN customers c ON v.customer_id = c.id";
    if ($customer_id) {
        $query .= " WHERE v.customer_id = ?";
    }
    $query .= " ORDER BY v.model ASC";
    
    $stmt = $conn->prepare($query);
    if ($customer_id) {
        $stmt->bind_param("i", $customer_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
    error_log("Query failed: " . $e->getMessage());
    die("Error retrieving vehicles. Please try again later.");
}
?>

<h2>All Vehicles</h2>
<?php if ($customer_id): ?>
    <p>Showing vehicles for customer ID: <?php echo $customer_id; ?> <a href="view_vehicles.php" class="btn btn-sm btn-secondary">Show All</a></p>
<?php endif; ?>

<?php if ($result->num_rows > 0): ?>
    <table class="table table-striped table-bordered data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Registration Number</th>
                <th>Model</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name'] ?: 'Unknown'); ?></td>
                    <td><?php echo htmlspecialchars($row['reg_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['model']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">No vehicles found.</div>
<?php endif; ?>

<?php $stmt->close(); ?>
