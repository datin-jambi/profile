<?php
include_once("lib.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
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
                <div class="logo"><a href="#"><img src="images/logo.png" alt="" border="0" width="300px" height="50px"/></a></div>
                <div id="menu">
                    <ul>
                        <li ><a href="index.php">home</a></li>
                        <li class="divider"></li>
                        <li ><a href="beranda.php">Beranda</a></li>
                        <li class="divider"></li>
                        <li><a href="pelaporan.php">Pelaporan</a></li>
                        <li class="divider"></li>
                        <li ><a href="tindaklanjut.php">TindakLanjut</a></li>
                        <li class="divider"></li>
                        <li class="selected"><a href="faq.php">FAQ</a></li>
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
                    <!--End of banner_content-->
                    <div class="main_content">

                        <h2>Frequently Asked Question</h2>
                        <?php
                        $no = 1;
                        $query = mysql_query("Select * from tbl_faq");
                        while ($row = mysql_fetch_array($query)) {
                            $replace1 = str_replace("&lt;", "<", $row['isi_faq']);
                            $replace2 = str_replace("&gt;", ">", $replace1);
                            echo'<div class="">
                                        <div >
                                              <h3><p> ' . $no . '. ' . $row['nama_faq'] . '<p></h3>
                                            <p style= "position:relative; left:18px;">' . $replace2 . '</p>
                                        </div>
                                    </div>';
                            $no++;
                        }
                        ?>


                        <div class="clear"></div>
                    </div>
                    <!--End of main_content-->
                    <div class="footer">
                        <div class="footer">
                            <div class="footer_links"> 
                                <a href="index.php">HOME</a> 
                                <a href="beranda.php">BERANDA</a> 
                                <a href="pelaporan.php">PELAPORAN</a> 
                                <a href="tindaklanjut.php">TINDAKLANJUT</a>
                                <a href="faq.php">FAQ</a> 
                            </div>
                            <div class="footer_copyrights"><a href="http://bankjambi.co.id"><img src="images/logo.png" alt="" border="0" width="300px" height="40px"/></a></div>
                        </div>
                    </div>
                    </div>
                    <!--End of wrap-->
                    <div align=center>Test - <a href='#'>Testing</a></div></body>
                    </div>
                    </body>
                    </html>
