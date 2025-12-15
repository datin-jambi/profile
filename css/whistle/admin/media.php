<?php
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler");
} else {
    ob_start();
}
include_once("lib.php");
include "timeout.php";
error_reporting(0);
session_start();
if ($_SESSION[login] == 1) {
    if (!cek_login()) {
        $_SESSION[login] = 0;
    }
}
if ($_SESSION[login] == 0) {
    header('location:logout.php');
} else {
    if (empty($_SESSION["usrname"]) && empty($_SESSION["passwd"]) && $_SESSION['login'] == 0) {
        echo '<script>alert("You Need To Login First !"); window.location = "index.php"</script>';
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>Whistle Blower - UPTD PPD Kota Jambi</title><meta charset="UTF-8" />
                <link rel="stylesheet" href="css/bootstrap.min.css" />
                <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
                <link rel="stylesheet" href="css/colorpicker.css" />
                <link rel="stylesheet" href="css/datepicker.css" />
                <link rel="stylesheet" href="css/uniform.css" />
                <link rel="stylesheet" href="css/select2.css" />
                <link rel="stylesheet" href="css/matrix-style.css" />
                <link rel="stylesheet" href="css/matrix-media.css" />
                <link rel="stylesheet" href="css/bootstrap-wysihtml5.css" />
                <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
                <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
                <script type="text/javascript" src="js/highcharts.js"></script>
                <script type="text/javascript" src="js/exporting.js"></script>
            </head>
            <body>
                <div id="header">
                    <h1>
                        <a href="media.php?menu=home">Bank Jambi</a>
                    </h1>
                </div>
                <div id="user-nav" class="navbar navbar-inverse">
                    <ul class="nav">
                        <li class="">
                            <a title="">
                                <i class="icon icon-user"></i> 
                                <span class="text">Hi, <?php echo $_SESSION["fullname"]; ?></span>
                            </a>
                        </li> 
                        <li class="">
                            <a title="Keluar" href="media.php?menu=chgpasswd">
                                <i class="icon icon-envelope-alt"></i> 
                                <span class="text">Ubah Password</span>
                            </a>
                        </li>
                        <li class="">
                            <a title="Keluar" href="logout.php">
                                <i class="icon icon-off"></i> 
                                <span class="text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="sidebar">
                    <ul>
                        <?php include "menu.php"; ?>
                    </ul>
                </div>
                <div id="content">
                    <div id="content-header">
                        <?php
                        $namamenu = $_GET['menu'];
                        $qry = mysql_query("SELECT * from tbl_menu WHERE stts = 'Active' AND link like '%$namamenu%'");
                        while ($breadcrumb = mysql_fetch_array($qry)) {
                            echo'<div id="breadcrumb">
                                        <a href="media.php?menu=home" title="Go to Home" class="tip-bottom">
                                            <i class="icon-home"></i> Home
                                        </a>
                                        <a href="' . $breadcrumb['link'] . '" class="current">' . $breadcrumb['nama_menu'] . '</a>
                                    </div>
                                   ';
                        }
                        ?>
                    </div>
                    <div class="container-fluid">
                        <?php include "content.php"; ?>
                    </div>
                </div>
                <div class="row-fluid">
                    <div id="footer" class="span12"> Copyright &copy; <script type="text/javascript">var creaditsyear = new Date();
                        document.write(creaditsyear.getFullYear());</script> <a href="">IT Bakeuda Jambi </a>- We Are Team </div>
                </div>

                <script src="js/jquery.min.js"></script> 
                <script src="js/select2.min.js"></script>
                <script src="js/jquery.dataTables.min.js"></script>
                <script src="js/matrix.tables.js"></script>
                <script src="js/jquery.ui.custom.js"></script> 
                <script src="js/bootstrap.min.js"></script> 
                <script src="js/jquery.uniform.js"></script> 
                <script src="js/matrix.js"></script> 
                <script src="js/matrix.form_common.js"></script> 
                <script src="js/bootstrap-colorpicker.js"></script> 
                <script src="js/bootstrap-datepicker.js"></script>
                
                <script type="text/javascript">
                        function goPage(newURL)
                        {
                            if (newURL != "")
                            {
                                if (newURL == "-")
                                {
                                    resetMenu();
                                }
                                else
                                {
                                    document.location.href = newURL;
                                }
                            }
                        }
                        function resetMenu()
                        {
                            document.gomenu.selector.selectedIndex = 2;
                        }

                        /*$('#sidebar > ul li').click(function(e) 
                         {
                         $('li.active').removeClass('active');
                         var $this = $(this);
                         $this.addClass('active');
                         e.preventDefault('active');
                         });*/
                </script>
            </body>
        </html>
        <?php
    }
}
?>