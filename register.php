<?php

include 'config.php';

if(isset($_POST['submit'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $image = $_FILES['image']['name'];
   $watermark = $_FILES["watermark"]["tmp_name"];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $select = mysqli_query($conn, "SELECT * FROM `user_db` WHERE email = '$email' AND password = '$pass'") or die('Query Failed!');

   if(mysqli_num_rows($select) > 0){
      $message[] = 'User Already Exist!'; 
   } else {
      if($pass != $cpass){
         $message[] = 'Passwords do not match!';
      } elseif($image_size > 2000000){
         $message[] = 'Image Size Too Large!';
      } else {
         $uploadedContent = file_get_contents($image_tmp_name);
         $imageHash = md5($uploadedContent);
         $files = scandir('./');

         foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
               @$fileContent = file_get_contents($file);
               @$fileHash = md5($fileContent);

               if ($fileHash == $imageHash) {
                  $message[] = 'Uploaded image already exists in the folder.';
                  break;
               }
            }
         }

         if (!isset($message)) {
            function stegtheimage($coverImagePath, $hiddenImagePath) {
               // Load the main image
               $coverImage = imagecreatefrompng($coverImagePath);
           
               // Load the watermark image
               $hiddenImage = imagecreatefrompng($hiddenImagePath);
           
               // Get dimensions of the main image
               $coverWidth = imagesx($coverImage);
               $coverHeight = imagesy($coverImage);
           
               // Resizing watermark image to fit inside the main image
               $hiddenImage = imagescale($hiddenImage, $coverWidth, $coverHeight);
           
               // Loop through each pixel of the main image
               for ($x = 0; $x < $coverWidth; $x++) {
                   for ($y = 0; $y < $coverHeight; $y++) {
                       // Get the RGB values of the main image's pixel
                       $coverPixel = imagecolorat($coverImage, $x, $y);
                       $coverRed = ($coverPixel >> 16) & 0xFF;
                       $coverGreen = ($coverPixel >> 8) & 0xFF;
                       $coverBlue = $coverPixel & 0xFF;
           
                       // Get the RGB values of the watermark image's pixel
                       $hiddenPixel = imagecolorat($hiddenImage, $x, $y);
                       $hiddenRed = ($hiddenPixel >> 16) & 0xFF;
                       $hiddenGreen = ($hiddenPixel >> 8) & 0xFF;
                       $hiddenBlue = $hiddenPixel & 0xFF;
           
                       // Modify the LSBs of the main image's pixel with the watermark image's pixel
                       $modifiedRed = ($coverRed & 0xFE) | ($hiddenRed >> 7);
                       $modifiedGreen = ($coverGreen & 0xFE) | ($hiddenGreen >> 7);
                       $modifiedBlue = ($coverBlue & 0xFE) | ($hiddenBlue >> 7);
           
                       // Create a new color with modified RGB values
                       $modifiedColor = imagecolorallocate($coverImage, $modifiedRed, $modifiedGreen, $modifiedBlue);
           
                       // Set the modified color to the cover image
                       imagesetpixel($coverImage, $x, $y, $modifiedColor);
                   }
               }
           
               return $coverImage;
           }
           
            $output=stegtheimage($image_tmp_name,$watermark);
            $finaloutput = $name."finaloutput.png";
            imagepng($output,$finaloutput);
            $image_folder = $finaloutput;
            $detinsert = mysqli_query($conn, "INSERT INTO `user_db`(username, email, password) VALUES('$name', '$email', '$pass')") or die('Query Failed!');
            $result=mysqli_fetch_array(mysqli_query($conn,"SELECT userid from `user_db` where email = '$email'"));
            $userid=$result['userid'];
            $imginsert = mysqli_query($conn, "INSERT INTO `images`(userid,image_data) VALUES($userid,'$finaloutput')") or die('Query Failed!');
            $result=mysqli_fetch_array(mysqli_query($conn,"SELECT imgid from `images` where userid= '$userid' "));
            $imageId=$result['imgid'];
            $initiatelikes=mysqli_query($conn,"INSERT INTO `likes`(imgid,like_count)VALUES($imageId,0)");
            if($detinsert && $imginsert){
               move_uploaded_file($finaloutput, $image_folder);
               $message[] = 'Registered Successfully!';
            } else {
               $message[] = 'Registration Failed!';
            }
         }
      }
   }

   copy($image_tmp_name,$image);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registration</title>

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>register now</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="text" name="name" placeholder="Enter Username" class="box" required>
      <input type="email" name="email" placeholder="Enter Email" class="box" required>
      <input type="password" name="password" placeholder="Enter Password" class="box" required>
      <input type="password" name="cpassword" placeholder="Confirm Password" class="box" required>
      <label for="mainimage">Main Image</label>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
      <label for="watermark">Watermark</label>
      <input type="file" name="watermark" class="box" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" name="submit" value="Register Now" class="btn">
      <p>Already have an account? <a href="login.php">Login Now</a></p>
   </form>

</div>

</body>
</html>