<?php
define('BASEURL', 'http://localhost/MVC/public/');

//Admin
define('ADMINURL', BASEURL . 'admin');
define('RUANGURL', BASEURL . 'ruang');
define('DOSENURL', BASEURL . 'dosen');
define('JADWALURL', BASEURL . 'jadwal');

//User
define('USERURL', BASEURL . 'user');
define('PEMINJAMANURL', BASEURL . 'peminjaman');

date_default_timezone_set("Asia/Jakarta");
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'peminjaman-ruang');
