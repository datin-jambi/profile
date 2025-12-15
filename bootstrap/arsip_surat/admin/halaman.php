<?php
date_default_timezone_set('Asia/Jakarta');

session_start();
if (!isset($_SESSION['username']) OR empty($_SESSION['username'])) {
	header("location:login.php");
}
include"../lib/fungsi_indotgl.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Arsip Surat BPKPD</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="paging.css">
    <link type="text/css" href="css/datepicker2.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    
    <SCRIPT language=Javascript>
<!--
function isNumberKey(evt)
{
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 48 || charCode > 57) )

return false;
return true;
}
//-->
</SCRIPT> 

 
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper" style="background:#003">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header" style="background:#003">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" style="background:#003">BPKPD</a>
                
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav" style="background:#003">
              
			   
                <a class="navbar-brand" href="#" style="text-align:right; font-size:13px">Tanggal : <?php echo tglindo(date('Y-m-d')).', Jam : '.date('H:i:s'); ?> </a>
                 
                    
                    
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['nama']; ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                                 <li>
                            <a href="?mod=setting"><i class="fa fa-fw fa-user"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse" style="background:#003">
                <ul class="nav navbar-nav side-nav" style="background:#003">
                    <li>
                        <a href="?mod=utama"><i class="fa fa-fw fa-dashboard"></i> Beranda</a>
                    </li>
                   
                    <li>
                    <a href="?mod=manajemenadmin"><i class="fa fa-fw fa-book"></i> Tambah User</a>
                    </li>

                   
                    <li>
                    <a href="?mod=surattt"><i class="fa fa-fw fa-book"></i> Data Arsip Surat</a>
                    </li>
                       <li>                 
                    <a href="?mod=laporan"><i class="fa fa-fw fa-book"></i> Laporan</a>
                    </li>
                  
                    
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                
                <!-- /.row -->
<?php include"content.php"; ?>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    
    <script src='js/bootstrap-datepicker.js'></script>

</body>

</html>
