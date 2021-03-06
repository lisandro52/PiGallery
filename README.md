PiGallery
=========

This is a lightweight online photo gallery design for the Raspberry Pi, but can be used with any other device.
The aim of the project is to create a photo gallery that is optimised for low-powered servers, but with a rich client side.

Features:
--------

* The site has 2 modes: Database (Mysql) mode and a database-less mode.
* Authentication
  * Support for no-authentication/Guest mode at local network.
* Recursive directory scan.
* On the fly thumbnail generation with cache.
* On the fly directory indexing (for database mode).
* Nice lightbox for image preview (using blueimp* gallery).
* Supports image keywords (like lightroom keywords).
* Search in file names, directory names, keywords (db-mode only).
* Designed for low* resource serves (weak CPU, low bandwidth)
* Translation support (English, Hungarian)

Installation:
--------

*  Install PHP, and a webserver (Apache, Nginx) on your device and a (optional) database server (mysql, mariadb)
*  Download the latest (it contains minfied files) [release (v0.99.1)](release/pigallery_0.99.1.zip) from github, extract it in your www directory (or in a subdirectory)
*  Open `config.php` and edit the configurations according to the comments.
*  If you're using the database mode, be sure that the database you set in the `config.php` exists'
*  Login with user: admin, pass: admin (or whatever you set in config.php)
*  In database mode, click on admin panel and click index photos,
   or enable on the fly indexing in the config.php (but in this case the manual indexing will still be at the admin panel)

Notes:
--------

* Site is using mysqli for accessing database. It should be enabled in `php.ini`.
* For thumbnail generation, the package `php-gd` is needed.
* Webserver's user (e.g: www-data for Nginx) needs read and write rights for the thumbnail folder, and read rights for the images folder.
* For best performance don't store too many photos in a directory (best is under 200-300).
* At large directories, PHP timeout may occur; if this is the case, increase the timeout in `php.ini`.
* For faster thumbnail generating user less thumbnail sizes (eg.: only one, with a low value such as `150`)
  and disable image resampling in config.php.

ScreenShot:
--------
![screenshot](screenshot1.jpg)
