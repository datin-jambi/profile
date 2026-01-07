<?php
$action = "menu/mn_beranda/act_beranda.php";
switch ($_GET['act']) {
    default:
        echo'<div class="row-fluid">
            	<div class="span12">
            		<div class="widget-box">
                    	<div class="widget-title"> 
                        	<span class="icon"> 
                            	<i class="icon-user-md"></i> 
                        	</span>
                        	<h5>Management Beranda</h5>
                        	<span class="label label-info">
	                        	<a title="Tambah Data" data-placement="bottom" data-toggle="tooltip" href="?menu=beranda&act=updtberanda&id_beranda=0">
                                <button type="submit" class="btn"><i class="icon icon-plus"></i></button>
		                       	</a>
	                       	</span>
                    	</div>
               <div class="widget-content nopadding">
                    		<table class="table table-bordered data-table">
				        <thead>
				            <tr>
		                                <th>No</th>
		                                <th>Nama Beranda</th>
		                                <th>Isi Beranda</th>
		                                <th>Action</th>
		                            </tr>
		                        </thead>
		   ';
        $no = 1;
        $qry = mysql_query("SELECT * FROM tbl_beranda");
        while ($result = mysql_fetch_array($qry)) {
            $replace1=  str_replace("&lt;", "<", $result['isi_beranda']);
            $replace2=  str_replace("&gt;", ">", $replace1);
            
            echo'<tr>
					              	<td>' . $no . '</td>
					               	<td>' . $result['nama_beranda'] . '</td>
                                                        
					               	<td>' . $replace2 . '</td>
					                <td>
                                                            <div class="btn-group">	
					                   	<a title="Edit Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-success" href="?menu=beranda&act=updtberanda&id_beranda=' . $result['id_beranda'] . '">
                                                                    <i class="icon-edit icon-white"></i>
					                        </a>
					                   	<a title="Hapus Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-danger" onclick="return confirm(`APAKAH ANDA YAKIN UNTUK HAPUS DATA BERANDA ?`)" href="' . $action . '?menu=beranda&act=dltberanda&id_beranda=' . $result['id_beranda'] . '">
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
    case 'updtberanda':
        $id_beranda = $_GET['id_beranda'];
        if ($id_beranda != 0) {
            $qry2 = mysql_query("SELECT * FROM tbl_beranda WHERE id_beranda = $id_beranda");
            $result2 = mysql_fetch_array($qry2);
            $nama_beranda = $result2['nama_beranda'];
            $isi_beranda = $result2['isi_beranda'];
        }
        echo'<div class="row-fluid">
                            <div class="span12">
                                <div class="widget-box">
                                    <div class="widget-title"> 
                                        <span class="icon"> 
                                            <i class="icon-key"></i> 
                                                </span>
                                                    <h5>Management Beranda</h5>
            		</div>
                    	<div class="widget-content nopadding">
		                        <form class="form-horizontal" id="form_sample_1" method="POST" action="' . $action . '?menu=beranda&act=updtberanda&id_beranda=' . $id_beranda . '" onSubmit="return validasi(this)">
                                            <fieldset>
                                            <div class="alert alert-error hide">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <h4>Error !!!</h4>
		                                You have some form errors. Please check below.
		                            </div>
                                           <div class="control-group">
		                                <label class="control-label">Nama Beranda <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input type="text" id="nama_beranda" name="nama_beranda" value="' . $nama_beranda . '" data-required="1" class="span12 m-wrap"/>
						</div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Nama Beranda <span class="required">*</span></label>
                                                <div class="controls">
                                                    <textarea class="textarea_editor span12" name="isi_beranda" rows="6" placeholder="Enter text ...">'.$isi_beranda.'</textarea>
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