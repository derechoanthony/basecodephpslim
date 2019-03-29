<?php
namespace App\Helper\Mailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Helper\Mailer\CResetPasswordTemplate;
use App\Helper\Mailer\CRegistrationTemplate;
class CMailer
{
    private $recipient;
    private $mode;
    private $content;
    private $resetPwd;
    public function __construct($data)
    {
        $this->content = $data['content'];
        $this->recipient = $data['emailAddress'];
        $this->mode = $data['mode'];
        $this->resetPwd = new CResetPasswordTemplate();
    }
    public function composeMessage()
    {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = env('SMTP_DEBUG');                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = env('EMAIL_HOST');  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = env('EMAIL_UNAME');                     // SMTP username
            $mail->Password   = env('EMAIL_PWD');                               // SMTP password
            $mail->SMTPSecure = env('SMTP_SECURE');                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = env('EMAIL_PORT');                                    // TCP port to connect to
        
            //Recipients
            $mail->setFrom(env('EMAIL_FROM'), 'Mailer');
            $mail->addAddress($this->recipient);     // Add a recipient
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
        
            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $emailTemplate = $this->emailTpl();
            $mail->Subject = $emailTemplate['subject'];
            $mail->Body    = $emailTemplate['subject'];
            $mail->AltBody = $emailTemplate['AltBody'];
        
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function emailTpl(){
        switch ($this->mode) {
            case 'reset':               
                return [
                        "body"=>$this->resetPwd->resetTemplate($this->content),
                        "AltBody"=>"This is the body in plain text for non-HTML mail clients",
                        "subject"=>"Reset Password"
                ];
                break;
            
            default:
                # code...
                
                break;
        }
    }
    }
