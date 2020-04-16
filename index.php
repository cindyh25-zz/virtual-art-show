<!DOCTYPE html>

<html lang="en">
<?php
include("includes/init.php");
include("includes/head.php");
?>

<body>

  <h1>Virtual Art Show</h1>

  <div class="topnav">
    <div id="links">
      <a href="about.php">About</a>
      <a href="add.php" class="button">Submit Artwork</a>
    </div>
  </div>

  <?php

  function display_column($array)
  {
    echo '<div class="column">';
    foreach ($array as $image) {
      echo '<img src="uploads/images/' . $image["id"] . "." . $image["file_ext"] . '">';
    }
    echo '</div>';
  }

  function display_images($db, $num_cols)
  {
    $records = exec_sql_query($db, "SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);
    $num_records = count($records);
    $num_rows = $num_records / $num_cols;
    $col = 0;
    $col_start = 0;
    echo '<div class="gallery">';
    while ($col < $num_cols) {
      // dealing with remainders
      if ($num_records - ($col * $num_rows) < $num_rows) {
        $num_rows += $num_records - ($col * $num_rows);
      }
      // display one column of images
      display_column(array_slice($records, $col_start, $num_rows));
      $col += 1;
      $col_start += $num_rows;
    }
    echo '</div>';
  }

  display_images($db, 3);
  ?>

</body>

</html>
