<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* Overall styles for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            display: flex;
        }

        /* Side panel styles */
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

        /* User info styles */
        .user-info {
            margin-bottom: 20px;
        }

        /* Image centering styles */
        .centered-image {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding:150px;
            padding-left:550px;
        }
        .centered-image img {
            max-width: 100%;
            max-height: 100%;
         }

        /* Logout button styles */
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
        .result-button {
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
    </style>
</head>
<body>
   <?php
      include 'config.php';
      session_start();
      $user_id = $_SESSION['user_id'];
   ?>
    <div class="side-panel">
        <?php
            $result=mysqli_fetch_array(mysqli_query($conn,"SELECT imgid from `images` where userid= '$user_id' "));
            $imageId=$result['imgid'];
            $res=mysqli_fetch_array(mysqli_query($conn,"SELECT like_count from likes where imgid= '$imageId'"));
            $result=mysqli_fetch_array(mysqli_query($conn,"SELECT username,email FROM user_db where userid = '$user_id' "));
            $user_name = $result['username'];
            $user_email = $result['email'];
        ?>
        <div class="user-info">
            <p>Name: <?php echo $user_name; ?></p>
            <p>Email: <?php echo $user_email; ?></p>
            <p>Likes: <?php echo $res['like_count'];?></p>
        </div>
        <a href="login.php" class="logout-button">Logout</a>
        <a href="winnertablein.php" class="result-button">View Results</a><br><br>
        <a href="index.html" class="result-button">Index Page</a>
    </div>

    <div class="centered-image">
        <?php
            $select = mysqli_query($conn, "SELECT * FROM `images` WHERE userid = '$user_id'") or die('query failed');
            if(mysqli_num_rows($select) > 0){
               $fetch = mysqli_fetch_assoc($select);
            }
            if($fetch['image_data'] == ''){
               echo '<img src="images/default-avatar.png">';
            }else{
               echo '<img src="'.$fetch['image_data'].'">';
            }
        ?>
    </div>
</body>
</html>