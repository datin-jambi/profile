<?php

echo'<div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> 
                            <i class="icon-key"></i> 
                        </span>
                        <h5>Change Password</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="POST" action="menu/mn_passwd/act_passwd.php" name="password_validate" id="password_validate" novalidate="novalidate">
                            <div class="control-group">
                                <label class="control-label">Current Password :</label>
                                <div class="controls">
                                    <input type="password" class="span11" placeholder="Current Password" id="currentpassword" name="currentpassword"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">New Password :</label>
                                <div class="controls">
                                    <input type="password" class="span11" placeholder="New Password" id="newpassword" name="newpassword"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Confirm Password :</label>
                                <div class="controls">
                                    <input type="password" class="span11" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword"/>
                                </div>
                            </div>
                            <div class="form-actions">
                                <input type="submit" value="Submit" class="btn btn-success">
                                <input type="reset" value="Reset" class="btn btn-danger">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
       ';
?>