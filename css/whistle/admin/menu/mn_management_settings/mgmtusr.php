<?php

$action = "menu/mn_management_settings/act_mgmtusr.php";
switch ($_GET['act']) {
    default:
        echo'<div class="row-fluid">
            	<div class="span12">
            		<div class="widget-box">
                    	<div class="widget-title"> 
                        	<span class="icon"> 
                            	<i class="icon-user-md"></i> 
                        	</span>
                        	<h5>Management User</h5>
                        	<span class="label label-info">
	                        	<a title="Tambah Data" data-placement="bottom" data-toggle="tooltip" href="?menu=mgmtusr&act=updtusr&id=0">
		                            <button type="submit" class="btn"><i class="icon icon-plus"></i></button>
		                       	</a>
	                       	</span>
                    	</div>
                    	<div class="widget-content nopadding">
                    		<table class="table table-bordered data-table">
				                <thead>
				                    <tr>
				                        <th>No</th>
			                           	<th>Username</th>
			                           	<th>Name</th>
			                           	<th>Position</th>
			                           	<th>Privileges</th>
			                           	<th>Status</th>
			                          	<th>Last Online</th>
			                           	<th>IP Address</th>
			                           	<th>Action</th>
				                    </tr>
				                </thead>
		   ';
        $no = 1;
        $qry = mysql_query("SELECT * FROM tbl_user  ORDER BY LastOnline DESC");
        while ($result = mysql_fetch_array($qry)) {
            if ($result['lvl'] == 'Superadmin') {
                echo'';
            } else {
                echo'<tr>
							   	<td><center>' . $no . '</center></td>
							   	<td>' . $result['uname'] . '</td>
							    <td>' . $result['fname'] . '</td>
							    <td>' . $result['position'] . '</td>
							    <td>' . $result['lvl'] . '</td>
							    <td>' . $result['stts'] . '</td>
							    <td>' . $result['LastOnline'] . '</td>
							    <td>' . $result['LastOnlineIP'] . '</td>
							    <td>
							        <center>
								        <div class="btn-group">
				                            <a title="Edit Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-success" href="?menu=mgmtusr&act=updtusr&id=' . $result['id'] . '">
				                              	<i class="icon-edit icon-white"></i>
				                            </a>
				                            <a title="Hapus Data" data-placement="bottom" data-toggle="tooltip" class="btn btn-danger" onclick="return confirm(`APAKAH ANDA YAKIN UNTUK HAPUS DATA USER ?`)" href="' . $action . '?menu=mgmtusr&act=dltusr&id=' . $result['id'] . '">
				                                <i class="icon-remove icon-white"></i>
				                            </a>
									    </div>
									</center>
								</td>
							</tr>
					   	   ';
                $no++;
            }
        }
        echo'				</table>
	   					</div>
	            	</div>
	        	</div>
	        </div>
	       ';
        break;
    case 'updtusr':
        $id = $_GET['id'];
        if ($id != 0) {
            $qry2 = mysql_query("SELECT * FROM tbl_user WHERE id = $id");
            $result2 = mysql_fetch_array($qry2);
            $usrname = $result2['uname'];
            $passwd = $result2['pwd'];
            $fname = $result2['fname'];
            $position = $result2['position'];
            $level = $result2['lvl'];
            $status = $result2['stts'];
            $uptd = $result2['uptd'];
        }
        echo'<div class="row-fluid">
            	<div class="span12">
                	<div class="widget-box">
                    	<div class="widget-title"> 
                        	<span class="icon"> 
                            	<i class="icon-key"></i> 
                        	</span>
                        	<h5>Management User</h5>
                    	</div>
                    	<div class="widget-content nopadding">
                        	<form class="form-horizontal" method="POST" action="' . $action . '?menu=mgmtusr&act=updtusr&id=' . $id . '" name="basic_validate" id="basic_validate" novalidate="novalidate">
	       ';
                
        echo'                    <div class="control-group">
	                                <label class="control-label">Username :</label>
	                                <div class="controls">
	                                    <input type="text" class="span11" placeholder="Username" id="username" name="username" value="' . $usrname . '"/>
	                                </div>
	                            </div>
	                            <div class="control-group">
	                                <label class="control-label">Password :</label>
	                                <div class="controls">
	                                    <input type="password" placeholder="Password" class="span11" id="password" name="password"/>
	                                </div>
	                            </div>
	                            <div class="control-group">
	                                <label class="control-label">Name :</label>
	                                <div class="controls">
	                                    <input type="text" placeholder="Name" class="span11" id="fullname" name="fullname" value="' . $fname . '"/>
	                                </div>
	                            </div>
	                            <div class="control-group">
	                                <label class="control-label">Position :</label>
	                                <div class="controls">
	                                    <input type="text" placeholder="Position" class="span11" id="position" name="position" value="' . $position . '"/>
	                                </div>
	                            </div>
           ';
        if (isset($level)) {
            echo'<div class="control-group">
                                          	<label class="control-label">Privileges :</label>
                                          	<div class="controls">
                                            	<select style="width:400px" id="level" name="level">
                                              		<option value="' . $level . '" selected>' . $level . '</option>';
            $query2 = mysql_query("SELECT DISTINCT lvl FROM tbl_user WHERE lvl != '$level' ORDER BY lvl");
            while ($resqry2 = mysql_fetch_array($query2)) {
                echo'<option value="' . $resqry2["lvl"] . '">' . $resqry2["lvl"] . '</option>';
            }
            echo'		</select>
                                          	</div>
                                        </div>
                                       ';
        } else if (!isset($level)) {
            echo'<div class="control-group">
                                          	<label class="control-label">Privileges :</label>
                                          	<div class="controls">
                                            	<select style="width:400px" id="level" name="level">
                                              		<option value="" selected="selected"></option>';
            $query2 = mysql_query("SELECT DISTINCT lvl FROM tbl_user WHERE lvl != '$level' ORDER BY lvl");
            while ($resqry2 = mysql_fetch_array($query2)) {
                echo'<option value="' . $resqry2["lvl"] . '">' . $resqry2["lvl"] . '</option>';
            }
            echo'		</select>
                                          	</div>
                                        </div>
                                       ';
        }
        if (isset($uptd)) {
            echo'<div class="control-group">
                                          	<label class="control-label">Uptd :</label>
                                          	<div class="controls">
                                            	<select style="width:400px" id="level" name="uptd">
                                              		<option value="' . $uptd . '" selected>' . $uptd . '</option>';
            $query3 = mysql_query("SELECT * FROM tbl_uptd WHERE nama_uptd != '$uptd' ORDER BY kode_uptd");
            while ($resqry3 = mysql_fetch_array($query3)) {
                echo'<option value="' . $resqry3["nama_uptd"] . '">' . $resqry3["nama_uptd"] . '</option>';
            }
            echo'		</select>
                                          	</div>
                                        </div>
                                       ';
        } else if (!isset($uptd)) {
            echo'<div class="control-group">
                                          	<label class="control-label">Uptd :</label>
                                          	<div class="controls">
                                            	<select style="width:400px" id="level" name="uptd">
                                              		<option value="" selected="selected"></option>';
            $query3 = mysql_query("SELECT * FROM tbl_uptd WHERE nama_uptd != '$uptd' ORDER BY kode_uptd");
            while ($resqry3 = mysql_fetch_array($query3)) {
                echo'<option value="' . $resqry3["nama_uptd"] . '">' . $resqry3["nama_uptd"] . '</option>';
            }
            echo'		</select>
                                          	</div>
                                        </div>
                                       ';
        }
        if ($status == 'Active') {
            echo'<div class="control-group">
	                           	   			<label class="control-label">Status</label>
	                               			<div class="controls">
	                               				<label>
		                                            <input id="stts" name="stts" value="Active" type="radio" checked="" />
		                                               	Active
		                                        </label>
		                                        <label>
		                                            <input id="stts" name="stts" value="Passive" type="radio" /> 
		                                               	Passive
		                                        </label>
	                               			</div>
	                           			</div>
	                           		   ';
        } else if ($status == 'Passive') {
            echo'<div class="control-group">
	                           	   			<label class="control-label">Status</label>
	                               			<div class="controls">
	                               				<label>
	                                               	<input id="stts" name="stts" value="Active" type="radio" /> 
	                                               		Active
	                                            </label>
	                                            <label>
	                                               	<input id="stts" name="stts" value="Passive" type="radio" checked="" /> 
	                                               		Passive
                                               	</label>
	                               			</div>
	                           			</div>
	                           		   ';
        } else {
            echo'<div class="control-group">
	                           	   			<label class="control-label">Status</label>
	                               			<div class="controls">
                                               	<label>
	                                               	<input id="stts" name="stts" value="Active" type="radio" /> 
	                                               		Active
	                                            </label>
	                                            <label>
	                                               	<input id="stts" name="stts" value="Passive" type="radio" /> 
	                                               		Passive
                                               	</label>
	                               			</div>
	                           			</div>
	                           		   ';
        }
        echo'<div class="form-actions">
			                                <button type="submit" class="btn btn-success">Submit</button>
			                                <button class="btn btn-default" type="button" onClick="history.go(-1);">Cancel</button>
			                            </div>
		                           	   ';
        echo'				</fieldset>
                        </form>
                    </div>
                </div>
        	</div>
		   ';
        break;
}
?>