<?php
session_start();
if(!isset($_SESSION['username'])){header("location:./index.php");
}
if($_SESSION['kd_wilayah'] == '001')
{
$wilayah = 'KOTA JAMBI';
$lok = '01';	
}
if($_SESSION['kd_wilayah'] == '002')
{
$wilayah = 'BATANGHARI';
$lok = '02';	
}
if($_SESSION['kd_wilayah'] == '003')
{
$wilayah = 'TANJAB BARAT';
$lok = '03';	
}
if($_SESSION['kd_wilayah'] == '004')
{
$wilayah = 'MERANGIN';
$lok = '04';	
}
if($_SESSION['kd_wilayah'] == '005')
{
$wilayah = 'BUNGO';
$lok = '05';	
}
if($_SESSION['kd_wilayah'] == '006')
{
$wilayah = 'KERINCI';
$lok = '06';	
}
if($_SESSION['kd_wilayah'] == '007')
{
$wilayah = 'TANJAB TIMUR';
$lok = '07';	
}
if($_SESSION['kd_wilayah'] == '008')
{
$wilayah = 'MUARO JAMBI';
$lok = '08';	
}
if($_SESSION['kd_wilayah'] == '009')
{
$wilayah = 'SAROLANGUN';
$lok = '09';	
}
if($_SESSION['kd_wilayah'] == '010')
{
$wilayah = 'TEBO';
$lok = '10';	
}
if($_SESSION['kd_wilayah'] == '011')
{
$wilayah = 'SUNGAI PENUH';
$lok = '11';	
}
if($_SESSION['kd_wilayah'] == '')
{
$wilayah = 'BAKEUDA';	
}

?><!DOCTYPE html>
<html>
<head>
	<title>KUPT SAMSAT</title>
    
    <link rel="stylesheet" type="text/css" href="../file/style.css">
    <link rel="stylesheet" href="css/stylemenuutama.css">
	<link href="css/bootstrap.min.css" rel="stylesheet">    
    <link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="dataTables.bootstrap.css">
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>
 <link href="css/datepicker.css" rel="stylesheet">
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/bootstrap.js" type="text/javascript"></script>

	<script src="datatables/jquery.dataTables.min.js"></script>
	<script src="datatables/dataTables.bootstrap.min.js"></script>

	<script type="text/javascript" charset="utf-8" language="javascript" src="js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf-8" language="javascript" src="js/DT_bootstrap.js"></script>
	
	
</head>
<body>
	<?php include'header.php'; ?>

        
	<div id="menu">
		    
        
	<div id="content">        
		<div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" align="center">                     
		<div class="panel panel-info" >
	<div class="panel-heading">
                
                
	<div class="panel-title" align="center">Cari Data Notice Batal Per tanggal</div> 
    
		<form action="nb_per_tgl.php" method="post" enctype="multipart/form-data"> 
            <div style="padding-top:30px" class="panel-body">
				<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div> 
					<form id="loginform" class="form-horizontal" role="form">
                  
					<tr>
						<td width="23%" height="41"><div align="center"><b>Tanggal Awal</b></div></td>
						<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                  <input class="form-control" type="text" name="tgl_awal" >
                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div><br />
            <tr>
            <td width="23%" height="41"><div align="center"><b>Tanggal Akhir</b></div></td>
            <div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
                  <input class="form-control" type="text" name="tgl_akhir" >
                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div><br />
					
							
                            <div style="margin-top:10px" class="form-group" align="right">
                                <!-- tombol -->
                                <div class="col-sm-12 controls">
                              <input type="submit" name="upload"  class="btn btn-success" value="Cari">
                                </div>
                                <!-- akhir tombol -->
                            </div>
			</div>
                         
      </form>
                     
			
</form>
<script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script>
    $(".input-group.date").datepicker({autoclose: true, todayHighlight: true});
    </script>
        
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-left:400px; height:200px;">
              
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title" id="myModalLabel">Input Nopol</h4>
                  </div>
         
                  <div class="modal-body">
                   <form method="post" action="lihat_data.php">
                                    <label class="col-sm-2 col-sm-2 control-label">Nopol</label>
                                    <input type="text" value="BH" name="nx" disabled style="width:40px;"><input type="hidden" value="BH" name="na" style="width:60px;"> - <input type="text" placeholder="angka" name="nb" style="width:120px;"> - <input type="text" placeholder="seri" name="nc" style="width:40px;"><p><br />
                                 <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Cari</button>
                  </div>
                </form>
                              </div>
                </div>
              </div>
          </div>
</body>
</html>