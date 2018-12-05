<?php
namespace codebase\Templates;

class PlutonTemplate extends Template{
    
    public function __construct(){
        parent::__construct();
        $this->setPageTitle(null);
    }
    
    public function getHead(){
        return '<meta charset=utf-8>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>'.$this->getPageName().($this->getPageTitle() != null ?  ' &bull; '.$this->getPageTitle() : '').'</title>
                <!-- Load Roboto font -->
                <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,700&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css">
        
                <!--Load css styles -->
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/bootstrap.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/bootstrap-responsive.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/style.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/pluton.css" />
                <!--[if IE 7]>
                <link rel = "stylesheet" type = "text/css" href = "css/pluton-ie7.css" />
                <![endif] -->
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/jquery.cslider.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/jquery.bxslider.css" />
                <link rel = "stylesheet" type = "text/css" href = "/assets/css/animate.css" />
                <link rel = "shortcut icon" href = "/assets/images/ico/favicon.png">
        ';
    }
    
    public function getNavigation(){
        return '<div class="navbar">
                    <div class="navbar-inner">
                        <div class="container">
                            <a href="/" class="brand">
                                <img src="/assets/images/logo.png" width="120" height="40" alt="Logo" />
                                <!-- This is website logo -->
                            </a>
                            <!-- Navigation button, visible on small resolution -->
                            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                <i class="icon-menu"></i>
                            </button>
                            <!-- Main navigation -->
                            <div class="nav-collapse collapse pull-right">
                                <ul class="nav" id="top-navigation">
                                    <li class="active"><a href="/#home">Home</a></li>
                                    <li><a href="/#portfolio">Portfolio</a></li>
                                    <li><a href="/#about">About</a></li>
                                    <li><a href="/#clients">Clients</a></li>
                                    <!--<li><a href="/#price">Price</a></li>-->
                                    <li><a href="/#contact">Contact</a></li>
                                </ul>
                            </div>
                            <!-- End main navigation -->
                        </div>
                    </div>
                </div>';
    }
    
    public function getFooter(){
        return '<div class="footer">
                    <p>&copy; 2015 vasil7112.com</p>
                </div>
                <div class="scrollup">
                    <a href="#">
                        <i class="icon-up-open"></i>
                    </a>
                </div>


                <div class="modal fade modal-to-external-link" tabindex="-1" role="dialog">
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