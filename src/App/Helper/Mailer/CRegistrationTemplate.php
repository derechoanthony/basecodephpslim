<?php
namespace App\Helper\Mailer;
use App\Helper\cs3Manger;
class CRegistrationTemplate
{
    private $s3;
    public function __construct()
    {
        $this->s3 = new cs3Manger();
    }
    public function registrationTemplate($url,$userInfo)
    {
        
        return '<html lang="en">
                    <head>
                        <style>
                        .card {
                                box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
                                transition: 0.3s;
                                width: 40%;
                                margin: auto;
                                margin-top:2%;
                            }

                            .container {
                                padding: 2px 26px;
                            }

                            .header{
                                background-color: #c9c9c9;
                            }

                            .emailLogo{
                                margin-left: 2%;
                                margin-top: 2%;
                                margin-bottom: 2%;
                                width: 170px;
                            }

                            body{
                                font-family: Arial;
                            }

                            .cardBody{
                                font-size:14px;
                            }

                            .body{
                                margin-top: 3%;
                            }

                            .footer{
                                margin-top: 8%;
                            }
                        </style>
                    </head>
                <body>
                    <div class="card">
                        <div class="header">
                            <img  class="emailLogo" src="'.$this->s3->getFile('Jollibee_Foods_Corporation_logo.png').'" alt="Logo goes here" style="height: 30px;">
                        </div>
                        <div class="cardBody">
                            <div class="container">
                                <p><strong>CONFIRM YOUR EMAIL</strong></p>
                                <p>Dear '.ucfirst(strtolower($userInfo->first_name)).' '.ucfirst(strtolower($userInfo->last_name)).',</p>
                                <div class="body">
                                    <p>
                                        We received a request to register your account with the email address of ('.$userInfo->email.') to JFC Franchising. 
                                        To complete your registration, please verify your email by accessing the link:<a href="' . $url . '">Account Verification Link</a>
                                    </p>
                                </div>
                                <p>The verification link will expire within 24 hours.</p>
                                <p><a href="http://franchising-uat.jfcgrp.com">JFC Franchising URL</a></p>
                                <div class="footer">
                                    <p>Best Regards,</p>
                                    <p>JFC Franchising Team</p>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                </body>
                </html>';
    }
}
