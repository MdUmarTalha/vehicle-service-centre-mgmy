<?php
include('../includes/header.php');
include('../config/db.php');

$valid_statuses = ['all', 'pending', 'completed'];
$status_filter = isset($_GET['status']) && in_array($_GET['status'], $valid_statuses) ? $_GET['status'] : 'all';

try {
    $query = "SELECT sr.id, sr.service_date, sr.status, s.service_name, s.cost, c.name AS customer_name, v.reg_number
              FROM service_requests sr
              JOIN services s ON sr.service_id = s.id
              JOIN customers c ON sr.customer_id = c.id
              JOIN vehicles v ON sr.vehicle_id = v.id";
    if ($status_filter !== 'all') {
        $query .= " WHERE sr.status = ?";
    }
    $query .= " ORDER BY sr.service_date DESC";
    
    $stmt = $conn->prepare($query);
    if ($status_filter !== 'all') {
        $stmt->bind_param("s", $status_filter);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
    error_log("Query failed: " . $e->getMessage());
    die("Error retrieving service requests. Please try again later.");
}
?>

<h2>Service Requests</h2>
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link <?php echo $status_filter == 'all' ? 'active' : ''; ?>" href="?status=all">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $status_filter == 'pending' ? 'active' : ''; ?>" href="?status=pending">Pending</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $status_filter == 'completed' ? 'active' : ''; ?>" href="?status=completed">Completed</a>
    </li>
</ul>

<?php if ($result->num_rows > 0): ?>
    <table class="table table-striped table-bordered data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Service</th>
                <th>Cost</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                    <td>$<?php echo number_format($row['cost'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['reg_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['service_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a href="edit_service_request.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete_service_request.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">No service requests found.</div>
<?php endif; ?>

<?php $stmt->close(); ?>
