<?php
$salt = bin2hex(openssl_random_pseudo_bytes(32));
$password = "bigDaddy";
$hash = hash_pbkdf2("sha512", $password, $salt, 2048, 128);
echo $hash;
echo "<br>";
echo $salt;
?>