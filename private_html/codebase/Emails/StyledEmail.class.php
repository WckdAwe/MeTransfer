<?php
namespace codebase\Emails;
class StyledEmail extends Email {
    public function setContents($contents)
    {
        $HTML_CONTENT = '<html>
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
                                    <img src="http://'.__ENV['website_url'].'/assets/img/MeTransferLogoFull.png" alt="" width="250" height="38"/>
                                </div>
                                <div class="mailbody">
                                    '.$contents.'
                                </div>
                            </body>
                        </html>';
        parent::setContents($HTML_CONTENT);
    }
}
