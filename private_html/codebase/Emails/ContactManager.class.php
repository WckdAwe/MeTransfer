<?php
namespace codebase\Emails;

// TODO: Rework with Inheritance for Template overriding.
class ContactManager{
    
    public $ErrorManager;
    
    public function sendMail($email, $msg){
        if(!empty($email)){
            if(!empty($msg)){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if(strlen($msg) >= 100 && strlen($msg) <= 4000){
                        $EmailManager = new \codebase\Emails\Email();
                        $EmailManager->setReciever('admin@vasil7112.com');
                        $EmailManager->setSubject('New message by : '. $email);
                        $EmailManager->setContents('<html>
                                                            <head>
                                                                <style>
                                                                    body{
                                                                        margin: 0;
                                                                        padding: 0;
                                                                    }
                                                                    .ecnav{
                                                                        background-color: #0B0B0B;
                                                                    }
                                                                        .ecnav>img{
                                                                        padding: 10px;
                                                                    }
                                                                    .mailbody{
                                                                        background-color: #EDEDED;
                                                                        padding: 10px;
                                                                    }
                                                                    .nomargin{
                                                                        margin: 0px 1px 0 !important;
                                                                    }
                                                                </style>
                                                            </head>
                                                                    <body>
                                                                        <div class="ecnav">
                                                                            <img src="http://vasil7112.com/assets/images/logo.png" alt=""/>
                                                                        </div>
                                                                        <div class="mailbody">
                                                                            <h2 class="nomargin">Dear Vasil7112,</h2>
                                                                            <p class="nomargin">You have recieved a new message by: <strong>' .$email. '</strong></p>
                                                                            <p class="nomargin">' . htmlspecialchars($msg) . '</p>
                                                                        </div>
                                                                    </body>
                                                    </html>');
                        $EmailManager->sendEmail();     
                    }else{
                        //$this->ErrorManager->addMessage('Message size must be between 100-4000 Charachters');
                    }
                }else{
                    //$this->ErrorManager->addMessage('Incorrect email');
                }
            }else{
                //$this->ErrorManager->addMessage('Message is missing');
            }
        }else{
            //$this->ErrorManager->addMessage('Email is missing');
        }
        return false;
    }
}