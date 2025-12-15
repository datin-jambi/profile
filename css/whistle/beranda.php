<?php
include_once("lib.php");
?>
<html>
    <head>
        <title>Whistle Blower</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="shortcut icon" href="admin/img/favsample.png">
        <script type="text/javascript" src="js/jquery-1.3.1.min.js"></script>
        <script type="text/javascript" src="js/scripts.js"></script>
        <script type="text/javascript" src="js/coda-slider.1.1.1.js"></script>
        <script type="text/javascript" src="js/jquery-easing-compatibility.1.2.pack.js"></script>
        <script type="text/javascript" src="js/jquery-easing.1.2.pack.js"></script>
    </head>
    <body>
        <div class="wrap">
            <div class="header">
                <div class="logo"><a href="#"><img src="images/logo.png" alt="" border="0" width="300px" height="50px" /></a></div>
                <div id="menu">
                    <ul>
                        <li ><a href="index.php">home</a></li>
                        <li class="divider"></li>
                        <li class="selected"><a href="beranda.php">Beranda</a></li>
                        <li class="divider"></li>
                        <li><a href="pelaporan.php">Pelaporan</a></li>
                        <li class="divider"></li>
                        <li><a href="tindaklanjut.php">TindakLanjut</a></li>
                        <li class="divider"></li>
                        <li><a href="faq.php">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <!--End of header-->
            <div class="banner_content">
                <div class="slider_content">
                    <div class="featured-arrow"  id="stripNavL0"><a href="#"><img src="images/arrow_left.gif" alt="" border="0" /></a> </div>
                    <div id="slider">
                        <div class="slider-wrap">
                            <div id="sliderc" class="csw">
                                <div class="panelContainer">
                                    <div class="panel"><img src="images/2.jpg" alt="" border="0" /></a> </div>
                                    <div class="panel"><img src="images/gambar2.jpg" alt="" border="0" /></a> </div>
                                    <div class="panel"><img src="images/gambar3.jpg" alt="" border="0" /></a> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="featured-arrow" id="stripNavR0"> <a href="#"><img src="images/arrow_right.gif" alt="" border="0"/></a> </div>
                    <div class="clear"></div>
                </div>
                <!--end of slider content-->
            </div><br><br>
            <div class="main_content">
                <?php
                $query = mysql_query("Select * from tbl_beranda");
                while ($row = mysql_fetch_array($query)) {
                    $replace1 = str_replace("&lt;", "<", $row['isi_beranda']);
                    $replace2 = str_replace("&gt;", ">", $replace1);
                    echo'<div class="">
                            <h2>' . $row['nama_beranda'] . '</h2>
                            <p>' . $replace2. '</p>
                        </div>';
                }
                ?>
                <div class="clear"></div>
            </div>
            <!--End of main_content-->
            <div class="footer">
                <div class="footer_links"> 
                    <a href="index.php">HOME</a> 
                    <a href="beranda.php">BERANDA</a> 
                    <a href="pelaporan.php">PELAPORAN</a> 
                    <a href="tindaklanjut.php">TINDAKLANJUT</a>
                    <a href="faq.php">FAQ</a> 
                </div>
                <div class="footer_copyrights"><a href="http://bankjambi.co.id"><img src="images/logo.png" alt="" border="0" width="300px" height="40px" /></a></div>
            </div>
            <!--End of footer-->
        </div>
        <!--End of wrap-->
        <div align=center>Test - <a href='#'>Testing</a></div></body>
</html>
