<?php
include('../config/db.php');
include('../includes/header.php');

$errors = [];
$success = '';

if (isset($_POST['submit'])) {
    $service_name = trim($_POST['service_name']);
    $cost = trim($_POST['cost']);

    if (empty($service_name)) {
        $errors[] = "Service name is required.";
    }
    if (!is_numeric($cost) || $cost < 0) {
        $errors[] = "Cost must be a valid non-negative number.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO services (service_name, cost) VALUES (?, ?)");
        $stmt->bind_param("sd", $service_name, $cost);
        
        if ($stmt->execute()) {
            $success = "Service added successfully!";
        } else {
            $errors[] = "Error adding service: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<h2>Add New Service</h2>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="error">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" class="needs-validation" novalidate>
    <div class="form-group">
        <label for="service_name">Service Name</label>
        <input type="text" class="form-control" id="service_name" name="service_name" value="<?php echo isset($_POST['service_name']) ? htmlspecialchars($_POST['service_name']) : ''; ?>" required>
        <div class="invalid-feedback">Please enter a service name.</div>
    </div>
    <div class="form-group">
        <label for="cost">Cost ($)</label>
        <input type="number" step="0.01" class="form-control" id="cost" name="cost" value="<?php echo isset($_POST['cost']) ? htmlspecialchars($_POST['cost']) : ''; ?>" required>
        <div class="invalid-feedback">Please enter a valid cost.</div>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Add Service</button>
</form>

