<!DOCTYPE html>
<html>
<head>
	
</head>
	<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">KAUPT SAMSAT <?php echo $wilayah; ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
    <li><a href="menu.php">Beranda</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Lihat Status Transaksi <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a data-toggle="modal" data-target="#myModal" href="">Per Nopol</a></li>
            <li ><a href="lihat_per_hari.php">Per Hari Ini</a></li>
            
          </ul>
        </li>
      </ul>
      
	   <ul class="nav navbar-nav">
    
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Lihat Rubah Jatuh Tempo<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="batal_per_hari.php">Perhari</a></li>
            <li><a href="form_batal.php">Pertanggal</a></li>
            
          </ul>
        </li>
      </ul>
	  
	    <ul class="nav navbar-nav">
    
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Lihat Pembatalan Notice<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="nb_per_hari.php">Perhari</a></li>
            <li><a href="form_nb.php">Pertanggal</a></li>
            
          </ul>
        </li>
      </ul>
	  
	   <ul class="nav navbar-nav">
    
        <li class="dropdown">
          <a href="hilang_per_hari.php">Lihat Data Hilang</a>
          
        </li>
      </ul>
      
	  <ul class="nav navbar-nav navbar-right">
                        
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Keluar</a></li>
                    </ul>
      
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</html>
