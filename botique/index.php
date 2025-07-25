<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Boutique♥</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Arial', sans-serif;
    }

    .bg-image {
      background-image: url('images/bg1.jpg');
      filter: brightness(0.6);
      height: 100%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }

    .content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      color: white;
      z-index: 1;
    }

    .btn-custom {
      width: 150px;
      margin: 10px;
      transition: all 0.3s ease;
    }
    
    .btn-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .feature-icon {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: #ff6b6b;
    }

    .testimonial-card {
      border-radius: 10px;
      padding: 20px;
      margin: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .testimonial-img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #fff;
      box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }

    .rating {
      color: #ffc107;
      margin: 10px 0;
    }

    section {
      padding: 80px 0;
    }

    .section-title {
      position: relative;
      margin-bottom: 60px;
    }

    .section-title:after {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: linear-gradient(to right, #ff6b6b, #ff8e8e);
    }

    /* Navbar styles */
    .navbar {
      background-color: rgba(0, 0, 0, 0.7);
      padding: 15px 0;
      transition: all 0.3s ease;
    }

    .navbar.scrolled {
      background-color: #000;
      padding: 10px 0;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.8rem;
      color: #fff !important;
    }

    .navbar-brand span {
      color: #ff6b6b;
    }

    .nav-link {
      color: #fff !important;
      margin: 0 10px;
      font-weight: 500;
      position: relative;
    }

    .nav-link:after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background-color: #ff6b6b;
      transition: width 0.3s ease;
    }

    .nav-link:hover:after {
      width: 100%;
    }

    .navbar-toggler {
      border: none;
      color: #fff;
    }

    .navbar-toggler:focus {
      outline: none;
      box-shadow: none;
    }
  </style>
</head>
<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">Boutique<span>♥</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="form.php">Shop</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="collection.php">Collections</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.php">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-light ms-2" href="register.php">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="bg-image"></div>

  <div class="content">
    <h1 class="fw-bold display-4">Welcome to Boutique♥</h1>
    <p class="lead fs-4">Rent Your Style. Save Your Wallet.</p>
    <!-- Centered buttons -->
    <a href="login.php" class="btn btn-light btn-custom">Login</a>
    <a href="register.php" class="btn btn-outline-light btn-custom">Register</a>
  </div>

  <!-- Why Choose Us Section -->
  <section class="why-choose-us py-5 bg-light">
    <div class="container">
      <h2 class="text-center section-title">Why Choose Boutique♥?</h2>
      <div class="row text-center">
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="feature-icon">
            <i class="fas fa-tshirt"></i>
          </div>
          <h4 class="mb-3">Stylish Collection</h4>
          <p class="px-3">Trendy dresses for every size, taste, and event. We update our collection weekly to keep up with the latest fashion trends.</p>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="feature-icon">
            <i class="fas fa-tags"></i>
          </div>
          <h4 class="mb-3">Affordable Prices</h4>
          <p class="px-3">Rent premium clothes at 10-20% of retail price. Perfect for special occasions without the commitment.</p>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="feature-icon">
            <i class="fas fa-truck"></i>
          </div>
          <h4 class="mb-3">Quick Delivery</h4>
          <p class="px-3">Next-day delivery available. Free pickup and returns. We handle the dry cleaning!</p>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="feature-icon">
            <i class="fas fa-star"></i>
          </div>
          <h4 class="mb-3">Premium Quality</h4>
          <p class="px-3">All items are professionally cleaned and inspected after each rental to ensure top condition.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials py-5">
    <div class="container">
      <h2 class="text-center section-title">What Our Customers Say</h2>
      <div class="row">
        <!-- Testimonial 1 -->
        <div class="col-md-4 mb-4">
          <div class="testimonial-card bg-white text-center">
            <img src="images/profile-pic.png" alt="Customer" class="testimonial-img mb-3">
            <h5>Roshani Sharma</h5>
            <div class="rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <p class="mb-0">"I rented a dress for my best friend's wedding and received so many compliments! The quality was amazing and the process was so easy."</p>
          </div>
        </div>
        
        <!-- Testimonial 2 -->
        <div class="col-md-4 mb-4">
          <div class="testimonial-card bg-white text-center">
            <img src="images/renusha.jpg" alt="Customer" class="testimonial-img mb-3">
            <h5>Renusha Thapa</h5>
            <div class="rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <p class="mb-0">"As a frequent traveler, Boutique♥ has saved me so much luggage space. I can wear different outfits for each event without carrying them all!"</p>
          </div>
        </div>
        
        <!-- Testimonial 3 -->
        <div class="col-md-4 mb-4">
          <div class="testimonial-card bg-white text-center">
            <img src="images/karima.jpg" alt="Customer" class="testimonial-img mb-3">
            <h5>Karima Miya</h5>
            <div class="rating">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <p class="mb-0">"The tuxedo I rented for my brother's wedding was perfect. Great fit and arrived right on time. Will definitely use Boutique♥ again!"</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include('./partials/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  </script>
</body>
</html>