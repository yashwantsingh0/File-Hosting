# File Hosting Webpage

A user-friendly file hosting platform for Apache or other web servers, featuring an intuitive interface for uploading and viewing files.

---

## Overview

The webpage consists of:

- **Header:** Displays the title *File Hosting* with a folder emoji for a clean, welcoming appearance.

- **Main Content:**
  - **Upload Section:** Users upload files via a form with a custom styled button (`📂 Choose File`). The upload form posts to `upload.php`, shows the selected file name, and displays a progress bar during uploads for real-time feedback.
  - **View Hosted Files Section:** Provides a link to browse uploaded files in a new tab.

The design focuses on simplicity and usability with visual feedback for a smooth user experience.

---

## How to Install on Fresh Kali Linux

### 🔧 Step 1: Install Apache & PHP

```bash
sudo apt update
sudo apt install apache2 php libapache2-mod-php default-jdk unzip -y 
sudo systemctl start apache2
sudo systemctl enable apache2
```

📁 STEP 2: Setup Your Project Directory

Let’s assume your files are in a folder like ~/Downloads/my_file_hosting_site.

Copy them to the Apache root:
```
sudo rm -rf /var/www/html/*
sudo cp -r ~/Downloads/my_file_hosting_site/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html
```
Check your structure is correct:

tree /var/www/html

You should see:
```
/var/www/html
├── index.html
├── upload.php
├── shared/
│   ├── index.php
│   └── files/
├── index_files/
│   ├── style.css
│   └── script.js
```
📂 STEP 3: Create the Upload Directory

Your PHP uploads go to /shared/files/, so ensure it exists:
```
sudo mkdir -p /var/www/html/shared/files
sudo chown -R www-data:www-data /var/www/html/shared/files
sudo chmod -R 755 /var/www/html/shared/files
```
⚙️ STEP 4: Allow File Uploads (if needed)

Edit the PHP config to allow large files:
```
sudo nano /etc/php/*/apache2/php.ini
```
Change these values:
```
file_uploads = On
upload_max_filesize = 100M
post_max_size = 100M
```
Restart Apache:
```
sudo systemctl restart apache2
```
🌐 STEP 5: Test in Browser

Open:

http://localhost


If needed, allow Apache through UFW:
```
sudo ufw allow 80/tcp
sudo ufw enable
```
✅ BONUS (Optional Hardening)

    Prevent directory listing: create .htaccess in /shared/files/:

echo "Options -Indexes" | sudo tee /var/www/html/shared/files/.htaccess

    Add a simple .htaccess password for deleting files (if you want that later)
