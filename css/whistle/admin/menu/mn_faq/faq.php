<?php

$action = "menu/mn_faq/act_faq.php";
switch ($_GET['act']) {
    default:
        echo'<div class="row-fluid">
            	<div class="span12">
            		<div class="widget-box">
                    	<div class="widget-title"> 
                        	<span class="icon"> 
                            	<i class="icon-user-md"></i> 
                        	</span>
                        	<h5>Management FAQ</h5>
                        	<span class="label label-info">
	                        	<a title="Tambah Data" data-placement="bottom" data-toggle="tooltip" href="?menu=faq&act=updtfaq&id_faq=0">
                                <button type="submit" class="btn"><i class="icon icon-plus"></i></button>
		                       	</a>
	                       	</span>
                    	</div>
               <div class="widget-content nopadding">
                    		<table class="table table-bordered data-table">
				        <thead>
				            <tr>
		                                <th>No</th>
		                                <th>Nama Faq</th>
		                                <th>Isi Faq</th>
		                                <th>Action</th>
		                            </tr>
		                        </thead>
		   ';
        $no = 1;
        $qry = mysql_query("SELECT * FROM tbl_faq");
        while ($result = mysql_fetch_array($qry)) {
            echo'<tr>
					              	<td>' . $no . '</td>
					               	<td>' . $result['nama_faq'] . '</td>
					               	<td>' . $result['isi_faq'] . '</td>
					                <td>
                                                            <div class="btn-group">	
					                   	<a title="Edit Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-success" href="?menu=faq&act=updtfaq&id_faq=' . $result['id_faq'] . '">
                                                                    <i class="icon-edit icon-white"></i>
					                        </a>
					                   	<a title="Hapus Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-danger" onclick="return confirm(`APAKAH ANDA YAKIN UNTUK HAPUS DATA FAQ ?`)" href="' . $action . '?menu=faq&act=dltfaq&id_faq=' . $result['id_faq'] . '">
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
    case 'updtfaq':
        $id_faq= $_GET['id_faq'];
        if ($id_faq != 0) {
            $qry2 = mysql_query("SELECT * FROM tbl_faq WHERE id_faq = $id_faq");
            $result2 = mysql_fetch_array($qry2);
            $nama_faq = $result2['nama_faq'];
            $isi_faq = $result2['isi_faq'];
        }
        echo'<div class="row-fluid">
                            <div class="span12">
                                <div class="widget-box">
                                    <div class="widget-title"> 
                                        <span class="icon"> 
                                            <i class="icon-key"></i> 
                                                </span>
                                                    <h5>Management Faq</h5>
            		</div>
                    	<div class="widget-content nopadding">
		                        <form class="form-horizontal" id="form_sample_1" method="POST" action="' . $action . '?menu=faq&act=updtfaq&id_faq=' . $id_faq . '" onSubmit="return validasi(this)">
                                            <fieldset>
                                            <div class="alert alert-error hide">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <h4>Error !!!</h4>
		                                You have some form errors. Please check below.
		                            </div>
                                           <div class="control-group">
		                                <label class="control-label">Nama Faq <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input type="text" id="nama_faq" name="nama_faq" value="' . $nama_faq . '" data-required="1" class="span12 m-wrap"/>
						</div>
                                            </div>
                                           <div class="control-group">
		                                <label class="control-label">Isi Faq <span class="required">*</span></label>
                                                <div class="controls">
                                                    <textarea class="textarea_editor span12" name="isi_faq" data-required="1" class="span12 m-wrap"/>' . $isi_faq . '</textarea>
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
<script src="js/wysihtml5-0.3.0.js"></script> 
<script src="js/bootstrap-wysihtml5.js"></script> 
<script>
    $('.textarea_editor').wysihtml5();
</script>