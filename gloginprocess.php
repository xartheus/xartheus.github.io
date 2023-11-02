<?php
$email = $_POST['email'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "photocontest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$check_query_guest = "SELECT * FROM `guest` WHERE g_email='$email'";
$check_query_user = "SELECT * FROM `user_db` WHERE email='$email'";
$result_guest = $conn->query($check_query_guest);
$result_user = $conn->query($check_query_user);
if($result_user->num_rows > 0){
  echo '<script>var db = confirm("You have already registered as a participant!");
  if(db){
    window.location.href = "guestlogin.html";
  }
  else{
    window.location.href= "guestlogin.html";
  }
  </script>';
}
else if ($result_guest->num_rows > 0) {
  echo '<script>var db = confirm("You have already registered and liked!");
  if(db){
    window.location.href = "guestlogin.html";
  }
  else{
    window.location.href= "guestlogin.html";
  }
  </script>';
} else {
  $insert_query = "INSERT INTO `guest` (g_email) VALUES ('$email')";
  $conn->query($insert_query);
  $_SESSION['user_email'] = $email;
  echo '<script>window.location.href="imggallery.php";</script>';
}

$conn->close();
exit();
?>
