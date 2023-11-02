<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <title>Image Gallery</title>
    <style>
        .container {
            display: inline-block;
            margin: 10px;
            text-align: center;
        }

        .container img {
            width: 450px;
            height: 300px;
        }

        .container button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-top: 5px;
        }

        .container span {
            display: block;
            margin-top: 5px;
        }
        .side-panel {
            background-color: #333;
            color: #fff;
            width: 320px;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            font-size: 20px;
        }
        .logout-button {
            background-color: #ff4500;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
        }
        .res-button {
            background-color: #ff4500;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
        }
        .imggallery{
            margin-left:270px;
        }
    </style>
</head>
<body style="background-color:gray;">
<div class="side-panel">
    <p>Guest User</p><br>
    <a href="guestlogin.html" class="logout-button">Logout</a>
    <a href="winnertablein.php" class="res-button">View Results</a><br><br>
    <a href="index.html" class="res-button">Index Page</a>
</div>
<center><div class="imggallery">
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "photocontest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT i.imgid, i.image_data, COALESCE(l.like_count, 0) as like_count FROM images i LEFT JOIN likes l ON i.imgid = l.imgid";
$result = mysqli_query($conn,$sql);

if (mysqli_num_rows($result)>0) {
    while($row=mysqli_fetch_assoc($result)) {
        echo '<div class="container">';
        echo '<img src="'.$row['image_data'].'" alt="Image" /><br>';
        echo '<button id="button" onclick="likeImage('.$row['imgid'].')">Like</button>'.'<span id="likeCount'.$row['imgid'].'">'.$row['like_count'].'</span> likes';
        echo '</div>';
    }
} else {
    echo "No images found.";
}

$conn->close();
?>
</div></center>
<script>
function likeImage(imageId) {
    var button = document.getElementById("button");

    if (!button.disabled) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("likeCount"+imageId).innerHTML = this.responseText;
                button.disabled = true;
            }
        };
        xhttp.open("GET", "like.php?id="+imageId, true);
        xhttp.send();
    }
}
</script>

</body>
</html>