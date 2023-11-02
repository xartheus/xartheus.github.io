<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "photocontest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$imageId = $_GET['id'];
$sql = "UPDATE `likes` SET like_count = like_count + 1 WHERE imgid = $imageId";
$conn->query($sql);

$sql = "SELECT like_count FROM likes WHERE imgid = $imageId";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo $row['like_count'];
$conn->close();
?>

