<?php

$action = "menu/mn_management_settings/act_mgmtmn.php";
switch ($_GET['act']) {
    default:
        echo'<div class="row-fluid">
            	<div class="span12">
            		<div class="widget-box">
                    	<div class="widget-title"> 
                        	<span class="icon"> 
                            	<i class="icon-user-md"></i> 
                        	</span>
                        	<h5>Management Menu</h5>
                        	<span class="label label-info">
	                        	<a title="Tambah Data" data-placement="bottom" data-toggle="tooltip" href="?menu=mgmtmn&act=updtmn&id=0">
                                <button type="submit" class="btn"><i class="icon icon-plus"></i></button>
		                       	</a>
	                       	</span>
                    	</div>
			 <div class="widget-content nopadding">
                    		<table class="table table-bordered data-table">
				        <thead>
				            <tr>
			                    	<th>No</th>
		                           	<th>Menu Name</th>
		                           	<th>Master Menu</th>
		                           	<th>Table Name</th>
			                        <th>Link</th>
			                        <th>Icon</th>
		                           	<th>Privileges</th>
		                           	<th>Status</th>
		                          	<th>Queue</th>
		                           	<th>Action</th>
			                    </tr>
			                </thead>
		   ';
        $no = 1;
        $qry = mysql_query("SELECT * FROM tbl_menu ORDER BY urutan");
        while ($result = mysql_fetch_array($qry)) {
            echo'<tr>
							    <td>' . $no . '</td>
							    <td>' . $result['nama_menu'] . '</td>
							    <td>' . $result['master_menu'] . '</td>
							    <td>' . $result['tabel_asal'] . '</td>
							    <td>' . $result['link'] . '</td>
							    <td>' . $result['icon'] . '</td>
							    <td>' . $result['lvl'] . '</td>
							    <td>' . $result['stts'] . '</td>
							    <td>' . $result['urutan'] . '</td>
							    <td>
							       	<div class="btn-group">
		                                <a title="Edit Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-success" href="?menu=mgmtmn&act=updtmn&id=' . $result['id'] . '">
		                                  	<i class="icon-edit icon-white"></i>
		                                </a>
		                                <a title="Hapus Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-danger" onclick="return confirm(`APAKAH ANDA YAKIN UNTUK HAPUS DATA MENU ?`)" href="' . $action . '?menu=mgmtmn&act=dltmn&id=' . $result['id'] . '">
		                                  	<i class="icon-remove icon-white"></i>
		                                </a>
							        </div>
							    </td>
							</tr>
				           ';
            $no++;
        }
        echo'		</table>
                            </div>
	            	</div>
                    </div>
	        </div>
	       ';
        break;
    case 'updtmn':
        $id = $_GET['id'];
        if ($id != 0) {
            $qry2 = mysql_query("SELECT * FROM tbl_menu WHERE id = $id");
            $result2 = mysql_fetch_array($qry2);
            $menuname = $result2['nama_menu'];
            $master_menu = $result2['master_menu'];
            $tablename = $result2['tabel_asal'];
            $icon = $result2['icon'];
            $link = $result2['link'];
            $level = $result2['lvl'];
            $status = $result2['stts'];
            $urutan = $result2['urutan'];
        }
        echo'<div class="row-fluid">
                            <div class="span12">
                                <div class="widget-box">
                                    <div class="widget-title"> 
                                        <span class="icon"> 
                                            <i class="icon-key"></i> 
                                                </span>
                                                    <h5>Management Menu</h5>
            		</div>
                    	<div class="widget-content nopadding">
                			<form class="form-horizontal" id="form_sample_1" method="POST" action="' . $action . '?menu=mgmtmn&act=updtmn&id=' . $id . '" onSubmit="return validasi(this)">
                				<fieldset>
                                            <div class="alert alert-error hide">
		                                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                                <h4>Error !!!</h4>
		                                You have some form errors. Please check below.
		                            </div>
                			    <div class="control-group">
		                                <label class="control-label">Nama Menu <span class="required">*</span></label>
		                                <div class="controls">
		                                    <input type="text" id="menuname" name="menuname" value="' . $menuname . '" data-required="1" class="span12 m-wrap"/>
		                                </div>
		                            </div>
                			    <div class="control-group">
		                                <label class="control-label">Master Menu <span class="required">*</span></label>
		                                <div class="controls">
		                                    <input type="text" id="Master Menu" name="master_menu" value="' . $master_menu . '" data-required="1" class="span12 m-wrap"/>
		                                </div>
		                            </div>';

        if (isset($tablename)) {
            echo'<div class="control-group">
	            <label class="control-label" for="select01">Tabel Asal </label>
	                <div class="controls">
	                    <select class="chzn-select" style="width:400px" name="tabelasal">
	                    <option value="' . $tablename . '" selected>' . $tablename . '</option>';
            $query2 = mysql_query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'db_whistle_blower' AND TABLE_NAME!='$tablename'");
            while ($resqry2 = mysql_fetch_row($query2)) {
                echo'<option value="' . $resqry2[0] . '">' . $resqry2[0] . '</option>';
            }
            echo'           </select>
	                </div>
	        </div>';
        } else if (!isset($tablename)) {
            echo'<div class="control-group">
	            <label class="control-label" for="select01">Tabel Asal </label>
	                <div class="controls">
	                    <select class="chzn-select" style="width:400px" name="tabelasal">
	                   <option value="" selected>Select a table name</option>';
            $query2 = mysql_query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'db_whistle_blower' AND TABLE_NAME!='$tablename'");
            while ($resqry2 = mysql_fetch_row($query2)) {
                echo'<option value="' . $resqry2[0] . '">' . $resqry2[0] . '</option>';
            }
            echo'           </select>
	                </div>
	        </div>';
        }
        echo'<div class="control-group">
		<label class="control-label">Link <span class="required">*</span></label>
                    <div class="controls">
			<input type="text" id="link" name="link" value="' . $link . '" data-required="1" class="span12 m-wrap"/>
                    </div>
            </div>
            <div class="control-group">
		<label class="control-label">Icon <span class="required">*</span></label>
                    <div class="controls">
			<input type="text" id="Icon" name="icon" value="' . $icon . '" data-required="1" class="span12 m-wrap"/>
                    </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="textarea2">Privileges </label>
                    <div class="controls">
                        <textarea class="input-xlarge textarea" id="lvl" name="lvl" style="width: 810px; height: 100px">' . $level . '</textarea>
                    </div>
            </div>';
        if ($status == 'Active') {
            echo'<div class="control-group">
                    <label class="control-label">Status</label>
		        <div class="controls">
                            <label><input id="stts" name="stts" value="Active" type="radio" checked />Active</label>
                            <label> <input id="stts" name="stts" value="Passive" type="radio" />Passive</label>
		        </div>
		</div>';
        } else if ($status == 'Passive') {
            echo'<div class="control-group">
                    <label class="control-label">Status</label>
		        <div class="controls">
                            <label><input id="stts" name="stts" value="Active" type="radio"  /> Active</label>
                            <label> <input id="stts" name="stts" value="Passive" type="radio" checked />Passive</label>
		        </div>
		</div>';
        } else {
            echo'<div class="control-group">
                    <label class="control-label">Status</label>
		        <div class="controls">
                            <label><input id="stts" name="stts" value="Active" type="radio"  />Active</label>
                            <label> <input id="stts" name="stts" value="Passive" type="radio" />Passive</label>
		        </div>
		</div>';
        }
        echo'<div class="control-group">
		<label class="control-label">Urutan <span class="required">*</span></label>
                    <div class="controls">
			<input type="text" id="urutan" name="urutan" value="' . $urutan . '" data-required="1" class="span12 m-wrap"/>
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
</div>';
        break;
}
?>