<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\Database;

require_once __DIR__ . '/../vendor/autoload.php';

class Mailer {
    /**
     * Send an email via Google SMTP
     *
     * @param string|array $to Receiver email address (string) or array of addresses
     * @param string $subject Subject
     * @param string $content Content (HTML)
     * @param array $attachments Array of file paths to attach (optional)
     * @return array ['success' => bool, 'message' => string]
     */
    public static function send($to, $subject, $content, $attachments = [], $shouldLog = true) {
        $mail = new PHPMailer(true);
        $result = ['success' => false, 'message' => ''];

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'] ?? ''; // Google Email
            $mail->Password   = $_ENV['SMTP_PASS'] ?? ''; // App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
            $mail->Port       = $_ENV['SMTP_PORT'] ?? 465;

            // Charset
            $mail->CharSet = 'UTF-8';

            // Recipients
            $mail->setFrom($mail->Username, $_ENV['SMTP_FROM_NAME'] ?? 'Neuron AI Admin');
            
            if (is_array($to)) {
                foreach ($to as $address) {
                    $mail->addAddress($address);
                }
            } else {
                $mail->addAddress($to);
            }

            // Attachments
            if (!empty($attachments)) {
                foreach ($attachments as $file) {
                    if (is_array($file)) {
                        // [path, name] format
                        if (file_exists($file[0])) {
                            $mail->addAttachment($file[0], $file[1]);
                        }
                    } else {
                        // String path format
                        if (file_exists($file)) {
                            $mail->addAttachment($file);
                        }
                    }
                }
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $content;
            $mail->AltBody = strip_tags($content);

            $mail->send();
            $result['success'] = true;
            $result['message'] = 'Message has been sent';

            // Log Success
            if ($shouldLog) {
                self::log(is_array($to) ? implode(',', $to) : $to, $subject, $content, 'success');
            }

        } catch (Exception $e) {
            $result['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            
            // Log Failure
            if ($shouldLog) {
                self::log(is_array($to) ? implode(',', $to) : $to, $subject, $content, 'fail', $mail->ErrorInfo);
            }
        }

        return $result;
    }

    /**
     * Log the mail sending result
     */
    private static function log($recipient, $subject, $content, $status, $error = null) {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO mail_logs (recipient, subject, content, status, error_message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $recipient,
                $subject, 
                $content, 
                $status, 
                $error
            ]);
        } catch (\Exception $e) {
            // Logging failed, maybe write to file or ignore
            error_log("Mail logging failed: " . $e->getMessage());
        }
    }
}
