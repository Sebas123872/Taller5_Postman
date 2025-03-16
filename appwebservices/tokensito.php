<?php
$secret_key = "Sebas12345";
$token = hash_hmac("sha256", "mi_token_secreto", $secret_key);
echo $token;
?>
