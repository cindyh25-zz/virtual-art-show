<!DOCTYPE html>

<html lang="en">
<?php
include("includes/init.php");
include("includes/head.php");
?>

<body>


  <?php

  function get_tag_images($tag_id, $db)
  {
    $sql = "SELECT images.id, images.file_ext FROM images INNER JOIN image_tags ON image_tags.image_id = images.id INNER JOIN tags ON image_tags.tag_id = tags.id WHERE image_tags.tag_id = :tag_id";
    $params = array(
      ":tag_id" => $tag_id
    );

    return exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  }

  function get_all_images($db)
  {
    return exec_sql_query($db, "SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);
  }


  $about_css = "inactive";
  $submit_css = "inactive";
  include("includes/header.php");
  ?>

  <?php

  if (isset($_GET['tag_id'])) {
    display_images(get_tag_images(intval($_GET['tag_id']), $db), 3);
    exit();
  }

  // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['delete'])) {
    $deleteid = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT);
    $deletesql = 'DELETE FROM images WHERE id = :id';
    $deleteparams = array(
      ':id' => $deleteid
    );
    exec_sql_query($db, $deletesql, $deleteparams);

    $deletetagsql = 'DELETE FROM image_tags WHERE image_id = :id';
    $deletetagparams = array(
      ':id' => $deleteid
    );
    exec_sql_query($db, $deletesql, $deleteparams);
  }

  ?>

  <!-- div to replace with single image overlay -->
  <!-- <div id="replace">
  </div> -->




  <div id="gallery-wrapper">
    <div id="menuicon"><img class="icon" src="documents/filtericon.png" onclick="showSideMenu()"></div>
    <!-- Original icon created by Cindy Huang-->

    <!-- tags left column -->
    <div id="sidemenu">
      <!-- <img class="icon" src="documents/X.png" onclick="hideSideMenu()"> -->
      <!-- Original icon created by Cindy Huang-->
      <h2>Filter</h2>
      <h3>Class</h3>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 1)); ?>">AP Studio Art</a>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 2)); ?>">Advanced Art Honors</a>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 3)); ?>">Visual Art 1</a>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 4)); ?>">Visual Art 2</a>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 5)); ?>">Foundations of Art</a>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 6)); ?>">Intro to Painting</a>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 7)); ?>">Photography</a>

      <h3>Medium</h3>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 8)); ?>">Acrylic</a>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 9)); ?>">Watercolor</a>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 10)); ?>">Pencil</a>
      <a class="tag inactive" href="index.php?<?php echo http_build_query(array("tag_id" => 11)); ?>">Ink</a>

    </div>

    <?php
    // display each image in a column
    function display_column($array)
    {
      echo '<div class="column">';
      foreach ($array as $image) {

        $filename = $image['id'] . "." . $image['file_ext'];
    ?>
        <a href="work.php?<?php echo http_build_query(array("img_id" => $image['id'])); ?>">
          <img class="galleryImg" src="uploads/images/<?php echo $filename; ?>"></a>
    <?php }
      echo '</div>';
    }


    function display_images($records, $num_cols)
    {
      $num_records = count($records);
      $num_rows = $num_records / $num_cols + 1;
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

    display_images(get_all_images($db), 3);

    ?>
  </div>


  <script type="text/javascript" src="scripts/gallery.js"></script>
  <script type="text/javascript" src="scripts/jquery-3.4.1.min.js"></script>
</body>

</html>
