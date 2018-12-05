<?php
namespace codebase\Templates;

class DemoTemplate extends Template{
    public $project;
    public function __construct($proj_title = null){
        parent::__construct();
        $this->setPageTitle(null);
        if($proj_title != null){
            $this->project = new \codebase\Projects\ProjectManager();
            $this->project->selectProjectByName($proj_title);
            $this->project = $this->project->getProjects();
            $this->project = $this->project[0];
        }
    }
    
    public function getHead(){
        return '<link rel = "stylesheet" type = "text/css" href = "/assets/css/bootstrap.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/bootstrap-responsive.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/style.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/pluton.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/demo.css" />
                <!--[if IE 7]>
                <link rel = "stylesheet" type = "text/css" href = "css/pluton-ie7.css" />
                <![endif] -->
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/jquery.cslider.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/jquery.bxslider.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/animate.css" />
                <link rel = "shortcut icon" href = "/assets/images/ico/favicon.png">';
    }
    
    public function getNavigation() {
        return '<div class="navbar">
                    <div class="navbar-inner">
                        <div class="container">
                            <a href="/" class="brand">
                                <img src="/assets/images/logo.png" width="120" height="40" alt="Logo" />
                            </a>
                            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                <i class="icon-menu"></i>
                            </button>
                            <div class="nav-collapse collapse pull-right">
                                <ul class="nav" id="top-navigation">
                                    <li><a href="/">Back to main page</a></li>
                                    '.($this->project->link_source != null ? '<li><a href="'.$this->project->link_source.'">Source Code</a></li>' : '').'
                                    '.($this->project->link_download != null ? '<li><a href="'.$this->project->link_download.'">Download</a></li>' : '').'
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    
    public function getFooter(){
        return '<div class="modal fade modal-to-external-link" tabindex="-1" role="dialog">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title center">You\'r about to leave this website.</h4>
                        </div>
                        <div class="modal-body">
                            <h1 id="timer-to-external" class="center">5</h1>
                            <h6 class="center"><a href="" id="failure-redirect-link" class="link-blue" target="_blank">Click Here</a> if you dont get redirected.</h6>
                            <h4 class="center">I hope you enjoyed your stay. I am releasing new content every now and then, so make sure to come back for more! :3</h4>
                            <ins class="adsbygoogle"
                                style="display:block"
                                data-ad-client="ca-pub-2644892027040046"
                                data-ad-slot="3738026246"
                                data-ad-format="auto"></ins>
                        </div>
                      </div>
                  </div>
                </div>
                
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
                <script src="/assets/js/jquery.js"></script>
                <script type="text/javascript" src="/assets/js/jquery.mixitup.js"></script>
                <script type="text/javascript" src="/assets/js/bootstrap.js"></script>
                <script type="text/javascript" src="/assets/js/modernizr.custom.js"></script>
                <script type="text/javascript" src="/assets/js/jquery.bxslider.js"></script>
                <script type="text/javascript" src="/assets/js/jquery.cslider.js"></script>
                <script type="text/javascript" src="/assets/js/jquery.placeholder.js"></script>
                <script type="text/javascript" src="/assets/js/jquery.inview.js"></script>

                <!-- css3-mediaqueries.js for IE8 or older -->
                <!--[if lt IE 9]>
                    <script src="/assets/js/respond.min.js"></script>
                <![endif]-->
                <script type="text/javascript" src="/assets/js/app.js"></script>
               '.$this->getJS();
    }
}