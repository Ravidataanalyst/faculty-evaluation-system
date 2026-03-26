<?php
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function notifyFacultyAfterReview($facultyEmail, $facultyName, $status, $comments) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'projectfacultyeval123@gmail.com';   // your email
        $mail->Password = 'cnsd skvs avez hkqc';               // your app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('projectfacultyeval123@gmail.com', 'Faculty Evaluation System');
        $mail->addAddress($facultyEmail);
        $mail->Subject = 'Your Evaluation Has Been Reviewed';
        $mail->Body = "Dear $facultyName,\n\nYour evaluation has been reviewed.\n\nStatus: $status\nComments: $comments\n\nRegards,\nEvaluation Team";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}
?>
