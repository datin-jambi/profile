<?php
$action = "menu/mn_management_settings/act_mgmtbrnch.php";
echo'<div class="row-fluid">
                            <div class="span12">
                                <div class="widget-box">
                                    <div class="widget-title"> 
                                        <span class="icon"> 
                                            <i class="icon-key"></i> 
                                                </span>
                                                    <h5>Management Branch</h5>
            		</div>
                    	<div class="widget-content nopadding">
		                        <form class="form-horizontal" id="form_sample_1" method="POST" action="" onSubmit="return validasi(this)">
                                            <fieldset>
                                            <div class="alert alert-error hide">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <h4>Error !!!</h4>
		                                You have some form errors. Please check below.
		                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Date picker (dd-mm)</label>
                                                <div class="controls">
                                                    <input type="text" data-date="' . date('yyyy-mm-dd') . '" data-date-format="yyyy-mm-dd" name="tanggal" class="datepicker span11" readonly="readonly">
                                                <span class="help-block">Date with Formate of  (dd-mm-yy)</span> </div>
                                            </div>
                                            <div class="form-actions">
				                <button type="submit" name=cari class="btn btn-primary">Submit</button>
				                <button class="btn btn-default" type="button" onClick="history.go(-1);">Cancel</button>
				            </div>
                                          </fieldset>
		                        </form>
		                    </div>
		                </div>
			       <div class="grafik" style="width:100%; height:400px;"></div><br><br><br>
                             <div class="grafik2" style="width:100%; height:400px;"></div>
		        </div>
		       ';
if (isset($_POST['cari'])) {

    $tanggal = substr($_POST['tanggal'], 0, 7);
    ?>
    <script type="text/javascript">
        $('.grafik').highcharts({
        chart: {
        type: 'column',
                marginTop: 80
        },
                credits: {
                enabled: false
                },
                tooltip: {
                shared: true,
                        crosshairs: true,
                        headerFormat: ' < b > {point.key} < /b>< br / > '
                },
                title: {
                text: 'JUMLAH PENGADUAN PELANGGARAN'
                },
                subtitle: {
                text: ' <?PHP echo $tanggal; ?> '
                },
                xAxis: {
                categories: [''],
                        labels: {
                        rotation: 0,
                                align: 'right',
                                style: {
                                fontSize: '10px',
                                        fontFamily: 'Verdana, sans - serif'
                                }
                        }
                },
                legend: {
                enabled: true
                },
                series: [
    <?php
    $query = mysql_query("select jenis_pelanggaran, waktu_kejadian, count(id_pelanggaran) as jumlah from  v_pelanggaran where Substring(waktu_kejadian, 1,7)= '$tanggal' and tempat_terjadi='$_SESSION[uptd]' group by jenis_pelanggaran ");
    while ($row = mysql_fetch_array($query)) {
        ?>
                    {
                    name: '<?php echo $row[jenis_pelanggaran]; ?>',
                            data: [<?php echo $row[jumlah]; ?>]
                    },
        <?php
    }
    ?>]
        })
    </script>


    <script type="text/javascript">
                $('.grafik2').highcharts({
        chart: {
        type: 'column',
                marginTop: 80
        },
                credits: {
                enabled: false
                },
                tooltip: {
                shared: true,
                        crosshairs: true,
                        headerFormat: ' < b > {point.key} < /b>< br / > '
                },
                title: {
                text: 'JUMLAH TINDAK LANJUT'
                },
                subtitle: {
                text: ' <?PHP echo $tanggal; ?> '
                },
                xAxis: {
                categories: [''],
                        labels: {
                        rotation: 0,
                                align: 'right',
                                style: {
                                fontSize: '10px',
                                        fontFamily: 'Verdana, sans - serif'
                                }
                        }
                },
                legend: {
                enabled: true
                },
                series: [
    <?php
    $query = mysql_query("SELECT waktu_kejadian, tindak_lanjut1, count(tindak_lanjut1) as jumlah FROM `v_pelanggaran` where Substring(waktu_kejadian, 1,7)= '$tanggal' and tempat_terjadi='$_SESSION[uptd]' group by tindak_lanjut1
                            union all 
                            SELECT waktu_kejadian, tindak_lanjut2,  count(tindak_lanjut2) as jumlah FROM `v_pelanggaran` where Substring(waktu_kejadian, 1,7)= '$tanggal' and tempat_terjadi='$_SESSION[uptd]' group by tindak_lanjut2
                            union all  
                            SELECT waktu_kejadian, tindak_lanjut3, count(tindak_lanjut3) as jumlah FROM `v_pelanggaran` where Substring(waktu_kejadian, 1,7)= '$tanggal' and tempat_terjadi='$_SESSION[uptd]' group by tindak_lanjut3");
    while ($row = mysql_fetch_array($query)) {
        if($row['tindak_lanjut1'] != '' or $row['tindak_lanjut1'] != 0)
         { ?>
                    {
                            name: '<?php   echo $row[tindak_lanjut1]; ?>',
                            data: [<?php echo $row[jumlah]; ?>]
                    },
        <?php
    }
    
         }
    ?>]
        })
    </script>

<?PHP } ?>    
