<!DOCTYPE html>

<html lang="en">
<?php
include("includes/init.php");
include("includes/head.php");
?>

<body>

  <?php
  $about_css = "inactive";
  $submit_css = "inactive";
  include("includes/header.php");

  // function filter_params($param, $val)
  // {

  //   http_build_query(array($param => $val));
  // }

  ?>

  <!-- <div class="filters">
    <h3>Filter</h3>
    <h4>Classes</h4>
    <a href="index.php?">AP Studio</a>
  </div> -->
  <?php

  // show enlarged image as an overlay
  function show_overlay($id, $db)
  {
    $art_sql = "SELECT * FROM images WHERE (id = :img_id )";
    $art_params = array(
      ':img_id' => strval($id)
    );
    $art_record = exec_sql_query($db, $art_sql, $art_params)->fetchAll(PDO::FETCH_ASSOC)[0];
    $file = $art_record['id'] . '.' . $art_record['file_ext'];
    $title = $art_record['title'];
    $description = $art_record['description'];
    $size = $art_record['width'] . ' x ' . $art_record['height'] . ' in.';
    $artist_id = $art_record['artist_id'];

    $artist_sql = "SELECT name FROM artists WHERE (id = :id)";
    $artist_params = array(
      ':id' => strval($artist_id)
    );
    $artist = exec_sql_query($db, $artist_sql, $artist_params)->fetchAll(PDO::FETCH_ASSOC)[0]['name'];
  ?>
    <div id="overlay-wrapper">
      <div class="overlay">
        <div class="large-img">
          <img src="uploads/images/<?php echo $file; ?>">
        </div>
        <div class="img-info">
          <h3>Title</h3>
          <p><?php echo $title; ?></p>
          <h3>Artist</h3>
          <p><?php echo $artist; ?></p>
          <a href="#">
            <p class="metadata url">cindyhuang.me</p>
          </a>
          <p class="metadata"><?php echo $size; ?></p>
          <p class="metadata">AP Studio Art</p>
          <p class="metadata">Watercolor</p>
          <h3>Artist's Statement</h3>
          <p><?php echo $description; ?><p>
        </div>
      </div>
    </div>


  <?php

  }
  // check for http param
  if (isset($_GET['img_id'])) {
    show_overlay(intval($_GET['img_id']), $db);
    exit();
  }
  ?>

  <!-- div to replace with single image overlay -->
  <div id="replace">
  </div>

  <?php
  // display each image in a column
  function display_column($array, $db)
  {
    echo '<div class="column">';
    foreach ($array as $image) {
      echo '<img onclick="fetch_image(' . $image["id"] . ')" class="galleryImg" src="uploads/images/' . $image["id"] . "." . $image["file_ext"] . '">';
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
      display_column(array_slice($records, $col_start, $num_rows), $db);
      $col += 1;
      $col_start += $num_rows;
    }
    echo '</div>';
  }

  display_images($db, 3);
  ?>


  <script type="text/javascript" src="scripts/gallery.js"></script>
  <script type="text/javascript" src="scripts/jquery-3.4.1.min.js"></script>
</body>

</html>
