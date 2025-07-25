<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us | Boutique</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fce4ec;
      font-family: Arial, sans-serif;
    }
    .text-pink {
      color: #e91e63;
    }
    .btn-pink {
      background-color: #e91e63;
      color: white;
      border: none;
    }
    .btn-pink:hover {
      background-color: #c2185b;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(233, 30, 99, 0.2);
      margin-top: 50px;
      max-width: 700px;
    }
    .message-box {
      border: 1px solid #ddd;
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 8px;
      background: #fff0f6;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2 class="text-center text-pink mb-4">Contact Us</h2>
    
    <form id="contactForm">
      <div class="mb-3">
        <label for="name" class="form-label">Your Name</label>
        <input type="text" class="form-control" id="name" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Your Email</label>
        <input type="email" class="form-control" id="email" required>
      </div>

      <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea class="form-control" id="message" rows="4" required></textarea>
      </div>

      <button type="submit" class="btn btn-pink">Send Message</button>
    </form>

    <div id="successMsg" class="mt-4 text-success fw-bold" style="display:none;">
      ✅ Message saved successfully!
    </div>

    <hr class="my-5">

    <h4 class="text-pink">📬 View Submitted Messages</h4>
    <div id="messageList"></div>
  </div>

  <!-- ✅ JavaScript -->
  <script>
    // Save Message
    document.getElementById('contactForm').addEventListener('submit', function (e) {
      e.preventDefault();

      let name = document.getElementById("name").value.trim();
      let email = document.getElementById("email").value.trim();
      let message = document.getElementById("message").value.trim();

      if (name && email && message) {
        let contact = {
          name: name,
          email: email,
          message: message,
          time: new Date().toLocaleString()
        };

        let messages = JSON.parse(localStorage.getItem("contactMessages")) || [];
        messages.push(contact);
        localStorage.setItem("contactMessages", JSON.stringify(messages));

        document.getElementById("contactForm").reset();
        document.getElementById("successMsg").style.display = "block";
        displayMessages();
      }
    });

    // View Messages
    function displayMessages() {
      let messageList = document.getElementById("messageList");
      messageList.innerHTML = "";

      let messages = JSON.parse(localStorage.getItem("contactMessages")) || [];

      if (messages.length === 0) {
        messageList.innerHTML = "<p>No messages yet.</p>";
        return;
      }

      messages.forEach(function (msg, index) {
        let messageDiv = document.createElement("div");
        messageDiv.className = "message-box";
        messageDiv.innerHTML = `
          <strong>${msg.name}</strong> (${msg.email})<br>
          <small>${msg.time}</small><br>
          <p>${msg.message}</p>
        `;
        messageList.appendChild(messageDiv);
      });
    }

    // Display messages on page load
    window.onload = displayMessages;
  </script>

</body>
</html>
