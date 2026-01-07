<?php

$action = "menu/mn_perlindungan/act_perlindungan.php";
switch ($_GET['act']) {
    default:
        echo'<div class="row-fluid">
            	<div class="span12">
            		<div class="widget-box">
                    	<div class="widget-title"> 
                        	<span class="icon"> 
                            	<i class="icon-user-md"></i> 
                        	</span>
                        	<h5>Management Perlindungan</h5>
                        	<span class="label label-info">
	                        	<a title="Tambah Data" data-placement="bottom" data-toggle="tooltip" href="?menu=perlindungan&act=updtperlindungan&id_perlindungan=0">
                                <button type="submit" class="btn"><i class="icon icon-plus"></i></button>
		                       	</a>
	                       	</span>
                    	</div>
               <div class="widget-content nopadding">
                    		<table class="table table-bordered data-table">
				        <thead>
				            <tr>
		                                <th>No</th>
		                                <th>Nama Perlindungan</th>
		                                <th>Isi Perlindungan</th>
		                                <th>Action</th>
		                            </tr>
		                        </thead>
		   ';
        $no = 1;
        $qry = mysql_query("SELECT * FROM tbl_perlindungan");
        while ($result = mysql_fetch_array($qry)) {
            echo'<tr>
					              	<td>' . $no . '</td>
					               	<td>' . $result['nama_perlindungan'] . '</td>
					               	<td>' . $result['isi_perlindungan'] . '</td>
					                <td>
                                                            <div class="btn-group">	
					                   	<a title="Edit Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-success" href="?menu=perlindungan&act=updtperlindungan&id_perlindungan=' . $result['id_perlindungan'] . '">
                                                                    <i class="icon-edit icon-white"></i>
					                        </a>
					                   	<a title="Hapus Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-danger" onclick="return confirm(`APAKAH ANDA YAKIN UNTUK HAPUS DATA PERLINDUNGAN ?`)" href="' . $action . '?menu=perlindungan&act=dltperlindungan&id_perlindungan=' . $result['id_perlindungan'] . '">
                                                                    <i class="icon-remove icon-white"></i>
                                                                </a>
							    </div>
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
    case 'updtperlindungan':
        $id_perlindungan= $_GET['id_perlindungan'];
        if ($id_perlindungan != 0) {
            $qry2 = mysql_query("SELECT * FROM tbl_perlindungan WHERE id_perlindungan = $id_perlindungan");
            $result2 = mysql_fetch_array($qry2);
            $nama_perlindungan = $result2['nama_perlindungan'];
            $isi_perlindungan = $result2['isi_perlindungan'];
        }
        echo'<div class="row-fluid">
                            <div class="span12">
                                <div class="widget-box">
                                    <div class="widget-title"> 
                                        <span class="icon"> 
                                            <i class="icon-key"></i> 
                                                </span>
                                                    <h5>Management Perlindungan</h5>
            		</div>
                    	<div class="widget-content nopadding">
		                        <form class="form-horizontal" id="form_sample_1" method="POST" action="' . $action . '?menu=perlindungan&act=updtperlindungan&id_perlindungan=' . $id_perlindungan . '" onSubmit="return validasi(this)">
                                            <fieldset>
                                            <div class="alert alert-error hide">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <h4>Error !!!</h4>
		                                You have some form errors. Please check below.
		                            </div>
                                           <div class="control-group">
		                                <label class="control-label">Nama Perlindungan <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input type="text" id="nama_perlindungan" name="nama_perlindungan" value="' . $nama_perlindungan . '" data-required="1" class="span12 m-wrap"/>
						</div>
                                            </div>
                                           <div class="control-group">
		                                <label class="control-label">Isi Perlindungan <span class="required">*</span></label>
                                                <div class="controls">
                                                    <textarea id="isi_perlindungan" name="isi_perlindungan" data-required="1" class="span12 m-wrap"/>' . $isi_perlindungan . '</textarea>
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