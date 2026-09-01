<?php
include('../includes/header.php');
include('../config/db.php');

// Fetch Customers and Services for the initial dropdowns
$customers = $conn->query("SELECT id, name FROM customers");
$services = $conn->query("SELECT id, service_name FROM services");
?>

<div class="container mt-4">
    <h2>Add Service Request</h2>
    <form action="process_service_request.php" method="POST">
        <div class="mb-3">
            <label for="customer_id" class="form-label">Select Customer</label>
            <select name="customer_id" id="customer_id" class="form-select" required onchange="fetchVehicles(this.value)">
                <option value="">-- Select Customer --</option>
                <?php while($row = $customers->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="vehicle_id" class="form-label">Select Vehicle</label>
            <select name="vehicle_id" id="vehicle_id" class="form-select" required>
                <option value="">-- Select a customer first --</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="service_id" class="form-label">Service Type</label>
            <select name="service_id" id="service_id" class="form-select" required>
                <option value="">-- Select Service --</option>
                <?php while($row = $services->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['service_name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="service_date" class="form-label">Service Date</label>
            <input type="date" name="service_date" id="service_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
        </div>

        <button type="submit" class="btn btn-warning">Create Request</button>
    </form>
</div>

<script>
function fetchVehicles(customerId) {
    const vehicleSelect = document.getElementById('vehicle_id');
    
    // Clear existing options
    vehicleSelect.innerHTML = '<option value="">-- Loading Vehicles... --</option>';

    if (customerId === "") {
        vehicleSelect.innerHTML = '<option value="">-- Select a customer first --</option>';
        return;
    }

    // Fetch vehicles for the selected customer via AJAX
    fetch(`get_vehicles.php?customer_id=${customerId}`)
        .then(response => response.json())
        .then(data => {
            vehicleSelect.innerHTML = '<option value="">-- Select Vehicle --</option>';
            data.forEach(vehicle => {
                let option = document.createElement('option');
                option.value = vehicle.id;
                option.textContent = `${vehicle.model} (${vehicle.reg_number})`;
                vehicleSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching vehicles:', error);
            vehicleSelect.innerHTML = '<option value="">-- Error loading vehicles --</option>';
        });
}
</script>