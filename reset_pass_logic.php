<?php

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date ("Y-m-d H:i:s", time() + 60 * 30);

$mysqli = require __DIR__ . "/db_connection.php";

$sql = "UPDATE utenti
        SET reset_token = ?,
        reset_token_expiration = ?
        WHERE email = ?";
$stmt = $mysqli->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

if ($mysqli->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom('from@example.com');
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body    = <<<END

    Click <a href="http://localhost:8888/Edusogno/edusogno-esercizio/reset-password.php?token=$token">here</a> to reset to password.

    END;

    try {
        $mail->send();

    } catch (Exception $e) {
        echo "Impossibile inviare il messaggio. Errore Mailer: {$mail->ErrorInfo}";
    }
}

echo 'Il messaggio è stato inviato con successo, controlla la tua mail o inbox.';