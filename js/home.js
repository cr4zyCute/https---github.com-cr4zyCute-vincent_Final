 function openPopup() {
    document.getElementById('post-popup').style.display = 'flex';
  }

  function closePopup() {
    document.getElementById('post-popup').style.display = 'none';
  }



  let allFiles = []; // Global array to store all selected files

  function triggerFileUpload() {
    document.getElementById('media-upload').click();
  }

  function previewFiles(event) {
    const newFiles = Array.from(event.target.files); // Get newly selected files

    // Append new files to the global file list
    allFiles = [...allFiles, ...newFiles];

    // Update the preview display
    displayFiles(allFiles);

    // Show the "Cancel Post" button if there are files
    toggleDeleteButton(allFiles.length > 0);
  }

 function displayFiles(files) {
    const previewContainer = document.getElementById('media-preview');
    previewContainer.innerHTML = ''; // Clear previous previews

    files.forEach((file, index) => {
      if (index >= 4) return; // Only show the first 4 items initially

      const fileType = file.type.split('/')[0]; 
      const previewElement = document.createElement('div');
      previewElement.className = 'media-item';

      if (fileType === 'image') {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.alt = file.name;
        previewElement.appendChild(img);
      } else if (fileType === 'video') {
        const video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.controls = true;
        video.alt = file.name;
        previewElement.appendChild(video);
      }
      previewContainer.appendChild(previewElement);
    });

    if (files.length > 4) {
      const overlayElement = document.createElement('div');
      overlayElement.className = 'media-overlay';
      overlayElement.textContent = `+${files.length - 4}`;
      const fourthMediaItem = previewContainer.children[3];
      fourthMediaItem.appendChild(overlayElement);
    }
  }

  function clearFiles() {
    // Clear the global file array
    allFiles = [];

    // Clear the preview display
    const previewContainer = document.getElementById('media-preview');
    previewContainer.innerHTML = '';

    // Clear the file input (reset its value)
    document.getElementById('media-upload').value = '';

    // Hide the "Cancel Post" button
    toggleDeleteButton(false);
  }

  function toggleDeleteButton(show) {
    const deleteButton = document.getElementById('delete-post');
    deleteButton.style.display = show ? 'block' : 'none';
  }

function previewFiles(event) {
    const previewContainer = document.getElementById('media-preview');
    previewContainer.innerHTML = '';
    const files = event.target.files;

    for (let file of files) {
        const fileReader = new FileReader();
        fileReader.onload = (e) => {
            const media = document.createElement(file.type.startsWith('image/') ? 'img' : 'video');
            media.src = e.target.result;
            media.controls = file.type.startsWith('video/');
            media.classList.add('preview-media');
            previewContainer.appendChild(media);
        };
        fileReader.readAsDataURL(file);
    }
}
