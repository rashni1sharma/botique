document.addEventListener('DOMContentLoaded', function() {
    // Sidebar navigation
    const menuItems = document.querySelectorAll('.sidebar li');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            menuItems.forEach(i => i.classList.remove('completed'));
            
            // Add active class to clicked item
            this.classList.add('completed');
            
            // Get the page name (simplified for demo)
            const pageName = this.textContent.trim().toLowerCase();
            if(pageName !== 'logout') {
                window.location.href = pageName + '.php';
            } else {
                // Handle logout
                alert('Logging out...');
            }
        });
    });
    
    // Set current page as active
    const currentPage = window.location.pathname.split('/').pop().replace('.php', '');
    if(currentPage) {
        menuItems.forEach(item => {
            if(item.textContent.trim().toLowerCase() === currentPage) {
                item.classList.add('completed');
            }
        });
    } else {
        // Default to dashboard
        document.querySelector('.sidebar li:first-child').classList.add('completed');
    }
    
    // Modal functionality
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        const closeBtn = modal.querySelector('.close');
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    });
    
    window.addEventListener('click', (e) => {
        modals.forEach(modal => {
            if(e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Clothes Page Specific
    if(document.getElementById('add-clothes-btn')) {
        const addClothesBtn = document.getElementById('add-clothes-btn');
        const clothesModal = document.getElementById('clothes-modal');
        
        addClothesBtn.addEventListener('click', () => {
            document.getElementById('modal-title').textContent = 'Add New Clothes';
            document.getElementById('clothes-form').reset();
            clothesModal.style.display = 'block';
        });
        
        // Sample data for demo
        const clothesData = [
            { id: 1, name: 'Summer Dress', category: 'Dress', size: 'M', price: 29.99, status: 'Available' },
            { id: 2, name: 'Denim Jeans', category: 'Pants', size: '32', price: 39.99, status: 'Rented' },
            { id: 3, name: 'Silk Blouse', category: 'Shirt', size: 'S', price: 24.99, status: 'Available' }
        ];
        
        renderClothesTable(clothesData);
    }
    
    // Users Page Specific
    if(document.getElementById('add-user-btn')) {
        const addUserBtn = document.getElementById('add-user-btn');
        const userModal = document.getElementById('user-modal');
        
        addUserBtn.addEventListener('click', () => {
            document.getElementById('user-modal-title').textContent = 'Add New User';
            document.getElementById('user-form').reset();
            userModal.style.display = 'block';
        });
        
        // Sample data for demo
        const usersData = [
            { id: 1, name: 'John Doe', email: 'john@example.com', phone: '555-1234', membership: 'Premium', status: 'Active' },
            { id: 2, name: 'Jane Smith', email: 'jane@example.com', phone: '555-5678', membership: 'Basic', status: 'Active' }
        ];
        
        renderUsersTable(usersData);
    }
    
    // Rentals Page Specific
    if(document.getElementById('add-rental-btn')) {
        const addRentalBtn = document.getElementById('add-rental-btn');
        const rentalModal = document.getElementById('rental-modal');
        
        addRentalBtn.addEventListener('click', () => {
            rentalModal.style.display = 'block';
        });
        
        // Sample data for demo
        const rentalsData = [
            { 
                id: 1, 
                clothes: 'Summer Dress', 
                customer: 'John Doe', 
                startDate: '2023-06-01', 
                endDate: '2023-06-08', 
                totalCost: 29.99, 
                status: 'Completed' 
            },
            { 
                id: 2, 
                clothes: 'Denim Jeans', 
                customer: 'Jane Smith', 
                startDate: '2023-06-10', 
                endDate: '2023-06-17', 
                totalCost: 39.99, 
                status: 'Active' 
            }
        ];
        
        renderRentalsTable(rentalsData);
    }
    
    // Initialize charts on dashboard
    if(document.getElementById('incomeChart')) {
        initIncomeChart();
    }
});

// Helper functions
function renderClothesTable(data) {
    const tbody = document.querySelector('#clothes-table tbody');
    tbody.innerHTML = '';
    
    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.name}</td>
            <td>${item.category}</td>
            <td>${item.size}</td>
            <td>$${item.price.toFixed(2)}</td>
            <td><span class="status-badge ${item.status.toLowerCase()}">${item.status}</span></td>
            <td>
                <button class="btn btn-primary edit-btn" data-id="${item.id}">Edit</button>
                <button class="btn btn-danger delete-btn" data-id="${item.id}">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
    
    // Add event listeners to buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            editClothes(id);
        });
    });
    
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if(confirm('Are you sure you want to delete this item?')) {
                // In a real app, you would make an AJAX call here
                alert(`Clothes item ${id} would be deleted in a real app`);
            }
        });
    });
}

function renderUsersTable(data) {
    const tbody = document.querySelector('#users-table tbody');
    tbody.innerHTML = '';
    
    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.name}</td>
            <td>${item.email}</td>
            <td>${item.phone}</td>
            <td>${item.membership}</td>
            <td><span class="status-badge ${item.status.toLowerCase()}">${item.status}</span></td>
            <td>
                <button class="btn btn-primary edit-user-btn" data-id="${item.id}">Edit</button>
                <button class="btn btn-danger delete-user-btn" data-id="${item.id}">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
    
    // Add event listeners to buttons
    document.querySelectorAll('.edit-user-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            editUser(id);
        });
    });
}

function renderRentalsTable(data) {
    const tbody = document.querySelector('#rentals-table tbody');
    tbody.innerHTML = '';
    
    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.id}</td>
            <td>${item.clothes}</td>
            <td>${item.customer}</td>
            <td>${formatDate(item.startDate)}</td>
            <td>${formatDate(item.endDate)}</td>
            <td>$${item.totalCost.toFixed(2)}</td>
            <td><span class="status-badge ${item.status.toLowerCase()}">${item.status}</span></td>
            <td>
                <button class="btn btn-primary">Details</button>
                ${item.status === 'Active' ? '<button class="btn btn-success">Return</button>' : ''}
            </td>
        `;
        tbody.appendChild(row);
    });
}

function editClothes(id) {
    // In a real app, you would fetch the data from the server
    const sampleData = {
        id: id,
        name: 'Sample Item',
        category: 'Dress',
        size: 'M',
        price: 29.99,
        status: 'Available'
    };
    
    document.getElementById('modal-title').textContent = 'Edit Clothes';
    document.getElementById('clothes-id').value = sampleData.id;
    document.getElementById('name').value = sampleData.name;
    document.getElementById('category').value = sampleData.category;
    document.getElementById('size').value = sampleData.size;
    document.getElementById('price').value = sampleData.price;
    document.getElementById('status').value = sampleData.status;
    
    document.getElementById('clothes-modal').style.display = 'block';
}

function editUser(id) {
    // In a real app, you would fetch the data from the server
    const sampleData = {
        id: id,
        name: 'Sample User',
        email: 'sample@example.com',
        phone: '555-0000',
        membership: 'Basic',
        status: 'Active'
    };
    
    document.getElementById('user-modal-title').textContent = 'Edit User';
    document.getElementById('user-id').value = sampleData.id;
    document.getElementById('user-name').value = sampleData.name;
    document.getElementById('user-email').value = sampleData.email;
    document.getElementById('user-phone').value = sampleData.phone;
    document.getElementById('user-membership').value = sampleData.membership;
    document.getElementById('user-status').value = sampleData.status;
    
    document.getElementById('user-modal').style.display = 'block';
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

function initIncomeChart() {
    const ctx = document.getElementById('incomeChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Income ($)',
                data: [1200, 1900, 1500, 2000, 1800, 2200],
                backgroundColor: 'rgba(52, 152, 219, 0.7)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
}