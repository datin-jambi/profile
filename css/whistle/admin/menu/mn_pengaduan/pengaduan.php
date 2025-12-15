<?php

$action = "menu/mn_pengaduan/act_pengaduan.php";
$action1 = "menu/mn_pengaduan/report_pengaduan.php";
switch ($_GET['act']) {
    default:
        echo'<div class="row-fluid">
            	<div class="span12">
            		<div class="widget-box">
                    	<div class="widget-title"> 
                        	<span class="icon"> 
                            	<i class="icon-user-md"></i> 
                        	</span>
                        	<h5>Pengaduan Pelanggran</h5>
                        	<span class="label label-info">
	                        	<a title="Tambah Data" data-placement="bottom" data-toggle="tooltip" href="?menu=pengaduan&act=updtpengaduan&id_pengaduan=0">
                                <button type="submit" class="btn"><i class="icon icon-plus"></i></button>
		                       	</a>
	                       	</span>
                    	</div>
               <div class="widget-content nopadding">
                    		<table class="table table-bordered data-table">
				        <thead>
				           <tr>
                                                <th rowspan="2" bgcolor="#D3D3D3"><div align="center">No</div></th>
                                                <th rowspan="2" rowspan="2" bgcolor="#D3D3D3"><div align="center">Nomor Referensi</div></th>
                                                <th rowspan="2" bgcolor="#D3D3D3"><div align="center">Nama Pelaku Pelanggaran</div></th>
                                                <th rowspan="2" bgcolor="#D3D3D3"><div align="center">Jenis Pelanggaran</div></th>
                                                <th colspan="3" bgcolor="#D3D3D3"><div align="center">Tindak Lanjut</div></th>
                                                <th rowspan="2" bgcolor="#D3D3D3"><div align="center">File</div></th>
                                                <th rowspan="2" bgcolor="#D3D3D3"><div align="center">Action</div></th>
                                            </tr>
                                            <tr>
                                                <th bgcolor="#D3D3D3"><div align="center">Dalam Proses</div></th>
                                                <th bgcolor="#D3D3D3"><div align="center">Di Tindaklanjuti</div></th>
                                                <th bgcolor="#D3D3D3"><div align="center">Tidak Diproses</th>
                                            </tr>
		                        </thead>
		   ';
        $no = 1;
        $qry = mysql_query("SELECT tbl_pengaduan_pelanggaran.*, tbl_pelanggaran.jenis_pelanggaran FROM tbl_pengaduan_pelanggaran join tbl_pelanggaran on tbl_pengaduan_pelanggaran.id_pelanggaran=tbl_pelanggaran.id_pelanggaran AND tbl_pengaduan_pelanggaran.tempat_terjadi='$_SESSION[uptd]' order by tbl_pengaduan_pelanggaran.id_pengaduan_pelanggaran desc");
        while ($result = mysql_fetch_array($qry)) {
                                             echo'<tr>                              
                                                    
					              	<td>' . $no . '</td>
					               	<td>' . $result['nomor_referensi'] . '</td>
					               	<td>' . $result['nama_pelaku'] . '</td>
					               	<td>' . $result['jenis_pelanggaran'] . '</td>';
                                                     if($result['tindak_lanjut1']=='Dalam Proses'){
                                                         echo ' <td>
                                                                    <center> <i class="icon-ok" style="color: black;"></i></center>
                                                                </td>'; 
                                                        }else{
                                                          echo '<td>
                                                                    <div class="">	
                                                                        <a title="Dalam Proses" data-placement="bottom" data-toggle="tooltip" href="' . $action . '?menu=pengaduan&act=updtpengaduan&id_pengaduan_pelanggaran=' . $result['id_pengaduan_pelanggaran'] . '&check1=DalamProses">
                                                                            <center> <i class="icon-sign-blank" style="color: black;"></i></center>
                                                                        </a>
                                                                    </div>
                                                                </td>'; 
                                                        }        
                                                        if($result['tindak_lanjut2']=='Ditindaklanjuti'){
                                                           echo ' <td>
                                                                    <center> <i class="icon-ok" style="color: black;"></i></center>
                                                                </td>'; 
                                                        }else{
                                                         echo '<td>
                                                                    <div class="">	
                                                                        <a title="Ditindaklanjuti" data-placement="bottom" data-toggle="tooltip" href="' . $action . '?menu=pengaduan&act=updtpengaduan&id_pengaduan_pelanggaran=' . $result['id_pengaduan_pelanggaran'] . '&check2=Ditindaklanjuti">
                                                                            <center> <i class="icon-sign-blank" style="color: black;"></i></center>
                                                                        </a>
                                                                    </div>
                                                                </td>'; 
                                                        }        
                                                        if($result['tindak_lanjut3']=='Tidak Diproses'){
                                                           echo ' <td>
                                                                    <center> <i class="icon-ok" style="color: black;"></i></center>
                                                                </td>';  
                                                        }else{
                                                          echo '<td>
                                                                    <div class="">	
                                                                        <a title="Tidak Diproses" data-placement="bottom" data-toggle="tooltip" href="' . $action . '?menu=pengaduan&act=updtpengaduan&id_pengaduan_pelanggaran=' . $result['id_pengaduan_pelanggaran'] . '&check3=TidakDiproses">
                                                                            <center> <i class="icon-sign-blank" style="color: black;"></i></center>
                                                                        </a>
                                                                    </div>
                                                                </td>'; 
                                                        } 
                                                        echo'<td><a href="' . $action . '?menu=pengaduan&act=downloadpengaduan&id_pengaduan_pelanggaran=' . $result['id_pengaduan_pelanggaran'] . '">
                                                            <font color="black">  ' . $result['unggah_file'] . '</font>
                                                            </a>
                                                        </td>
					                <td>
                                                            <div class="">	
					                   	<a title="Cetak Data" data-placement="bottom" data-toggle="tooltip" target=_blank  href="'.$action1.'?id_pengaduan_pelanggaran=' . $result['id_pengaduan_pelanggaran'] . '">
                                                                    <i class="icon-print" style="color: black;"> Cetak Data</i>
					                        </a>';
                                                        if($_SESSION['level']=='Superadmin'){        
                                                            echo'    <a title="Hapus Data" data-placement="bottom" data-toggle="tooltip" onclick="return confirm(`APAKAH ANDA YAKIN UNTUK HAPUS DATA PENGADUAN ?`)" href="' . $action . '?menu=pengaduan&act=dltpengaduan&id_pengaduan_pelanggaran=' . $result['id_pengaduan_pelanggaran'] . '">
                                                                    <i class="icon-remove icon-white"  style="color: black;"> Delete</i>
                                                                </a>';
                                                        }        
							 echo'   </div>
							</td>
                                                    </tr>
					           ';
            $no++;
        }
        echo'				</table>
	                	</div>
	                </div>
	            </div>
	        </div>
	       ';
        break;
    case 'updtpelanggaran':
        $id_pelanggaran = $_GET['id_pelanggaran'];
        if ($id_pelanggaran != 0) {
            $qry2 = mysql_query("SELECT * FROM tbl_pelanggaran WHERE id_pelanggaran = $id_pelanggaran");
            $result2 = mysql_fetch_array($qry2);
            $jenis_pelanggaran = $result2['jenis_pelanggaran'];
        }
        echo'<div class="row-fluid">
                            <div class="span12">
                                <div class="widget-box">
                                    <div class="widget-title"> 
                                        <span class="icon"> 
                                            <i class="icon-key"></i> 
                                                </span>
                                                    <h5>Management Pelanggaran</h5>
            		</div>
                    	<div class="widget-content nopadding">
		                        <form class="form-horizontal" id="form_sample_1" method="POST" action="' . $action . '?menu=pelanggaran&act=updtpelanggaran&id_pelanggaran=' . $id_pelanggaran . '" onSubmit="return validasi(this)">
                                            <fieldset>
                                            <div class="alert alert-error hide">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <h4>Error !!!</h4>
		                                You have some form errors. Please check below.
		                            </div>
                                           <div class="control-group">
		                                <label class="control-label">Jenis Pelanggaran <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input type="text" id="jenis_pelanggaran" name="jenis_pelanggaran" value="' . $jenis_pelanggaran . '" data-required="1" class="span12 m-wrap"/>
						</div>
                                            </div>
                                            <div class="form-actions">
				                <button type="submit" class="btn btn-primary">Submit</button>
				                <button class="btn btn-default" type="button" onClick="history.go(-1);">Cancel</button>
				            </div>
                                          </fieldset>
		                        </form>
		                    </div>
		                </div>
		            </div>
		       ';
        break;
}
?>
<script>
$(document).ready(function(){
    $("#formname").on("change", "input:checkbox", function(){
        $("#formname").submit();
    });
});
</script>