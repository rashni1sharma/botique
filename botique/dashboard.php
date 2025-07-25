<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>botique - Clothing Rental Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --dark-color: #2d3436;
            --light-color: #f5f6fa;
            --success-color: #00b894;
            --warning-color: #fdcb6e;
            --danger-color: #d63031;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100vh;
            position: fixed;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #eee;
        }
        
        .sidebar-header h3 {
            color: var(--primary-color);
            font-weight: 700;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu h4 {
            padding: 0 20px 10px;
            color: #888;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .sidebar-menu ul {
            list-style: none;
        }
        
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .sidebar-menu li a:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
        }
        
        .sidebar-menu li a.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            transition: all 0.3s ease;
        }
        
        /* Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 99;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
        }
        
        .menu-toggle {
            font-size: 20px;
            cursor: pointer;
            margin-right: 20px;
            color: var(--dark-color);
            display: none;
        }
        
        .search-bar {
            position: relative;
            width: 300px;
        }
        
        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 30px;
            outline: none;
            transition: all 0.3s ease;
        }
        
        .search-bar input:focus {
            border-color: var(--primary-color);
        }
        
        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
        }
        
        .navbar-item {
            margin-left: 20px;
            position: relative;
            cursor: pointer;
        }
        
        .navbar-item i {
            font-size: 20px;
            color: var(--dark-color);
        }
        
        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
        }
        
        .user-profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        
        .user-profile span {
            font-weight: 500;
            color: var(--dark-color);
        }
        
        /* Content Styles */
        .content {
            padding: 30px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            font-size: 24px;
            color: var(--dark-color);
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #5649c0;
        }
        
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .card-img {
            height: 200px;
            overflow: hidden;
        }
        
        .card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }
        
        .card:hover .card-img img {
            transform: scale(1.1);
        }
        
        .card-body {
            padding: 15px;
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark-color);
        }
        
        .card-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .card-price {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .card-footer {
            display: flex;
            justify-content: space-between;
            padding: 0 15px 15px;
        }
        
        .btn-sm {
            padding: 8px 15px;
            font-size: 13px;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .search-bar {
                width: 200px;
            }
        }
        
        @media (max-width: 768px) {
            .search-bar {
                display: none;
            }
            
            .user-profile span {
                display: none;
            }
        }
        
        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 5px;
            z-index: 1;
            padding: 10px 0;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        .dropdown-item {
            padding: 10px 20px;
            color: var(--dark-color);
            text-decoration: none;
            display: block;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
        }
        
        .dropdown-divider {
            height: 1px;
            background-color: #eee;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Botique</h3>
        </div>
        <div class="sidebar-menu">
            <h4>Main</h4>
            <ul>
                <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="collection.php"><i class="fas fa-calendar-alt"></i> Reservations</a></li>
                <li><a href="about.php"><i class="fas fa-tshirt"></i> About us</a></li>
                <li><a href="contact.php"><i class="fas fa-history"></i>Help and support</a></li>
            </ul>
            
            <h4>Categories</h4>
            <ul>
                <li><a href="#"><i class="fas fa-female"></i> Women's Fashion</a></li>
                <li><a href="#"><i class="fas fa-male"></i> Men's Fashion</a></li>
                <li><a href="#"><i class="fas fa-child"></i> Kids' Fashion</a></li>
                <li><a href="#"><i class="fas fa-ring"></i> Accessories</a></li>
                <li><a href="#"><i class="fas fa-shoe-prints"></i> Footwear</a></li>
            </ul>
        
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search for outfits...">
                </div>
            </div>
            <div class="navbar-right">
                <div class="navbar-item">
                    <i class="far fa-bell"></i>
                    <div class="badge">3</div>
                </div>
                <div class="navbar-item">
                    <i class="far fa-envelope"></i>
                    <div class="badge">5</div>
                </div>
                <div class="navbar-item dropdown">
                    <i class="far fa-heart"></i>
                    <div class="badge">2</div>
                    <div class="dropdown-content">
                        <a href="#" class="dropdown-item"><i class="fas fa-heart mr-2"></i> Wishlist (2 items)</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"><i class="fas fa-tshirt mr-2"></i> Floral Summer Dress</a>
                        <a href="#" class="dropdown-item"><i class="fas fa-vest mr-2"></i> Designer Blazer</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"><i class="fas fa-shopping-bag mr-2"></i> View All</a>
                    </div>
                </div>
                <div class="navbar-item dropdown">
                    <div class="user-profile">
                        <img src="images/profile-pic.png" alt="User">
                        <span>Roshani Sharma</span>
                    </div>
                    <div class="dropdown-content">
                        <a href="#" class="dropdown-item"><i class="fas fa-user mr-2"></i> My Profile</a>
                        <a href="#" class="dropdown-item"><i class="fas fa-cog mr-2"></i> Settings</a>
                        <a href="#" class="dropdown-item"><i class="fas fa-credit-card mr-2"></i> Billing</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Content -->
        <div class="content">
            <div class="page-header">
                <h1>Available Outfits</h1>
                <button class="btn btn-primary">Filter Options</button>
            </div>
            
            <div class="card-container">
                <!-- Item 1 -->
                <div class="card">
                    <div class="card-img">
                        <img src="images/karima.jpg" alt="Dress">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">Floral Kurta Surwal</h3>
                        <p class="card-text">Perfect for summer weddings and garden parties</p>
                        <div class="card-price">Rs.250/day</div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline"><i class="far fa-heart"></i> Wishlist</button>
                        <a href="form.php" class="btn btn-sm btn-primary">Rent Now</a>

                    </div>
                </div>
                
                <!-- Item 2 -->
                <div class="card">
                    <div class="card-img">
                        <img src="images/white.jpg" alt="Suit">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">Designer Lehenga</h3>
                        <p class="card-text">Elegant lehenga for formal occasions</p>
                        <div class="card-price">Rs.350/day</div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline"><i class="far fa-heart"></i> Wishlist</button>
                        <a href="form.php" class="btn btn-sm btn-primary">Rent Now</a>

                    </div>
                </div>
                
                <!-- Item 3 -->
                <div class="card">
                    <div class="card-img">
                        <img src="images/lehenga.jpg" alt="Jeans">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">Designer bridal Lehenga</h3>
                        <p class="card-text">Premium quality lehenga for bridal wear</p>
                        <div class="card-price">Rs.150/day</div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline"><i class="far fa-heart"></i> Wishlist</button>
                        <a href="form.php" class="btn btn-sm btn-primary">Rent Now</a>

                    </div>
                </div>
                
                <!-- Item 4 -->
                <div class="card">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Jacket">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">Leather Jacket</h3>
                        <p class="card-text">Stylish leather jacket for a bold look</p>
                        <div class="card-price">Rs.30/day</div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline"><i class="far fa-heart"></i> Wishlist</button>
                        <a href="form.php" class="btn btn-sm btn-primary">Rent Now</a>

                    </div>
                </div>
                
                <!-- Item 5 -->
                <div class="card">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1551232864-3f0890e580d9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Cocktail Dress">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">Cocktail Dress</h3>
                        <p class="card-text">Elegant dress for evening parties</p>
                        <div class="card-price">Rs.280/day</div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline"><i class="far fa-heart"></i> Wishlist</button>
                        <a href="form.php" class="btn btn-sm btn-primary">Rent Now</a>

                    </div>
                </div>
                
                <!-- Item 6 -->
                <div class="card">
                    <div class="card-img">
                        <img src="https://images.unsplash.com/photo-1520367445093-50dc08a59d9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Formal Suit">
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">Formal Suit</h3>
                        <p class="card-text">Complete suit for business meetings</p>
                        <div class="card-price">Rs.400/day</div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-outline"><i class="far fa-heart"></i> Wishlist</button>
                        <a href="form.php" class="btn btn-sm btn-primary">Rent Now</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle sidebar on mobile
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (window.innerWidth <= 992 && !sidebar.contains(event.target) && event.target !== menuToggle) {
                sidebar.classList.remove('active');
            }
        });
        
        // Simulate adding to wishlist
        const wishlistButtons = document.querySelectorAll('.btn-outline');
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-heart"></i> Added';
                this.style.color = 'white';
                this.style.backgroundColor = 'var(--danger-color)';
                this.style.borderColor = 'var(--danger-color)';
                
                // After 2 seconds, revert back
                setTimeout(() => {
                    this.innerHTML = '<i class="far fa-heart"></i> Wishlist';
                    this.style.color = 'var(--primary-color)';
                    this.style.backgroundColor = 'transparent';
                    this.style.borderColor = 'var(--primary-color)';
                }, 2000);
            });
        });
    </script>
</body>
</html>