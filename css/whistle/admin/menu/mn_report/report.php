<?php

$action = "menu/mn_management_settings/act_mgmtbrnch.php";
        echo'<div class="row-fluid">
                            <div class="span12">
                                <div class="widget-box">
                                    <div class="widget-title"> 
                                        <span class="icon"> 
                                            <i class="icon-key"></i> 
                                                </span>
                                                    <h5>Management Report</h5>
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
                                                <label class="control-label">Date picker (dd-mm)</label>
                                                <div class="controls">
                                                    <input type="text" data-date="'.date('yyyy-mm-dd').'" data-date-format="yyyy-mm-dd" class="datepicker span11" readonly="readonly">
                                                <span class="help-block">Date with Formate of  (dd-mm-yy)</span> </div>
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

?>