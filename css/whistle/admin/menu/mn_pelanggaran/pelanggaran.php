<?php

$action = "menu/mn_pelanggaran/act_pelanggaran.php";
switch ($_GET['act']) {
    default:
        echo'<div class="row-fluid">
            	<div class="span12">
            		<div class="widget-box">
                    	<div class="widget-title"> 
                        	<span class="icon"> 
                            	<i class="icon-user-md"></i> 
                        	</span>
                        	<h5>Management Pelanggran</h5>
                        	<span class="label label-info">
	                        	<a title="Tambah Data" data-placement="bottom" data-toggle="tooltip" href="?menu=pelanggaran&act=updtpelanggaran&id_pelanggaran=0">
                                <button type="submit" class="btn"><i class="icon icon-plus"></i></button>
		                       	</a>
	                       	</span>
                    	</div>
               <div class="widget-content nopadding">
                    		<table class="table table-bordered data-table">
				        <thead>
				            <tr>
		                                <th>No</th>
		                                <th>Jenis Pelanggran</th>
		                                <th>Action</th>
		                            </tr>
		                        </thead>
		   ';
        $no = 1;
        $qry = mysql_query("SELECT * FROM tbl_pelanggaran");
        while ($result = mysql_fetch_array($qry)) {
            echo'<tr>
					              	<td>' . $no . '</td>
					               	<td>' . $result['jenis_pelanggaran'] . '</td>
					                <td>
                                                            <div class="btn-group">	
					                   	<a title="Edit Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-success" href="?menu=pelanggaran&act=updtpelanggaran&id_pelanggaran=' . $result['id_pelanggaran'] . '">
                                                                    <i class="icon-edit icon-white"></i>
					                        </a>
					                   	<a title="Hapus Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-danger" onclick="return confirm(`APAKAH ANDA YAKIN UNTUK HAPUS DATA MENU ?`)" href="' . $action . '?menu=pelanggaran&act=dltpelanggaran&id_pelanggaran=' . $result['id_pelanggaran'] . '">
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