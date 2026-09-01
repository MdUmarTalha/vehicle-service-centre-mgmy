<?php
include('../config/db.php');
include('../includes/header.php');

$errors = [];
$success = '';

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Phone must be a 10-digit number.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Email is already registered.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO customers (name, phone, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $email);
        
        if ($stmt->execute()) {
            $success = "Customer added successfully!";
        } else {
            $errors[] = "Error adding customer: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<h2>Add New Customer</h2>
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
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
        <div class="invalid-feedback">Please enter a name.</div>
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" pattern="[0-9]{10}" required>
        <div class="invalid-feedback">Please enter a 10-digit phone number.</div>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
        <div class="invalid-feedback">Please enter a valid email.</div>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Add Customer</button>
</form>

