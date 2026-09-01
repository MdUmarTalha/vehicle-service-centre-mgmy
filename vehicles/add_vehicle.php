<?php
include('../config/db.php');
include('../includes/header.php');

$errors = [];
$success = '';

try {
    $customer_query = $conn->query("SELECT id, name FROM customers ORDER BY name");
    $customers = $customer_query->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $e) {
    error_log("Error fetching customers: " . $e->getMessage());
    $errors[] = "Unable to load customers. Please try again later.";
}

if (isset($_POST['submit'])) {
    $customer_id = trim($_POST['customer_id']);
    $reg_number = trim($_POST['reg_number']);
    $model = trim($_POST['model']);

    if (!is_numeric($customer_id) || $customer_id <= 0) {
        $errors[] = "Please select a valid customer.";
    }
    if (empty($reg_number) || !preg_match('/^[A-Z0-9-]{1,20}$/', $reg_number)) {
        $errors[] = "Registration number must be 1-20 alphanumeric characters or hyphens.";
    }
    if (empty($model)) {
        $errors[] = "Model is required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM customers WHERE id = ?");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows == 0) {
            $errors[] = "Selected customer does not exist.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT reg_number FROM vehicles WHERE reg_number = ?");
        $stmt->bind_param("s", $reg_number);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Registration number already exists.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO vehicles (customer_id, reg_number, model) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $customer_id, $reg_number, $model);
        
        if ($stmt->execute()) {
            $success = "Vehicle added successfully!";
        } else {
            $errors[] = "Error adding vehicle: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<h2>Add New Vehicle</h2>
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
        <label for="customer_id">Customer</label>
        <select class="form-control" id="customer_id" name="customer_id" required>
            <option value="">Select a customer</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?php echo $customer['id']; ?>" <?php echo isset($_POST['customer_id']) && $_POST['customer_id'] == $customer['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($customer['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">Please select a customer.</div>
    </div>
    <div class="form-group">
        <label for="reg_number">Registration Number</label>
        <input type="text" class="form-control" id="reg_number" name="reg_number" value="<?php echo isset($_POST['reg_number']) ? htmlspecialchars($_POST['reg_number']) : ''; ?>" pattern="[A-Z0-9-]{1,20}" required>
        <div class="invalid-feedback">Please enter a valid registration number.</div>
    </div>
    <div class="form-group">
        <label for="model">Model</label>
        <input type="text" class="form-control" id="model" name="model" value="<?php echo isset($_POST['model']) ? htmlspecialchars($_POST['model']) : ''; ?>" required>
        <div class="invalid-feedback">Please enter a model.</div>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Add Vehicle</button>
</form>

