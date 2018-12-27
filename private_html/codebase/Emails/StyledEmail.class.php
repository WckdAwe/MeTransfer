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
                                    <img src="https://prod-cdn.wetransfer.net/assets/wt-facebook-568be8def5a86a09cedeb21b8f24cb208e86515a552bd07d856c7d5dfc6a23df.png" alt=""/>
                                </div>
                                <div class="mailbody">
                                    '.$contents.'
                                </div>
                            </body>
                        </html>';
        parent::setContents($HTML_CONTENT);
    }
}
