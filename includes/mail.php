<?php
function sendMail($to, $nom, $password){

$subject = "Votre compte Chef";

$message = "
Bonjour $nom,

Votre compte a été créé.

Email: $to
Mot de passe: $password

Merci.
";

$headers = "From: resto@univ.com";

mail($to,$subject,$message,$headers);
}