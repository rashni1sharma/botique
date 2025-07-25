<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Boutique Navbar</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .navbar {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    .navbar.scrolled {
      background-color: #212529 !important;
      padding-top: 5px;
      padding-bottom: 5px;
    }
    .navbar-brand {
      font-size: 1.5rem;
    }
    .nav-link {
      font-weight: 500;
      padding: 0.5rem 1rem;
    }
    .btn-register {
      transition: all 0.3s ease;
    }
    .btn-register:hover {
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">
        Boutique<span class="text-danger">♥</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">
              <i class="fas fa-home me-1"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="form.php">
              <i class="fas fa-shopping-bag me-1"></i> Shop
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="collection.php">
              <i class="fas fa-layer-group me-1"></i> Collections
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.php">
              <i class="fas fa-info-circle me-1"></i> About
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">
              <i class="fas fa-envelope me-1"></i> Contact
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">
              <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="nav-link btn btn-primary px-3 btn-register" href="register.php">
              <i class="fas fa-user-plus me-1"></i> Register
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Placeholder content to make navbar scrollable -->
    <div class="container mt-5 pt-5">

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
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