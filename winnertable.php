<?php
include('config.php');

$sql = "SELECT user_db.username, likes.like_count 
        FROM user_db 
        JOIN images ON user_db.userid = images.userid
        JOIN likes ON images.imgid = likes.imgid
        ORDER BY likes.like_count DESC";

$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
  echo "<table class='styled-table'>
        <thead>
          <tr>
            <th>Name</th>
            <th>Like Count</th>
          </tr>
        </thead>
        <tbody>";

  while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>".$row["username"]."</td>
            <td>".$row["like_count"]."</td>
          </tr>";
  }

  echo "</tbody></table>";
} else {
  echo "0 results";
}

$conn->close();
?>
