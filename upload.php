<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the file was uploaded without errors
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $uploadedFile = $_FILES['file'];
        $uploadDirectory = '/var/www/html/shared/files/';

        // Sanitize the file name to prevent directory traversal attacks
        $fileName = basename($uploadedFile['name']);
        $fileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $fileName); // Allow only alphanumeric, dash, underscore, and period
        $filePath = $uploadDirectory . $fileName;

        // Check if the file already exists and avoid overwriting it
        if (file_exists($filePath)) {
            // Create a unique name if the file exists
            $fileInfo = pathinfo($fileName);
            $newFileName = $fileInfo['filename'] . '-' . time() . '.' . $fileInfo['extension'];
            $filePath = $uploadDirectory . $newFileName;
        }

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
            echo "File has been uploaded successfully. File name: " . htmlspecialchars($newFileName ?? $fileName);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file uploaded or there was an error.";
    }
} else {
    // Debugging line for checking request method
    echo "Invalid request method. Expected POST.";
}
?>

