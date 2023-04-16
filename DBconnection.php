<?php

$servername = "localhost"; // Veritabanı sunucusu adı
$username = "root"; // Veritabanı kullanıcı adı
$password = ""; // Veritabanı şifresi
$dbname = "turkticaret"; // Veritabanı adı


try {
    $baglan = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $baglan->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    
    die("Bağlantı hatası: " . $e->getMessage());
}



?>
