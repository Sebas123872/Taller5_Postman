<?php
$secret_key = "FreimanClave123";
$token = hash_hmac("sha256", "mi_token_secreto", $secret_key);
echo $token;
?>
