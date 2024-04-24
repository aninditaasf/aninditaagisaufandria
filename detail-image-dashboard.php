<?php
 
    error_reporting(0);
    include 'db.php';
	$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
	$a = mysqli_fetch_object($kontak);
	
	$produk = mysqli_query($conn, "SELECT * FROM tb_image WHERE image_id = '".$_GET['id']."' ");
	$p = mysqli_fetch_object($produk);
	
	$komentar = mysqli_query($conn, "SELECT * FROM komentar_foto WHERE image_id = '".$_GET['id']."' order by created_at desc");
     $com = mysqli_fetch_object($komentar);
	
	$like= mysqli_query($conn, "SELECT * FROM tb_like WHERE image_id = '".$_GET['id']."' order by created_at desc");
     $L = mysqli_fetch_object($like);
	 
	
	session_start();
	include 'db.php';
	if($_SESSION['status_login'] != true){
		echo '<script>window.location="login.php"</script>';
    }
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WEB Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
        <h1><a href="dashboard.php">WEB GALERI FOTO</a></h1>
        <ul>
             <li><a href="dashboard.php">Dashboard</a></li>
           <li><a href="profil.php">Profil</a></li>
           <li><a href="data-image.php">Data Foto</a></li>
           <li><a href="Keluar.php">Keluar</a></li>
          
        </ul>
        </div>
    </header>
    
    <!-- search -->
    <div class="search">
        <div class="container">
            <form action="galeri-dash.php">
                <input type="text" name="search" placeholder="Cari Foto" value="<?php echo $_GET['search'] ?>" />
                <input type="hidden" name="kat" value="<?php echo $_GET['kat'] ?>" />
                <input type="submit" name="cari" value="Cari Foto" />
            </form>
        </div>
    </div>
    
    <!-- product detail -->
    <div class="section">
        <div class="container">
             <h3>Detail Foto</h3>
            <div class="box">
                <div class="col-2">
                   <img src="foto/<?php echo $p->image ?>" width="100%" /> 
                </div>
                <div class="col-2">
                   <h3><?php echo $p->image_name ?></h3>
                   <h4>Nama User : <?php echo $p->admin_name ?><br />
                   Upload Pada Tanggal : <?php echo $p->date_created  ?></h4>
                   <p>Deskripsi :<br />
                        <?php echo $p->image_description ?>
                   </p>
                   </div>
                   
				
            </div>
                               <div class="col-2">
                               <!-------suka----->
                               <form method="POST" action="">
                               <input type="hidden" name="gam" value="<?php echo $p->image_id ?>">
                               <input type="hidden" name="adname" value="<?php echo $_SESSION['a_global']->admin_name ?>" required>
                               <input type="hidden" name="like"  />
                                  
                <?php
              $qt = mysqli_query($conn, "SELECT SUM(suka) FROM tb_like WHERE image_id = '".$_GET['id']."'");
			  if(mysqli_num_rows($qt) > 0){
				  while($q = mysqli_fetch_array($qt)){
		  		?>
                   <button name="suka" class="like">Like <?php echo $q['SUM(suka)'] ?> </button><br />
       
                    <?php }}else{ ?>
              			<p>tidak ada like</p>
          			<?php } ?>
                 
                   </form>
                     <?php
                   if(isset($_POST['suka'])){
					  
					   $gam  = $_POST['gam'];
					   $adname   = $_POST['adname'];
					   $like   = $_POST['like'];
					   
					   $cekk = mysqli_query($conn, "SELECT * FROM tb_like WHERE admin_name='".$adname."' AND image_id='".$gam."'");
					   if(mysqli_num_rows($cekk) > 0){
						   echo  $hapus=mysqli_query($conn, "DELETE FROM tb_like WHERE admin_name='".$adname."' AND image_id='".$gam."'");
						   if($hapus){
							  echo '<script>window.location="detail-image-dashboard.php? id=' .$_GET['id'].'"</script>';
						   }else{
							      echo 'gagal'.mysqli_error($conn);
						   }
					   }else{
					   
					   $insert = mysqli_query($conn, "INSERT INTO tb_like VALUES (
						               null,
									   '".$gam."',
									   '".$adname."',
									   '1',
									    CURRENT_TIMESTAMP
									   ) ");
						 if($insert){
							 
							   echo '<script>window.location="detail-image-dashboard.php? id=' .$_GET['id'].'"</script>';
							  $com;
						   }else{
							   echo 'gagal'.mysqli_error($conn);
						   }
						   }
					   }
				   ?>
                   
                   <br />
                   <!---comentar--->
                    <form action="" method="POST">
                       <input type="hidden" name="image" value="<?php echo $p->image_id ?>">
                       <input type="hidden" name="adminid" value="<?php echo $_SESSION['a_global']->admin_id ?>" required >
                       <input type="hidden" name="adminnm" value="<?php echo $_SESSION['a_global']->admin_name ?>" required>
                       <textarea name="komentar" class="input-control"  maxlength="80" placeholder="Tulis Komentar..." required ></textarea>
                       <input type="submit" name="submit" value="Kirim" class="btn">
                       </form>
                    <?php
                   if(isset($_POST['submit'])){
					   include 'db.php';
					   $image  = $_POST['image'];
					   $adminid   = $_POST['adminid'];
					   $adminnm   = $_POST['adminnm'];
					   $komen  	   = $_POST['komentar'];
					   
					   $insert = mysqli_query($conn, "INSERT INTO komentar_foto VALUES (
						               null,
									   '".$image."',
									   '".$adminid."',
									   '".$adminnm."',
									   '".$komen."',
									    CURRENT_TIMESTAMP
									   ) ");
					  
						 if($insert){
							 
							   echo '<script>window.location="detail-image-dashboard.php? id=' .$_GET['id'].'"</script>';
							  $com;
						   }else{
							   echo 'gagal'.mysqli_error($conn);
							   
						   }
					   }
				   ?>
        <br />         
       <div class="">
       <h3>Komentar</h3>
       <div class="">
          <?php
              $up = mysqli_query($conn, "SELECT * FROM komentar_foto WHERE image_id = '".$_GET['id']."' ORDER BY tanggal_komentar DESC ");
			  if(mysqli_num_rows($up) > 0){
				  while($u = mysqli_fetch_array($up)){
		  ?>
         
          <div class="inpu"> 
            <h4><?php echo $u['admin_name'] ?><br /></h4> 
              <h5> <?php echo $u['isi_komentar'] ?><br /></h5>
             <h6> <?php echo $u['tanggal_komentar']  ?></h6>
          </div>
          </a>
          <?php }}else{ ?>
              <p>komentar tidak ada</p>
          <?php } ?>
       </div>
    </div>
                   
                   
                </div>

        </div>
        
    </div>
    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Web Galeri Foto.</small>
        </div>
    </footer>
</body>
</html>