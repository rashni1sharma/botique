<?php include 'header.php'; ?>

<div class="main-content">
    <h2>User Management</h2>
    
    <div class="action-bar">
        <button id="add-user-btn" class="btn-primary">Add New User</button>
        <div class="search-box">
            <input type="text" id="user-search" placeholder="Search users...">
            <button id="user-search-btn">Search</button>
        </div>
    </div>
    
    <table id="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Membership</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via JavaScript -->
        </tbody>
    </table>
    
    <!-- User Modal -->
    <div id="user-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3 id="user-modal-title">Add New User</h3>
            <form id="user-form">
                <input type="hidden" id="user-id">
                <div class="form-group">
                    <label for="user-name">Full Name:</label>
                    <input type="text" id="user-name" required>
                </div>
                <div class="form-group">
                    <label for="user-email">Email:</label>
                    <input type="email" id="user-email" required>
                </div>
                <div class="form-group">
                    <label for="user-phone">Phone:</label>
                    <input type="tel" id="user-phone" required>
                </div>
                <div class="form-group">
                    <label for="user-membership">Membership:</label>
                    <select id="user-membership" required>
                        <option value="Basic">Basic</option>
                        <option value="Premium">Premium</option>
                        <option value="VIP">VIP</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="user-status">Status:</label>
                    <select id="user-status" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Suspended">Suspended</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>