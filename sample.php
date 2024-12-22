<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Popup Example with Caption</title>
  <style>
    /* General styling for the popup overlay */
    .popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    /* Popup container */
    .popup {
      width: 400px;
      background: #242526;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      color: #fff;
      font-family: Arial, sans-serif;
    }

    /* Header */
    .popup-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 18px;
      font-weight: bold;
    }

    .popup-header button {
      background: none;
      border: none;
      color: #bbb;
      font-size: 16px;
      cursor: pointer;
    }

    /* Content */
    .popup-content {
      margin: 15px 0;
    }

    /* Caption text area */
      /* Caption text area */
    .caption-area {
      margin-top: 10px;
    }

    textarea {
      width: 94%;
      height: 60px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background: #3a3b3c;
      color: #fff;
      font-size: 14px;
      resize: none;
    }

    textarea::placeholder {
      color: #bbb;
    }
    /* Add photos section */
    .add-photos {
      background: #3a3b3c;
      border: 1px dashed #ccc;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      color: #ccc;
      margin-top: 10px;
    }

    /* Buttons */
    .popup-footer {
      display: flex;
      justify-content: flex-end;
      margin-top: 15px;
    }

    .popup-footer button {
      background: #1877f2;
      border: none;
      padding: 10px 20px;
      color: #fff;
      border-radius: 6px;
      cursor: pointer;
    }

    .popup-footer button:hover {
      background: #145db2;
    }
  </style>
</head>
<body>
  <!-- Popup Overlay -->
<div class="popup-overlay" id="popup">
  <div class="popup">
    <div class="popup-header">
      <span>Create post</span>
      <button onclick="closePopup()">×</button>
    </div>
    <div class="popup-content">
      <p>What's on your mind, Nikki?</p>
      <textarea placeholder="Write a caption..."></textarea>
      <div class="add-photos">
        <p>Add photos/videos</p>
        <p>or drag and drop</p>
      </div>
    </div>
    <div class="popup-footer">
      <button onclick="closePopup()">Post</button>
    </div>
  </div>
</div>

  <script>
    function closePopup() {
      document.getElementById('popup').style.display = 'none';
    }
  </script>
</body>
</html>

/* Media Grid */
#media-preview {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* 2 items per row */
  gap: 10px;
  justify-content: center; /* Center items horizontally */
  align-items: center; /* Center items vertically */
  margin: 0 auto; /* Center the whole grid in the container */
  max-width: 300px; /* Adjust as needed */
}

/* Individual Media Items */
.media-item {
  width: 100%;
  height: 150px; /* Adjust height */
  overflow: hidden;
  border-radius: 8px;
  position: relative;
}

.media-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Overlay for Additional Images */
.media-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  color: #fff;
  font-size: 24px;
  font-weight: bold;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 8px;
}
x