<?php
namespace codebase\Emails;
class NewTransferEmail extends StyledEmail {
    private $transferUrl;
    public function __construct($transferName, $transferUrl)
    {
        $transferName = htmlentities($transferName);
        $this->transferUrl = $transferUrl;
        $this->setSubject('You have a new Transfer: '. $transferName);
        $transfer_msg = empty($transfer_msg) ? '' : '<p class="nomargin>The following message was included: '.htmlentities($transfer_msg).'</p>';
        parent::setContents('<h2 class="nomargin">Hello there, </h2>
                                     <p class="nomargin">You have received a new Transfer called \''.$transferName.'\'</p>
                                     <p class="nomargin">To begin download on this totally harmless file <a href="http://'.__ENV['website_url'].$this->transferUrl.'">click here</a></p>
                                     '.$transfer_msg);
    }
}
