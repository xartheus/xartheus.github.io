<!DOCTYPE html>
<html>
<head>
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
    </style>
</head>
<body style="background-color:gray;">

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
        echo '<button id="button" onclick="likeImage('.$row['imgid'].')">Like</button>'.'<span id="likeCount'.$row['imgid'].'">'.$row['like_count'].'</span> likes <br><br>';
        echo '</div>';
    }
} else {
    echo "No images found.";
}

$conn->close();
?>

<script>
function likeImage(imageId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("likeCount"+imageId).innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "initiatelikes.php?id="+imageId, true);
    xhttp.send();
    xhttp.open("GET", "like.php?id="+imageId, true);
    xhttp.send();
}
</script>

</body>
</html>
