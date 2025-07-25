<?php
$conn = new mysqli("localhost", "root", "", "botique");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$msg = "";
$msg_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $desc = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $rating = floatval($_POST["rating"]);

    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Dress name is required";
    }
    
    if (empty($desc)) {
        $errors[] = "Description is required";
    }
    
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }
    
    if ($rating < 1 || $rating > 5) {
        $errors[] = "Rating must be between 1 and 5";
    }
    
    // Image validation
    $image = "";
    if (isset($_FILES["image"])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES["image"]["type"];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPG, PNG, GIF, and WEBP images are allowed";
        } elseif ($_FILES["image"]["size"] > 5 * 1024 * 1024) { // 5MB limit
            $errors[] = "Image size must be less than 5MB";
        } else {
            $image = basename($_FILES["image"]["name"]);
            $temp = $_FILES["image"]["tmp_name"];
            
            $target_dir = "images/";
            $target_file = $target_dir . $image;
            
            // Check if the directory exists, if not create it
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            // Move the uploaded file to the target directory
            if (!move_uploaded_file($temp, $target_file)) {
                $errors[] = "Failed to upload image. Please try again.";
            }
            
        }
    } else {
        $errors[] = "Image is required";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO collection (name, description, image, price, rating) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdi", $name, $desc, $image, $price, $rating);

        if ($stmt->execute()) {
            $msg = "Collection added successfully!";
            $msg_class = "alert-success";
            // Clear form on success
            $_POST = array();
        } else {
            $msg = "Failed to add collection: " . $stmt->error;
            $msg_class = "alert-danger";
        }

        $stmt->close();
    } else {
        $msg = implode("<br>", $errors);
        $msg_class = "alert-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Collection - Boutique Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b9a;
            --secondary-color: #ffd6e5;
            --dark-pink: #e91e63;
            --light-pink: #fff5f8;
        }
        
        body {
            background-color: var(--light-pink);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 700px;
            margin-top: 30px;
            margin-bottom: 50px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(233, 30, 99, 0.1);
            padding: 30px;
        }
        
        .form-header {
            color: var(--dark-pink);
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .form-header h2 {
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .form-header::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background: var(--primary-color);
            margin: 15px auto;
            border-radius: 3px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #f0c0d2;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 154, 0.25);
        }
        
        .btn-submit {
            background-color: var(--dark-pink);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
            text-transform: uppercase;
        }
        
        .btn-submit:hover {
            background-color: #c2185b;
            transform: translateY(-2px);
        }
        
        .rating-display {
            color: #ffc107;
            font-size: 1.2rem;
        }
        
        .preview-image {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 8px;
            display: none;
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }
        
        .required-field::after {
            content: '*';
            color: var(--dark-pink);
            margin-left: 4px;
        }
        
        .price-input {
            position: relative;
        }
        
        .price-input::before {
            content: 'Rs.';
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            z-index: 1;
        }
        
        .price-input input {
            padding-left: 40px !important;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-header">
        <h2><i class="fas fa-plus-circle me-2"></i> Add New Collection Item</h2>
    </div>
    
    <?php if ($msg): ?>
    <div class="alert <?php echo $msg_class; ?> alert-dismissible fade show">
        <?php echo $msg; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" id="collectionForm" novalidate>
        <div class="mb-4">
            <label class="form-label required-field">Dress Name</label>
            <input type="text" name="name" class="form-control" required
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                   minlength="3" maxlength="100">
            <div class="invalid-feedback">Please enter a valid dress name (3-100 characters)</div>
        </div>
        
        <div class="mb-4">
            <label class="form-label required-field">Description</label>
            <textarea name="description" class="form-control" required
                      rows="4" minlength="10" maxlength="500"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            <div class="invalid-feedback">Please enter a description (10-500 characters)</div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <label class="form-label required-field">Price (Rs)</label>
                <div class="price-input">
                    <input type="number" name="price" class="form-control" required
                           step="0.01" min="0.01" max="99999"
                           value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                </div>
                <div class="invalid-feedback">Please enter a valid price (greater than 0)</div>
            </div>
            
            <div class="col-md-6 mb-4">
                <label class="form-label required-field">Rating (1–5)</label>
                <input type="number" name="rating" class="form-control" required
                       step="0.1" min="1" max="5"
                       value="<?php echo htmlspecialchars($_POST['rating'] ?? '5'); ?>">
                <div class="invalid-feedback">Please enter a rating between 1 and 5</div>
                <div class="rating-display mt-2">
                    <span id="starDisplay"></span>
                    <small class="text-muted ms-2" id="ratingText"></small>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="form-label required-field">Upload Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" required
                   id="imageUpload">
            <div class="invalid-feedback">Please select a valid image file (JPG, PNG, GIF, WEBP, max 5MB)</div>
            <img id="imagePreview" class="preview-image" alt="Preview">
        </div>
        
        <button type="submit" class="btn btn-submit w-100 mt-3">
            <i class="fas fa-save me-2"></i> Add to Collection
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Form validation
    (function() {
        'use strict';
        
        const form = document.getElementById('collectionForm');
        const ratingInput = document.querySelector('input[name="rating"]');
        const starDisplay = document.getElementById('starDisplay');
        const ratingText = document.getElementById('ratingText');
        const imageUpload = document.getElementById('imageUpload');
        const imagePreview = document.getElementById('imagePreview');
        
        // Rating display
        function updateRatingDisplay() {
            const rating = parseFloat(ratingInput.value) || 0;
            const fullStars = Math.floor(rating);
            const halfStar = rating % 1 >= 0.5 ? 1 : 0;
            const emptyStars = 5 - fullStars - halfStar;
            
            starDisplay.innerHTML = 
                '<i class="fas fa-star"></i>'.repeat(fullStars) +
                (halfStar ? '<i class="fas fa-star-half-alt"></i>' : '') +
                '<i class="far fa-star"></i>'.repeat(emptyStars);
            
            ratingText.textContent = rating.toFixed(1);
        }
        
        // Image preview
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.style.display = 'block';
                    imagePreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Initialize rating display
        updateRatingDisplay();
        ratingInput.addEventListener('input', updateRatingDisplay);
        
        // Form validation
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
        
        // Price input validation
        const priceInput = document.querySelector('input[name="price"]');
        priceInput.addEventListener('blur', function() {
            if (this.value && parseFloat(this.value) <= 0) {
                this.setCustomValidity('Price must be greater than 0');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Rating input validation
        ratingInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (isNaN(value) || value < 1 || value > 5) {
                this.setCustomValidity('Rating must be between 1 and 5');
            } else {
                this.setCustomValidity('');
            }
        });
    })();
</script>
</body>
</html>