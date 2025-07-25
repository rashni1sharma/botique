<?php include 'header.php'; ?>

<div class="main-content">
    <h2>Rental Management</h2>
    
    <div class="action-bar">
        <button id="add-rental-btn" class="btn-primary">Create New Rental</button>
        <div class="filter-box">
            <select id="rental-status-filter">
                <option value="all">All Rentals</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="overdue">Overdue</option>
            </select>
        </div>
    </div>
    
    <table id="rentals-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Clothes</th>
                <th>Customer</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Cost</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via JavaScript -->
        </tbody>
    </table>
    
    <!-- Rental Modal -->
    <div id="rental-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Create New Rental</h3>
            <form id="rental-form">
                <div class="form-group">
                    <label for="rental-clothes">Clothes:</label>
                    <select id="rental-clothes" required>
                        <option value="">Select Clothes</option>
                        <!-- Options will be loaded via JavaScript -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="rental-customer">Customer:</label>
                    <select id="rental-customer" required>
                        <option value="">Select Customer</option>
                        <!-- Options will be loaded via JavaScript -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="rental-start">Start Date:</label>
                    <input type="date" id="rental-start" required>
                </div>
                <div class="form-group">
                    <label for="rental-end">End Date:</label>
                    <input type="date" id="rental-end" required>
                </div>
                <div class="form-group">
                    <label for="rental-notes">Notes:</label>
                    <textarea id="rental-notes"></textarea>
                </div>
                <button type="submit" class="btn-primary">Create Rental</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>