<?php
include_once("lib.php");
$action = "menu/mn_rumah/act_rumah.php";
switch ($_GET['act']) {
    default:

            $qry2 = mysql_query("SELECT * FROM tbl_rumah");
            $result2 = mysql_fetch_array($qry2);
            $id_rumah = $result2['id_rumah'];
            $nama_rumah = $result2['nama_rumah'];
            $isi_rumah = $result2['isi_rumah'];
        echo'<div class="row-fluid">
                            <div class="span12">
                                <div class="widget-box">
                                    <div class="widget-title"> 
                                        <span class="icon"> 
                                            <i class="icon-key"></i> 
                                                </span>
                                                    <h5>Management Home</h5>
            		</div>
                    	<div class="widget-content nopadding">
		                        <form class="form-horizontal" id="form_sample_1" method="POST" action="' . $action . '?menu=rumah&act=updtrumah&id_rumah=' . $id_rumah . '" onSubmit="return validasi(this)">
                                            <fieldset>
                                            <div class="alert alert-error hide">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <h4>Error !!!</h4>
		                                You have some form errors. Please check below.
		                            </div>
                                           <div class="control-group">
		                                <label class="control-label">Nama Home <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input type="text" id="nama_beranda" name="nama_rumah" value="' . $nama_rumah. '" data-required="1" class="span12 m-wrap"/>
						</div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="textarea2">Isi Home </label>
                                                <div class="controls">
                                                    <textarea class="textarea_editor span12" id="isi_home" name="isi_rumah" style="width: 1425px; height: 100px">' . $isi_rumah . '</textarea>
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