<?php
include('../includes/header.php');
include('../config/db.php');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_param = "%" . $search . "%";

try {
    $query = "SELECT id, name, phone, email FROM customers";
    if ($search) {
        $query .= " WHERE name LIKE ? OR email LIKE ?";
    }
    $query .= " ORDER BY name ASC";
    
    $stmt = $conn->prepare($query);
    if ($search) {
        $stmt->bind_param("ss", $search_param, $search_param);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
    error_log("Query failed: " . $e->getMessage());
    die("Error retrieving customers. Please try again later.");
}
?>

<h2>All Customers</h2>
<form method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

<?php if ($result->num_rows > 0): ?>
    <table class="table table-striped table-bordered data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">No customers found.</div>
<?php endif; ?>

<?php $stmt->close(); ?>
