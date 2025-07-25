<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clothes Renting System</title>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6a5acd;
            --secondary: #9370db;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        h1 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .form-section h2 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.4rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .required:after {
            content: " *";
            color: var(--danger);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(106, 90, 205, 0.2);
        }
        
        .upload-area {
            border: 2px dashed #ccc;
            padding: 30px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #f9f9f9;
            margin-bottom: 15px;
        }
        
        .upload-area:hover {
            border-color: var(--primary);
            background-color: #f0f0f0;
        }
        
        .upload-area i {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .upload-area p {
            margin-bottom: 5px;
            color: #666;
        }
        
        .file-input {
            display: none;
        }
        
        .preview-container {
            margin-top: 15px;
            text-align: center;
        }
        
        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid #ddd;
            display: none;
        }
        
        .size-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .size-option {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .size-option:hover {
            border-color: var(--primary);
        }
        
        .size-option.selected {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            margin-top: 30px;
        }
        
        .btn:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }
        
        .qr-container {
            text-align: center;
            margin-top: 40px;
            padding: 30px;
            border: 1px dashed var(--primary);
            border-radius: 10px;
            background-color: #f9f9f9;
            display: none;
        }
        
        #qrcode {
            display: inline-block;
            margin: 20px 0;
            padding: 15px;
            background: white;
            border: 1px solid #eee;
            border-radius: 5px;
        }
        
        .confirmation {
            display: none;
            text-align: center;
            padding: 30px;
            background-color: #e8f5e9;
            border-radius: 10px;
            margin-top: 30px;
        }
        
        .confirmation i {
            font-size: 60px;
            color: var(--success);
            margin-bottom: 20px;
        }
        
        .esewa-qr-container {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 8px;
        }
        
        .esewa-qr-container img {
            max-width: 200px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .esewa-qr-container p {
            color: #555;
            margin-bottom: 10px;
        }
        
        .error-message {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: 5px;
            display: none;
        }
        
        .input-error {
            border-color: var(--danger) !important;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 15px;
            }
            
            .size-options {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-tshirt"></i> Clothes Renting System</h1>
        
        <form id="rentalForm" enctype="multipart/form-data">
            <!-- Personal Information Section -->
            <div class="form-section">
                <h2><i class="fas fa-user"></i> Personal Information</h2>
                
                <div class="form-group">
                    <label for="username" class="required">Full Name</label>
                    <input type="text" id="username" name="username" required>
                    <div class="error-message" id="nameError">Please enter a valid name</div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="required">Email Address</label>
                    <input type="email" id="email" name="email" required>
                    <div class="error-message" id="emailError">Please enter a valid email address</div>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="required">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required pattern="[0-9]{10}">
                    <div class="error-message" id="phoneError">Please enter a valid 10-digit phone number</div>
                </div>
                
                <div class="form-group">
                    <label for="address" class="required">Delivery Address</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                    <div class="error-message" id="addressError">Please enter your delivery address</div>
                </div>
            </div>
            
            <!-- Clothing Details Section -->
            <div class="form-section">
                <h2><i class="fas fa-tshirt"></i> Clothing Details</h2>
                
                <div class="form-group">
                    <label class="required">Select Size</label>
                    <div class="size-options">
                        <div class="size-option" data-size="XS">XS</div>
                        <div class="size-option" data-size="S">S</div>
                        <div class="size-option" data-size="M">M</div>
                        <div class="size-option" data-size="L">L</div>
                        <div class="size-option" data-size="XL">XL</div>
                        <div class="size-option" data-size="XXL">XXL</div>
                    </div>
                    <input type="hidden" id="selectedSize" name="size" required>
                    <div class="error-message" id="sizeError">Please select a size</div>
                </div>
                
                <div class="form-group">
                    <label for="rentalDays">Rental Duration (Days)</label>
                    <select id="rentalDays" name="rentalDays">
                        <option value="3">3 Days</option>
                        <option value="7" selected>7 Days</option>
                        <option value="14">14 Days</option>
                        <option value="30">30 Days</option>
                    </select>
                </div>
            </div>
            
            <!-- Document Upload Section -->
            <div class="form-section">
                <h2><i class="fas fa-file-upload"></i> Document Uploads</h2>
                
                <div class="form-group">
                    <label class="required">Citizenship Proof</label>
                    <div class="upload-area" id="citizenshipUpload">
                        <i class="fas fa-id-card"></i>
                        <p>Click to upload Citizenship ID/Passport</p>
                        <p>Accepted formats: JPG, PNG, PDF (max 5MB)</p>
                        <img id="citizenshipPreview" class="preview-image" alt="Citizenship Preview">
                    </div>
                    <input type="file" id="citizenshipFile" class="file-input" name="citizenshipFile" accept="image/*,.pdf" required>
                    <div class="error-message" id="citizenshipError">Please upload your citizenship document</div>
                </div>
                
                <div class="form-group">
                    <label class="required">Payment Screenshot (eSewa)</label>
                    
                    <div class="esewa-qr-container">
                        <h3>Scan to Pay with eSewa</h3>
                        <img src="images/esewa.PNG" alt="eSewa QR Code"> 
                        <p>Scan the QR code to pay the amount</p>
                        <p>Merchant: Boutique</p>
                    </div>
                    
                    <div class="upload-area" id="paymentUpload">
                        <i class="fas fa-rupee-sign"></i>
                        <p>Click to upload Payment Screenshot</p>
                        <p>Accepted formats: JPG, PNG (max 5MB)</p>
                        <img id="paymentPreview" class="preview-image" alt="Payment Preview">
                    </div>
                    <input type="file" id="paymentFile" class="file-input" name="paymentFile" accept="image/*" required>
                    <div class="error-message" id="paymentError">Please upload your payment screenshot</div>
                </div>
            </div>
            
            <div class="form-group">
                <input type="checkbox" id="termsAgreement" name="termsAgreement" required>
                <label for="termsAgreement">I agree to the <a href="#" style="color: var(--primary);">Terms and Conditions</a> of the rental service</label>
                <div class="error-message" id="termsError">You must agree to the terms and conditions</div>
            </div>
            
            <button type="button" class="btn" id="submitBtn">
                <i class="fas fa-qrcode"></i> Generate Rental QR Code
            </button>
        </form>
        
        <!-- QR Code Display Section -->
        <div class="qr-container" id="qrContainer">
            <h2><i class="fas fa-qrcode"></i> Your Rental QR Code</h2>
            <p>Show this code when picking up and returning your rental</p>
            <div id="qrcode"></div>
            <p><strong>Rental ID:</strong> <span id="rentalId"></span></p>
            <p><strong>Pickup Date:</strong> <span id="pickupDate"></span></p>
            <p><strong>Return Date:</strong> <span id="returnDate"></span></p>
        </div>
        
        <!-- Confirmation Section -->
        <div class="confirmation" id="confirmation">
            <i class="fas fa-check-circle"></i>
            <h2>Rental Confirmed!</h2>
            <p>Your clothing rental has been successfully registered.</p>
            <p>An email confirmation has been sent to <span id="confirmEmail"></span></p>
            <p>Please check your email for pickup instructions.</p>
        </div>
    </div>

    <script>
        // Global variables
        let citizenshipFile = null;
        let paymentFile = null;
        let selectedSize = null;
        
        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Input validation
            document.getElementById('username').addEventListener('blur', validateName);
            document.getElementById('email').addEventListener('blur', validateEmail);
            document.getElementById('phone').addEventListener('blur', validatePhone);
            document.getElementById('address').addEventListener('blur', validateAddress);
            
            // Size selection
            document.querySelectorAll('.size-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.size-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    this.classList.add('selected');
                    selectedSize = this.getAttribute('data-size');
                    document.getElementById('selectedSize').value = selectedSize;
                    hideError('sizeError');
                });
            });
            
            // Citizenship upload
            document.getElementById('citizenshipUpload').addEventListener('click', function() {
                document.getElementById('citizenshipFile').click();
            });
            
            document.getElementById('citizenshipFile').addEventListener('change', function(e) {
                handleFileUpload(e, 'citizenshipPreview', file => {
                    citizenshipFile = file;
                    hideError('citizenshipError');
                });
            });
            
            // Payment upload
            document.getElementById('paymentUpload').addEventListener('click', function() {
                document.getElementById('paymentFile').click();
            });
            
            document.getElementById('paymentFile').addEventListener('change', function(e) {
                handleFileUpload(e, 'paymentPreview', file => {
                    paymentFile = file;
                    hideError('paymentError');
                });
            });
            
            // Form submission
            document.getElementById('submitBtn').addEventListener('click', validateAndSubmit);
        });
        
        // Validation functions
        function validateName() {
            const nameInput = document.getElementById('username');
            const name = nameInput.value.trim();
            const nameRegex = /^[a-zA-Z\s]{2,50}$/;
            
            if (!nameRegex.test(name)) {
                showError('nameError', 'Please enter a valid name (2-50 letters and spaces only)');
                nameInput.classList.add('input-error');
                return false;
            } else {
                hideError('nameError');
                nameInput.classList.remove('input-error');
                return true;
            }
        }
        
        function validateEmail() {
            const emailInput = document.getElementById('email');
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                showError('emailError', 'Please enter a valid email address');
                emailInput.classList.add('input-error');
                return false;
            } else {
                hideError('emailError');
                emailInput.classList.remove('input-error');
                return true;
            }
        }
        
        function validatePhone() {
            const phoneInput = document.getElementById('phone');
            const phone = phoneInput.value.trim();
            const phoneRegex = /^[0-9]{10}$/;
            
            if (!phoneRegex.test(phone)) {
                showError('phoneError', 'Please enter a valid 10-digit phone number');
                phoneInput.classList.add('input-error');
                return false;
            } else {
                hideError('phoneError');
                phoneInput.classList.remove('input-error');
                return true;
            }
        }
        
        function validateAddress() {
            const addressInput = document.getElementById('address');
            const address = addressInput.value.trim();
            
            if (address.length < 10) {
                showError('addressError', 'Please enter a complete delivery address (at least 10 characters)');
                addressInput.classList.add('input-error');
                return false;
            } else {
                hideError('addressError');
                addressInput.classList.remove('input-error');
                return true;
            }
        }
        
        function validateTerms() {
            const termsChecked = document.getElementById('termsAgreement').checked;
            
            if (!termsChecked) {
                showError('termsError', 'You must agree to the terms and conditions');
                return false;
            } else {
                hideError('termsError');
                return true;
            }
        }
        
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
        
        function hideError(elementId) {
            const errorElement = document.getElementById(elementId);
            errorElement.style.display = 'none';
        }
        
        // Handle file uploads
        function handleFileUpload(event, previewId, callback) {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);
            
            if (!file) return;
            
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB limit');
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png'];
            if (previewId === 'citizenshipPreview') {
                validTypes.push('application/pdf');
            }
            
            if (!validTypes.includes(file.type)) {
                alert('Only JPG, PNG files are allowed' + 
                      (previewId === 'citizenshipPreview' ? ' or PDF for citizenship' : ''));
                return;
            }
            
            // Show preview for images
            if (file.type.includes('image')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
            
            callback(file);
        }
        
        // Validate form and submit
        function validateAndSubmit() {
            // Validate all fields
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isPhoneValid = validatePhone();
            const isAddressValid = validateAddress();
            const isTermsValid = validateTerms();
            
            // Validate size selection
            if (!selectedSize) {
                showError('sizeError', 'Please select a size');
            } else {
                hideError('sizeError');
            }
            
            // Validate citizenship upload
            if (!citizenshipFile) {
                showError('citizenshipError', 'Please upload your citizenship document');
            } else {
                hideError('citizenshipError');
            }
            
            // Validate payment upload
            if (!paymentFile) {
                showError('paymentError', 'Please upload your payment screenshot');
            } else {
                hideError('paymentError');
            }
            
            // Check if all validations passed
            if (isNameValid && isEmailValid && isPhoneValid && isAddressValid && 
                selectedSize && citizenshipFile && paymentFile && isTermsValid) {
                submitFormData();
            } else {
                // Scroll to the first error
                const firstError = document.querySelector('.error-message[style="display: block;"]');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        }
        
        // Submit form data to server
        function submitFormData() {
            const form = document.getElementById('rentalForm');
            const formData = new FormData(form);
            
            // Add the selected size to the form data
            formData.append('size', selectedSize);
            
            fetch('process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showRentalQR(data.rentalId, data.pickupDate, data.returnDate, data.email);
                } else {
                    alert("Error: " + (data.error || "Failed to process rental"));
                }
            })
            .catch(error => {
                alert("Error submitting form: " + error.message);
                console.error('Error:', error);
            });
        }
        
        // Show rental QR code
        function showRentalQR(rentalId, pickupDate, returnDate, email) {
            document.getElementById('rentalId').textContent = rentalId;
            document.getElementById('pickupDate').textContent = new Date(pickupDate).toLocaleDateString();
            document.getElementById('returnDate').textContent = new Date(returnDate).toLocaleDateString();
            document.getElementById('confirmEmail').textContent = email;
            
            // Prepare data for QR code
            const rentalData = {
                rentalId: rentalId,
                pickupDate: pickupDate,
                returnDate: returnDate,
                timestamp: new Date().toISOString()
            };
            
            // Clear previous QR code
            document.getElementById('qrcode').innerHTML = '';
            
            // Generate new QR code
            new QRCode(document.getElementById('qrcode'), {
                text: JSON.stringify(rentalData),
                width: 200,
                height: 200,
                colorDark: "#6a5acd",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            
            // Show QR container and hide form
            document.getElementById('qrContainer').style.display = 'block';
            document.getElementById('rentalForm').style.display = 'none';
            document.getElementById('submitBtn').style.display = 'none';
            
            // Scroll to QR code
            document.getElementById('qrContainer').scrollIntoView({
                behavior: 'smooth'
            });
            
            // Show confirmation after delay
            setTimeout(() => {
                document.getElementById('qrContainer').style.display = 'none';
                document.getElementById('confirmation').style.display = 'block';
                
                // Scroll to confirmation
                document.getElementById('confirmation').scrollIntoView({
                    behavior: 'smooth'
                });
            }, 5000);
        }
    </script>
</body>
</html>