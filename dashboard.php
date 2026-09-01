<?php
include('includes/header.php');
include('config/db.php');

try {
    $customer_count = $conn->query("SELECT COUNT(*) FROM customers")->fetch_row()[0];
    $vehicle_count = $conn->query("SELECT COUNT(*) FROM vehicles")->fetch_row()[0];
    $service_count = $conn->query("SELECT COUNT(*) FROM service_requests")->fetch_row()[0];
    $pending_count = $conn->query("SELECT COUNT(*) FROM service_requests WHERE status = 'pending'")->fetch_row()[0];
} catch (mysqli_sql_exception $e) {
    error_log("Dashboard query failed: " . $e->getMessage());
    $customer_count = $vehicle_count = $service_count = $pending_count = 0;
}
?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Customers</h5>
                <p class="card-text display-6"><?php echo $customer_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Vehicles</h5>
                <p class="card-text display-6"><?php echo $vehicle_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Service Requests</h5>
                <p class="card-text display-6"><?php echo $service_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Pending Requests</h5>
                <p class="card-text display-6"><?php echo $pending_count; ?></p>
            </div>
        </div>
    </div>
</div>

<h3>Quick Actions</h3>
<div class="row">
    <div class="col-md-4 mb-4">
        <a href="customers/add_customer.php" class="btn btn-primary w-100">Add Customer</a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="vehicles/add_vehicle.php" class="btn btn-primary w-100">Add Vehicle</a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="services/add_service.php" class="btn btn-primary w-100">Add Service</a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="services/add_service_request.php" class="btn btn-primary w-100">Add Service Request</a>
    </div>
</div>
