<?php
// Define the directory where uploaded files are stored
$directory = 'files/'; // Path relative to this file

// Get the list of files in the directory
$files = array_diff(scandir($directory), array('..', '.')); // Remove . and .. entries

// Function to format file size
function formatSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' B';
    }
}

// Search functionality
$searchTerm = isset($_GET['search']) ? strtolower($_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hosted Files</title>
  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

body {
  font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(270deg, #0f2027, #203a43, #2c5364);
  background-size: 600% 600%;
  animation: gradientAnimation 12s ease infinite;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
  overflow-x: hidden;
  color: #eee;
}

.container {
  background: rgba(17, 17, 17, 0.8);
  backdrop-filter: blur(12px);
  border-radius: 20px;
  padding: 30px 20px;
  width: 100%;
  max-width: 800px;
  text-align: center;
  box-shadow: 0 8px 32px rgba(0,0,0,0.6);
  animation: fadeIn 1s ease;
}

header h1 {
  font-size: 2.4rem;
  margin-bottom: 25px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  color: #f0f0f0;
}

.search-bar {
  margin-bottom: 20px;
}

.search-bar input {
  padding: 10px;
  width: 80%;
  border-radius: 8px;
  border: 1px solid #ccc;
  background-color: #333;
  color: #eee;
  font-size: 1em;
}

.files-list ul {
  list-style: none;
  margin-top: 20px;
}

.files-list li {
  background: rgba(255, 255, 255, 0.05);
  margin: 15px 0;
  padding: 18px 20px;
  border-radius: 14px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  transition: transform 0.3s, background 0.3s;
}

.files-list li:hover {
  transform: translateY(-4px);
  background: rgba(255, 255, 255, 0.08);
}

.file-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  gap: 10px;
  flex-wrap: wrap;
}

.file-details {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  min-width: 200px;
}

.file-details a {
  color: #4aa8ff;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.1em;
  word-break: break-all;
}

.file-details a:hover {
  color: #82cfff;
}

.meta {
  font-size: 0.9em;
  color: #bbb;
  margin-top: 5px;
}

.download-btn {
  margin-top: 10px;
  background: #4aa8ff;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  font-weight: 600;
  color: #111;
  text-decoration: none;
  transition: background 0.3s;
  align-self: flex-end;
}

.download-btn:hover {
  background: #82cfff;
}

footer {
  margin-top: 30px;
  font-size: 0.9em;
  color: #888;
}

footer a {
  color: #4aa8ff;
  text-decoration: none;
}

footer a:hover {
  text-decoration: underline;
}

  </style>
</head>
<body>

<div class="container">
  <header>
    <h1>📁 Hosted Files</h1>
  </header>

  <div class="search-bar">
    <form method="get">
      <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search files..." />
    </form>
  </div>

  <main>
    <section class="files-list">
      <ul>
        <?php
        if (empty($files)) {
            echo '<li>No files uploaded yet.</li>';
        } else {
            foreach ($files as $file) {
                if ($searchTerm && stripos($file, $searchTerm) === false) {
                    continue; // Skip files that don't match the search term
                }

                $filePath = $directory . $file;
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $icon = '📄'; // Default icon

                $preview = ''; // Default preview for non-image/PDF files

                // Handle images for preview
                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $icon = '🖼️';
                    $preview = '<img src="' . htmlspecialchars($filePath) . '" alt="' . htmlspecialchars($file) . '" style="max-width: 100px; max-height: 100px; border-radius: 8px; object-fit: cover;">';
                } 
                // Handle PDFs for inline view
                elseif ($extension == 'pdf') {
                    $icon = '📄';
                    $preview = '<iframe src="' . htmlspecialchars($filePath) . '" style="width: 100%; height: 200px;" frameborder="0"></iframe>';
                } 
                // Handle archives
                elseif (in_array($extension, ['zip', 'rar', 'tar', 'gz'])) {
                    $icon = '🗜️';
                } 
                // Handle videos and audio files
                elseif (in_array($extension, ['mp4', 'mkv', 'webm'])) {
                    $icon = '🎥';
                } elseif (in_array($extension, ['mp3', 'wav'])) {
                    $icon = '🎵';
                }

                $size = formatSize(filesize($filePath));
                $uploadDate = date("d M Y, H:i", filemtime($filePath));

                echo '<li>
                        <div class="file-info">
                          <div class="file-icon">' . $icon . '</div>
                          <div class="file-details">
                            <a href="' . htmlspecialchars($filePath) . '" target="_blank">' . htmlspecialchars($file) . '</a>
                            <div class="meta">' . $size . ' | ' . $uploadDate . '</div>
                          </div>
                          <div class="file-preview">' . $preview . '</div>
                        </div>
                        <a class="download-btn" href="' . htmlspecialchars($filePath) . '" download>Download</a>
                      </li>';
            }
        }
        ?>
      </ul>
    </section>
  </main>

  <footer>
    <p>© 2021-2025 | Designed by <a href="https://twitter.com/yashwantsingh_1" target="_blank">@yashwantsingh_1</a></p>
  </footer>

</div>

</body>
</html>

