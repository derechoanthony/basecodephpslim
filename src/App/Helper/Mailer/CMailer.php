<?php
namespace App\Helper\Mailer;

use App\Helper\Mailer\CRegistrationTemplate;
use App\Helper\Mailer\CResetPasswordTemplate;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use App\Service\Domain\CUserDomain;
// use CInitialAssessment\CInitialAssessment;
use App\Helper\Mailer\CActivityTpl;
use App\Helper\Mailer\CAcceptLetterIA;
use App\Helper\Mailer\UserTemplate\CUserTemplate;

use App\Helper\Mailer\CActivityComplete;

class CMailer
{
    private $sender;
    /**
     * User email address
     */
    private $reciever;
    /**
     * link for activation / reset password /
     */

    private $url;
    private $regTpl;
    private $resetTpl;
    /**
     *
     * @param string $sender
     * @param string $reciever
     * @param array $content
     */

     private $CActivityTpl;
     private $userInfo;
     private $data;
     private $ia;
     private $CUserTpl;
     private $CActivityComplete;
     /**
      * Email constructor
      *
      * @param string $reciever
      * @param [type] $url
      * @param [type] $type
      * @param [type] $data
      */
    public function __construct(string $reciever, $url, $type = null,$data=null)
    {

        $this->reciever = $reciever;
        $this->url = $url;
        $this->type = (is_null($type)) ? 'registration' : $type;
        $this->regTpl = new CRegistrationTemplate();
        $this->resetTpl = new CResetPasswordTemplate();
        $this->data = (empty($data)) ? [] : $data;
        $this->CActivityTpl = new CActivityTpl();
        $this->userInfo = new CUserDomain();
        $this->ia = new CAcceptLetterIA();
        $this->CUserTpl = new CUserTemplate();

        $this->CActivityComplete = new CActivityComplete();
    }
    public function composeMessage()
    {
        $userInfo = $this->userInfo->getUserByEmail($this->reciever);
        $emailSubject = $this->getEmailSubject();
        $subject = $emailSubject;
            if ($this->type == 'registration') {
                
                // $message = $this->regTpl->registrationTemplate($this->url,$userInfo);
                /**
                 * server side */
                $body = $this->regTpl->registrationTemplate($this->url,$userInfo);
            }
            if ($this->type == 'reset') {
                // $message = $this->resetTpl->resetTemplate($this->url,$userInfo);
                /**server side */
                $body = $this->resetTpl->resetTemplate($this->url,$userInfo);
            }
       
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom(env('EMAIL_HOST'), "EMAIL NOTIFICATION");
        $email->setSubject($subject);
        $email->addTo($this->reciever);
        $email->addContent("text/html",$body);
        $sendgrid = new \SendGrid(env('SEND_GRID'));
        
        try {
            $response = $sendgrid->send($email);
            $email_res = [
                "status code"=>$response->statusCode(),
                "headers"=>$response->headers(),
                "body"=>$response->body(),
            ];

            return true;
        } catch (Exception $e) {
            $email_res = ["err"=>$e->getMessage()];
            return false;
        }
    }
    public function getEmailSubject(){
        switch ($this->type) {
            case 'cna':
            case 'cibi':
            case 'ha':
                if($this->type === 'cna'){
                    $activityName = 'Corporate Name Approval';
                }
                if($this->type === 'cibi'){
                    $activityName = 'CIBI';
                }
                if($this->type === 'ha'){
                    $activityName = 'Harrison Assessment';
                }

                return $activityName." Completed";
            break;
            case 'ia':
                return "Initial Assessment of Application";
            break;
            case 'ii':
                return "Initial Interview";
            break;
            case 'schedule':
                return "Schedule";
            break;
            case 'fi':
                return "Final Interview";
            break;
            case 'gtm':
                return "Going Through the Motion";
            break;
            case 'bdd':
                return "Business Direction Discussion";
            break;
            case 'fg':
                return "Franchise Grant";
            break; 
            case 'fa':
                return "Franchise Agreement Signing";
            break;
            case 'reset':
                return 'JFC Franchising - Reset Password';
            break;
            case 'registration':
                return 'JFC Franchising Account Verification';
            break;
            default:
                return  "";
            break;
        }
        }
    }
