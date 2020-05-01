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

  function get_tags($db, $type_id)
  {
    $sql = 'SELECT * FROM tags WHERE type_id = :type_id';
    $params = array(
      ':type_id' => $type_id
    );

    return exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  }


  $about_css = "inactive";
  $submit_css = "inactive";
  include("includes/header.php");
  ?>

  <?php

  if (isset($_GET['tag_id'])) {
    echo '<div id="gallery-wrapper">';
    show_filters($db);
    display_images(get_tag_images(intval($_GET['tag_id']), $db), 3);
    echo '</div>';
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


  function show_filters($db)
  { ?>
    <div id="sidemenu">
      <h2>Filter</h2>
      <h3>Class</h3>
      <?php
      $classes = get_tags($db, 1);
      foreach ($classes as $class) {
        echo '<a class="tag inactive" href="index.php?' . http_build_query(array('tag_id' => $class['id'])) . '">' . $class['tag'] . '</a>';
      }
      ?>

      <h3>Medium</h3>
      <?php
      $media = get_tags($db, 2);
      foreach ($media as $medium) {
        echo '<a class="tag inactive" href="index.php?' . http_build_query(array('tag_id' => $medium['id'])) . '">' . $medium['tag'] . '</a>';
      }
      ?>

    </div>

  <?php
  }

  ?>


  <div id="gallery-wrapper">

    <?php show_filters($db); ?>

    <!-- tags left column -->

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
      $num_rows = max(1, intdiv($num_records, $num_cols));
      $col = 0;
      $col_start = 0;
      while ($col < $num_cols) {
        // dealing with remainders
        if (($num_rows < $num_records) && ($num_records < 2 * $num_rows)) {
          $num_rows = $num_records;
        }

        // display one column of images
        display_column(array_slice($records, $col_start, $num_rows));
        $col += 1;
        $num_records -= $num_rows;
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
