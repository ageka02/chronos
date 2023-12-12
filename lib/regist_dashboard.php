<?php 
$conn_mysql = mysqli_connect("localhost","root","","chronos");
if (mysqli_connect_errno()) {
	echo "Koneksi gagal : ".mysqli_connect_error();
}
 
 ?>
 <!-- <!DOCTYPE html> -->
 <html>
 <head>
 <title>ff</title>
 </head>
<body>
	<form method="post">
	<!-- <h4>Name</h4>
<input type="text" name="name"> -->
	<h4>username</h4>
<input type="text" name="username">
	<h4>Password</h4>
<input type="password" name="password">
<!-- <h4>Level</h4> -->
<!-- <select name="level">
	<option value="0">Manager</option>
	<option value="1">Biasa</option>
	<option value="00">admin</option>
</select> -->
<input type="submit" name="submit">
</form>

<?php 
if (isset($_POST['submit']) ) {
	$username = $_POST['username'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

	$query = "INSERT INTO tbl_dashboard (username,password) VALUES('$username','$password')";
	$sql = mysqli_query($conn_mysql, $query);
	if ($sql) {
		echo "Berhasil insert Data";
	}else{
		echo "Coba lagi";
	}
}

 ?>
 </body>
 </html>
