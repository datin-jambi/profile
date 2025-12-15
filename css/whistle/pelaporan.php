<?php
session_start();
include_once 'lib.php';
?>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    function hanyaAngka(e, decimal) {
        var key;
        var keychar;
        if (window.event) {
            key = window.event.keyCode;
        } else
        if (e) {
            key = e.which;
        } else
            return true;

        keychar = String.fromCharCode(key);
        if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27)) {
            return true;
        } else
        if ((("-0123456789").indexOf(keychar) > -1)) {
            return true;
        } else
        if (decimal && (keychar == ".")) {
            return true;
        } else
            return false;
    }
</script>
<html>
    <head>
        <title>Whistle Blower</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="calender/jquery-ui.css" />
        <link rel="shortcut icon" href="admin/img/favsample.png">
        <script type="text/javascript" src="calender/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="calender/jquery-ui.js"></script>
        <script type="text/javascript" src="js/scripts.js"></script>
        <script type="text/javascript" src="js/coda-slider.1.1.1.js"></script>
        <script type="text/javascript" src="js/jquery-easing-compatibility.1.2.pack.js"></script>
        <script type="text/javascript" src="js/jquery-easing.1.2.pack.js"></script>
        <script type="text/javascript">
            $(function () {
                $("#input").datepicker({
                    changeYear: true,
                    changeMonth: true,
                    format: "yy-mm-dd"

                });
            });
        </script>
    </head>
    <body>
        <div class="wrap">
            <div class="header">
                <div class="logo"><a href="#"><img src="images/logo.png" alt="" border="0" width="200px" height="50px"/></a></div>
                <div id="menu">
                    <ul>
                        <li ><a href="index.php">home</a></li>
                        <li class="divider"></li>
                        <li><a href="beranda.php">Beranda</a></li>
                        <li class="divider"></li>
                        <li class="selected"><a href="pelaporan.php">Pelaporan</a></li>
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
            <!--End of banner_content-->
            <?php
            $query = "select max(nomor_referensi) as maxKode from tbl_pengaduan_pelanggaran WHERE DATE_FORMAT(waktu_kejadian,'%Y')=DATE_FORMAT(NOW(),'%Y')";
            $hasil = mysql_query($query);
            $data = mysql_fetch_array($hasil);
            $no_dokumen = $data['maxKode'];
            $noUrut = (string) substr($no_dokumen, 1, 4);
            $noUrut++;
            $hasilkode = sprintf("A%03s", $noUrut);
            ?>
            <div class="main_content">
                <div class="left_content">
                    <h2>FORMULIR PENGADUAN PELANGGARAN</h2>
                    <form class="form-horizontal" id="form_sample_1" enctype="multipart/form-data" method="POST" action="act_pengaduan.php" onSubmit="return validasi(this)">
                        <div class="contact_form">
                         <div class="form_row">
                                <label class="contact"  style="color:black;">UPTD Yang Melakukan </label>
                                <div class="controls">
                                    <select class="contact_input" name="kategori" required="required" style="width:400px;height:40px;" onChange="document.location.href=this.options[this.selectedIndex].value;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option value="0">- Pilih -</option>

                                            <option style="color:red;" value="pelaporan.php?id=1" <?php if($p=='1'){echo 'selected';} ?>>UPTD KOTA JAMBI</option>
                                            <option style="color:red;" value="pelaporan.php?id=2" <?php if($p=='2'){echo 'selected';} ?>>UPTD BATANGHARI</option>
                                            <option style="color:red;" value="pelaporan.php?id=3" <?php if($p=='3'){echo 'selected';} ?>>UPTD TANJAB BARAT</option>
                                             <option style="color:red;" value="pelaporan.php?id=4" <?php if($p=='4'){echo 'selected';} ?>>UPTD MERANGIN</option>
                                              <option style="color:red;" value="pelaporan.php?id=5" <?php if($p=='5'){echo 'selected';} ?>>UPTD BUNGO</option>
                                             <option style="color:red;" value="pelaporan.php?id=6" <?php if($p=='6'){echo 'selected';} ?>>UPTD KERINCI</option>
                                              <option style="color:red;" value="pelaporan.php?id=7" <?php if($p=='7'){echo 'selected';} ?>>UPTD TANJAB TIMUR</option>
                                             <option style="color:red;" value="pelaporan.php?id=8" <?php if($p=='8'){echo 'selected';} ?>>UPTD MUARO JAMBI</option>
                                              <option style="color:red;" value="pelaporan.php?id=9" <?php if($p=='9'){echo 'selected';} ?>>UPTD SAROLANGUN</option>
                                               <option style="color:red;" value="pelaporan.php?id=10" <?php if($p=='10'){echo 'selected';} ?>>UPTD TEBO</option>
                                        </select>
                                </div>
                            </div> 
                            <?php if ($_GET['id'] == '0')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                    <select class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>- Pilih -</option>
                                                                                       
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                              <?php if ($_GET['id'] == '1')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD KOTA JAMBI" />
                                    <select class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Kota Jambi</option>
                                            <option>Drivethru Kota Jambi</option>
                                            <option>Gerai Jamtos</option>
                                            <option>Gerai WTC</option>
                                            <option>Gerai Transmart</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                                    <?php if ($_GET['id'] == '2')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD BATANGHARI" />
                                    <select  class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Batanghari</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                                    <?php if ($_GET['id'] == '3')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD TANJAB BARAT" />
                                    <select  class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Tanjab Barat</option>
                                            <option>Pos Merlung</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                                      <?php if ($_GET['id'] == '4')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD MERANGIN" />
                                    <select  class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Merangin</option>
                                            <option>Pos Rantau Panjang</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                                      <?php if ($_GET['id'] == '5')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD BUNGO" />
                                    <select  class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Bungo</option>
                                            <option>Pos Kuamang Kuning</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                                      <?php if ($_GET['id'] == '6')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD KERINCI" />
                                    <select  class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Kerinci</option>
                                            <option>Pos Sei Penuh</option>
                                            <option>Pos Kayu Aro</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                                      <?php if ($_GET['id'] == '7')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD TANJAB TIMUR" />
                                    <select  class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Tanjab TImur</option>
                                            <option>Pos Rantau Rasau</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                                      <?php if ($_GET['id'] == '8')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD MUARO JAMBI" />
                                    <select  class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Muaro Jambi</option>
                                            <option>Pos Sei Bahar</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                                      <?php if ($_GET['id'] == '9')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD SAROLANGUN" />
                                    <select  class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Sarolangun</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                                      <?php if ($_GET['id'] == '10')
                                    {?>

                                    <div class="form_row">
                                <label class="contact"  style="color:black;">Lokasi Kejadian </label>
                                <div class="controls">
                                <input type="hidden" class="contact_input" name="uptd" required="required" value="UPTD TEBO" />
                                    <select  class="contact_input" name="lokasi" required="required" style="width:400px;height:40px;">     
                                           <?php $p = $_GET['id']; ?>
                                            <option>Samsat Tebo</option>
                                            <option>Pos Rimbo Bujang</option>
                                            <option>Samsat Keliling</option>
                                            
                                        </select>
                                </div>
                            </div> 

                                    <?php } ?>
                           <div class="form_row">
                                <label class="contact"  style="color:black;">Nomor Referensi</label>
                                <input type="text" class="contact_input" name="nomor_referensi" value="<?php echo $hasilkode; ?>" readonly="readonly"/>
                            </div>
                            <div class="form_row">
                                <h4><font color="black"><b>IDENTITAS PELAPOR</b></font></h4> 
                            </div>
                            <div class="form_row" >
                                <label class="contact"  style="color:black;">Nama Pelapor <font color="red">*</font></label>
                                <input type="text" class="contact_input" name="nama_pelapor" required="required"/>
                            </div>
                            <div class="form_row">
                                <label class="contact"  style="color:black;">Nomor Telepon <font color="red">*</font></label>
                                <input type="text" onkeypress="return hanyaAngka(event, false)" class="contact_input" name="nomor_telepon"/>
                            </div>
                            <div class="form_row">
                                <label class="contact"  style="color:black;">Email <font color="red">*</font></label>
                                <input type="email" class="contact_input" name="email"/>
                            </div>
                            <div class="form_row">
                                <h4><font color="black"><b>TERLAPOR</b></font></h4> 
                            </div>
                            <div class="form_row">
                                <label class="contact"  style="color:black;">Nama Terlapor <font color="red">*</font></label>
                                <input type="text" class="contact_input" name="nama_pelaku" required="required"/>
                            </div>
                            <div class="form_row">
                                <label class="contact"  style="color:black;">Jenis Pelanggaran <font color="red">*</font></label>
                                <div class="controls">
                                    <select class="contact_input" name="id_pelanggaran" required="required" style="width:400px;height:40px;">
                                        <option value="" selected> -- </option>
                                        <?php
                                        $query2 = mysql_query("select * from tbl_pelanggaran");
                                        while ($resqry2 = mysql_fetch_row($query2)) {
                                            echo'<option value="' . $resqry2[0] . '">' . $resqry2[1] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                            <div class="form_row" >
                                <label class="contact"  style="color:black;">Uraian Pelanggaran <font color="red">*</font></label>
                                <textarea class="contact_textarea" rows="" cols="" name="uraian_pelanggaran" required="required"></textarea>
                            </div>
                            <div class="form_row">
                                <label class="contact"  style="color:black;">Waktu kejadian <font color="red">*</font></label>
                                <input type="text" class="contact_input" name="waktu_kejadian" id="input" required="required"/>
                            </div>
                            <div class="form_row">
                                <label class="contact"  style="color:black;">Kronologis Permasalahan <font color="red">*</font></label>
                                <textarea class="contact_textarea" rows="" cols="" name="kronologis_permasalahan" required="required"></textarea>
                            </div>
                            <div class="form_row">
                                <label class="contact"  style="color:black;">Unggah File <font color="red">*</font></label>
                                <input type="file" class="contact_input" name="unggah_file" >
                                <div><br / ><br / ><br / ><label><font size="3" style="color:black; margin-left:235px;">format file : *.jpg,*.gif,*.doc,*.pdf,*.docx,*.xls,*.xlsx,*.ppt,*.pptx,*.jpeg,*.png (Maks : 2 MB)</font></label></div>       
                            </div>
                            <div class="form_row">
                                <label class="contact"  style="color:black;"> Captcha</label>
                                <img src="gambar.php"><br><br>
                                <label class="contact" ></label>
                                <input name="captcha" value="" maxlength="6" class="contact_input"/>  
                            </div> 
                            <div class="form_row">
                                <input type="image" src="images/send.gif" class="send"/>
                            </div>
                        </div>
                    </form>      
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
                <div class="footer_copyrights"><a href="http://bankjambi.co.id"><img src="images/logo.png" alt="" border="0" width="150px" height="40px"/></a></div>
            </div>
            <!--End of footer-->
        </div>
        <!--End of wrap-->
        <div align=center>Test - <a href='#'>Testing</a></div></body>
    </body>
</html>
