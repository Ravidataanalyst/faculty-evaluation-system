<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

function notifyFacultyAfterScore($email, $name, $totalScore, $reviewComments) {
    // Ensure config is loaded if not already
    require_once __DIR__ . '/../config.php';
    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port = SMTP_PORT;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email, $name);
        $mail->Subject = '✅ Your Performance Evaluation is Complete';

        $mail->Body = "Hi $name,

✅ Your performance evaluation has been completed and scored by the department head.

📊 Total Score: $totalScore%

📝 Review Comments:
$reviewComments

You may log in to the system to view detailed feedback.

Regards,  
Faculty Evaluation System";

        $mail->send();
    } catch (Exception $e) {
        error_log("Score Mail Error: {$mail->ErrorInfo}");
    }
}
