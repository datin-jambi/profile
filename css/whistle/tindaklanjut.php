<!DOCTYPE html>
<html>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <head>
        <title>Whistle Blower</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="shortcut icon" href="admin/img/favsample.png">
        <script src="grafik/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="grafik/highcharts.js" type="text/javascript"></script>
        <script src="grafik/exporting.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/scripts.js"></script>
        <script type="text/javascript" src="js/coda-slider.1.1.1.js"></script>
        <script type="text/javascript" src="js/jquery-easing-compatibility.1.2.pack.js"></script>
        <script type="text/javascript" src="js/jquery-easing.1.2.pack.js"></script>
        <script type="text/javascript">
            var chart1; // globally available
            $(document).ready(function () {
                chart1 = new Highcharts.Chart({
                    chart: {
                        renderTo: 'container',
                        type: 'column'
                    },
                    title: {
                        text: 'JUMLAH DATA PELANGGARAN'
                    },
                    xAxis: {
                        categories: ['Tahun <?php echo date('Y'); ?>']
                    },
                    yAxis: {
                        title: {
                            text: 'Jumlah Pengaduan'
                        }
                    },
                    series:
                            [
<?php
// file koneksi php
    include 'lib.php';
    $date= date('Y');
    $sql_jumlah = "SELECT waktu_kejadian, tindak_lanjut1, count(tindak_lanjut1) as jumlah FROM `v_pelanggaran` where Substring(waktu_kejadian, 1,4)= '$date' group by tindak_lanjut1
                   union all 
                   SELECT waktu_kejadian, tindak_lanjut2,  count(tindak_lanjut2) as jumlah FROM `v_pelanggaran` where Substring(waktu_kejadian, 1,4)= '$date' group by tindak_lanjut2
                   union all  
                   SELECT waktu_kejadian, tindak_lanjut3, count(tindak_lanjut3) as jumlah FROM `v_pelanggaran` where Substring(waktu_kejadian, 1,4)= '$date' group by tindak_lanjut3";
    $query_jumlah = mysql_query($sql_jumlah) or die(mysql_error());
    while ($row = mysql_fetch_array($query_jumlah)) {
           if($row['tindak_lanjut1'] != '' or $row['tindak_lanjut1'] != 0)
         { ?>
                    {
                            name: '<?php   echo $row['tindak_lanjut1']; ?>',
                            data: [<?php echo $row['jumlah']; ?>]
                    },
        <?php
    }
    
         }
    ?>]
                });
            });
        </script>
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
                        <li class="selected"><a href="tindaklanjut.php">TindakLanjut</a></li>
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
            <!--End of banner_content-->
            <div class="main_content">
                <div class="">
                    <h1 style="color:black;">Tindak Lanjut</h1>
                    <div class="testimonials_block"> <img src="images/icon3.gif" alt="" border="0" class="thumb_left" />
                        <div class="testimonials_details">
                            <p> Berupa gambar grafik yang menggambarkan jumlah pengaduan yang masuk sepanjang tahun, jumlah pengaduan yang di tindak lanjuti, jumlah pengaduaan yang terbukti melakukan pelanggaran.</p>
                        </div>
                    </div>
                    <div class="testimonials_block"> 
                        <div class="testimonials_details">
                            <h2 style="color:black;">Grafik Dari Tindak Lanjut</h2>
                            <!--End of header-->
                            <!-- fungsi yang di tampilkan dibrowser  -->
                            <div id="container" style="min-width: 200px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                
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
                <div class="footer_copyrights"><a href="http://bankjambi.co.id"><img src="images/logo.png" alt="" border="0" width="300px" height="40px"/></a></div>
            </div>
            <!--End of footer-->
        </div>
        <!--End of wrap-->
        <div align=center>Test - <a href='#'>Testing</a></div></body>
    </body>
</html>
