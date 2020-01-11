<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title><?php echo $title; ?></title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Themesbrand" name="author" />
        <link rel="shortcut icon" href="<?php echo base_url('assets/theme/Veltrix_v2.1/horizontal/') ?>assets/images/favicon.ico">

        <link href="<?php echo base_url('assets/theme/Veltrix_v2.1/horizontal/') ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url('assets/theme/Veltrix_v2.1/horizontal/') ?>assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url('assets/theme/Veltrix_v2.1/horizontal/') ?>assets/css/style.css" rel="stylesheet" type="text/css">

    	<!-- css outline -->
        <?php echo $css_outline; ?>

        <style>
            a, button, .btn {
                border-radius: 0 !important;
            }

            <?php echo $css_inline; ?>
        </style>
    </head>

    <body>

        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main">
                <div class="container-fluid">

                    <!-- Logo container-->
                    <div class="logo">

                        <a href="index.html" class="logo">
                            <img src="<?php echo base_url('assets/theme/Veltrix_v2.1/horizontal/') ?>assets/images/logo-sm.png" alt="" class="logo-small">
                            <img src="<?php echo base_url('assets/theme/Veltrix_v2.1/horizontal/') ?>assets/images/logo-light.png" alt="" class="logo-large">
                        </a>

                    </div>

                    <!-- End Logo container-->


                    <div class="menu-extras topbar-custom">

                        <ul class="navbar-right list-inline float-right mb-0">
                            <!-- full screen -->
                            <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                                <a class="nav-link" href="#" id="btn-fullscreen">
                                    <i class="mdi mdi-fullscreen noti-icon"></i>
                                </a>
                            </li>

                            <li class="dropdown notification-list list-inline-item">
                                <div class="dropdown notification-list nav-pro-img">
                                    <a class="dropdown-toggle nav-link arrow-none nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                        <img src="<?php echo base_url('assets/theme/Veltrix_v2.1/horizontal/') ?>assets/images/users/user-4.jpg" alt="user" class="rounded-circle">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                        <!-- item-->
                                        <a class="dropdown-item text-danger" href="<?php echo base_url('auth/sign_out') ?>"><i class="mdi mdi-power text-danger"></i> Logout</a>
                                    </div>
                                </div>
                            </li>

                            <li class="menu-item list-inline-item">
                                <!-- Mobile menu toggle-->
                                <a class="navbar-toggle nav-link">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                                <!-- End mobile menu toggle-->
                            </li>

                        </ul>
                    </div>
                    <!-- end menu-extras -->

                    <div class="clearfix"></div>

                </div> <!-- end container -->
            </div>
            <!-- end topbar-main -->

            <!-- MENU Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">

                            <li class="has-submenu">
                                <a href="<?php echo base_url('dashboard/index') ?>"><i class="ti-home"></i>Dashboard</a>
                            </li>

							<li class="has-submenu">
								<a href="#"><i class="ti-package"></i>Master Data <i class="fas fa-angle-down"></i></a>
								<ul class="submenu">
									<li><a href="<?php echo base_url('biodata/index') ?>">Biodata</a></li>
								</ul>
							</li>

							<li class="has-submenu">
								<a href="#"><i class="ti-archive"></i>User Page <i class="fas fa-angle-down"></i></a>
								<ul class="submenu">
									<li><a href="<?php echo base_url('auth/change_password') ?>">Change Password</a></li>
									<li><a href="<?php echo base_url('auth/sign_out') ?>">Sign Out</a></li>
								</ul>
							</li>

                        </ul>
                        <!-- End navigation menu -->
                    </div> <!-- end #navigation -->
                </div> <!-- end container -->
            </div> <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->