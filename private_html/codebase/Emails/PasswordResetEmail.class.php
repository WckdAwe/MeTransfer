<?php
namespace codebase\Emails;
class PasswordResetEmail extends StyledEmail {
    public function __construct($reset_token)
    {
        $this->setSubject('Reset password requested.');
        parent::setContents('<h2 class="nomargin">Hello there, </h2>
                                     <p class="nomargin">You (or someone else, our Robot-Monkeys can\'t figure that out yet), have requested a password reset.</p>
                                     <p class="nomargin">You can click <a href="http://'.__ENV['website_url'].'/account/password_reset_success?&token='.$reset_token.'">here</a> to reset your password.</p>
                                     <p class="nomargin">Or alternatively you can use this token: <b>'.$reset_token.'</b></p>');
    }
}
