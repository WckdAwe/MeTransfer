<?php
namespace codebase\Emails;
class NewTransferOwnerEmail extends StyledEmail {
    private $transferUrl;
    public function __construct($transferName, $transferUrl, $transfer_msg=null)
    {
        $transferName = htmlentities($transferName);
        $this->transferUrl = $transferUrl;
        $transfer_msg = empty($transfer_msg) ? '' : '<p class="nomargin">The following message was included: '.htmlentities($transfer_msg).'</p>';
        $this->setSubject('You\'ve sent a Transfer: '. $transferName);
        parent::setContents('<h2 class="nomargin">Hello there, </h2>
                                     <p class="nomargin">Your Transfer called \''.$transferName.'\' has been delivered!</p>
                                     <p class="nomargin">You can also download this harmless file by clicking <a href="http://'.__ENV['website_url'].$this->transferUrl.'">here</a></p>
                                     '.$transfer_msg);
    }
}
