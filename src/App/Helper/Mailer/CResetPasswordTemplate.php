<?php
namespace App\Helper\Mailer;
use App\Helper\cs3Manger;
class CResetPasswordTemplate
{
    private $s3;
    public function __construct()
    {
        $this->s3 = new cs3Manger();
    }
    public function resetTemplate($data)
    {
        return '<!doctype html>
                    <html lang="en">
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
                                    margin-top: 7%;
                                }
                            </style>
                        </head>
                    <body>
                        <div class="card">
                            <div class="cardBody">
                                <div class="container">
                                    <p>Dear '.ucfirst(strtolower($data['first_name'])).' '.ucfirst(strtolower($data['last_name'])).',</p>
                                    <div class="body">
                                        <p>We received a request to reset your account password. To proceed, redirect to this URL to reset your password. <a href="' . $data['url'] . '">Reset Password URL</a></p>
                                        <br>
                                        <p>The password reset link will expire within 24 hours.</p>
                                        <p><a href="http://franchising-uat.jfcgrp.com/#/login">JFC Franchising URL</a></p>
                                    </div>
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
