<?php

$action = "menu/mn_management_settings/act_mgmtbrnch.php";
switch ($_GET['act']) {
    default:
        echo'<div class="row-fluid">
            	<div class="span12">
            		<div class="widget-box">
                    	<div class="widget-title"> 
                        	<span class="icon"> 
                            	<i class="icon-user-md"></i> 
                        	</span>
                        	<h5>Management Branch</h5>
                        	<span class="label label-info">
	                        	<a title="Tambah Data" data-placement="bottom" data-toggle="tooltip" href="?menu=mgmtbrnch&act=updtbrnch&id=0">
                                <button type="submit" class="btn"><i class="icon icon-plus"></i></button>
		                       	</a>
	                       	</span>
                    	</div>
               <div class="widget-content nopadding">
                    		<table class="table table-bordered data-table">
				        <thead>
				            <tr>
		                                <th>NO</th>
		                                <th>Branch ID</th>
		                                <th>Branch Name</th>
		                                <th>Parent Code of Branch</th>
		                                <th>Action</th>
		                            </tr>
		                        </thead>
		   ';
        $no = 1;
        $qry = mysql_query("SELECT * FROM tbl_cabang ORDER BY kode_cabang,kode_cabang_induk");
        while ($result = mysql_fetch_array($qry)) {
            echo'<tr>
					              	<td>' . $no . '</td>
					               	<td>' . $result['kode_cabang'] . '</td>
					                <td>' . $result['nama_cabang'] . '</td>
					                <td>' . $result['kode_cabang_induk'] . '</td>
					                <td>
                                                            <div class="btn-group">	
					                   	<a title="Edit Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-success" href="?menu=mgmtbrnch&act=updtbrnch&id=' . $result['id'] . '">
                                                                    <i class="icon-edit icon-white"></i>
					                        </a>
					                   	<a title="Hapus Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-danger" onclick="return confirm(`APAKAH ANDA YAKIN UNTUK HAPUS DATA MENU ?`)" href="' . $action . '?menu=mgmtbrnch&act=dltbrnch&id=' . $result['id'] . '">
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
    case 'updtbrnch':
        $id = $_GET['id'];
        if ($id != 0) {
            $qry2 = mysql_query("SELECT * FROM tbl_cabang WHERE id = $id");
            $result2 = mysql_fetch_array($qry2);
            $kodecabang = $result2['kode_cabang'];
            $namacabang = $result2['nama_cabang'];
            $kodecabanginduk = $result2['kode_cabang_induk'];
        }
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
		                        <form class="form-horizontal" id="form_sample_1" method="POST" action="' . $action . '?menu=mgmtbrnch&act=updtbrnch&id=' . $id . '" onSubmit="return validasi(this)">
                                            <fieldset>
                                            <div class="alert alert-error hide">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <h4>Error !!!</h4>
		                                You have some form errors. Please check below.
		                            </div>
                                           <div class="control-group">
		                                <label class="control-label">Branch ID <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input type="text" id="kodecabang" name="kodecabang" value="' . $kodecabang . '" data-required="1" class="span12 m-wrap"/>
						</div>
                                            </div>
                                            <div class="control-group">
		                                <label class="control-label">Branch Name <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input type="text" id="namacabang" name="namacabang" value="' . $namacabang . '" data-required="1" class="span12 m-wrap"/>
                                                </div>
		                            </div>
		                            <div class="control-group">
		                                <label class="control-label">Parent Code Of Branch <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input type="text" id="kodecabanginduk" name="kodecabanginduk" value="' . $kodecabanginduk . '" data-required="1" class="span12 m-wrap"/>
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