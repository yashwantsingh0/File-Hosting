const uploadForm = document.getElementById('uploadForm');
const fileInput = document.getElementById('file');
const uploadButton = document.getElementById('uploadButton');
const progressContainer = document.getElementById('progressContainer');
const progressBar = document.getElementById('progressBar');
const progressText = document.getElementById('progressText');
const statusMessage = document.getElementById('statusMessage');
const fileNameDisplay = document.getElementById('fileNameDisplay');  // To display the selected file name

// Update file name on file selection
fileInput.addEventListener('change', function() {
  const fileName = fileInput.files[0] ? fileInput.files[0].name : '';
  fileNameDisplay.textContent = fileName ? `Selected file: ${fileName}` : '';
});

uploadForm.addEventListener('submit', function(event) {
  event.preventDefault();

  const file = fileInput.files[0];
  if (!file) {
    alert('Please select a file first.');
    return;
  }

  const formData = new FormData();
  formData.append('file', file);

  const xhr = new XMLHttpRequest();
  
  xhr.open('POST', 'upload.php', true);

  // Progress event to update the progress bar
  xhr.upload.onprogress = function(e) {
    if (e.lengthComputable) {
      const percentComplete = (e.loaded / e.total) * 100;
      progressBar.style.width = percentComplete + '%';
      progressText.textContent = Math.floor(percentComplete) + '%';
    }
  };

  // When upload starts
  xhr.onloadstart = function() {
    progressContainer.style.display = 'block';
    progressBar.style.width = '0%';
    progressText.textContent = '0%';
    uploadButton.disabled = true;
    fileInput.disabled = true;  // Disable file input during upload
    statusMessage.textContent = '';
  };

  // On completion of upload
  xhr.onload = function() {
    if (xhr.status === 200) {
      progressBar.style.width = '100%';
      progressText.textContent = '100%';
      statusMessage.innerHTML = "<span style='color: #00ffc8;'>Upload complete!</span>";
    } else {
      statusMessage.innerHTML = "<span style='color: #ff4b5c;'>Upload failed. Try again.</span>";
    }
    uploadButton.disabled = false;
    fileInput.disabled = false;  // Enable file input after upload
  };

  // On error during upload
  xhr.onerror = function() {
    statusMessage.innerHTML = "<span style='color: #ff4b5c;'>Upload error! Check the file size and try again.</span>";
    uploadButton.disabled = false;
    fileInput.disabled = false;
  };

  // On abort
  xhr.onabort = function() {
    statusMessage.innerHTML = "<span style='color: #ff4b5c;'>Upload canceled.</span>";
    uploadButton.disabled = false;
    fileInput.disabled = false;
  };

  // Send the form data to the server
  xhr.send(formData);
});
