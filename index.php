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
  ?>

  <?php
  // if a specific tag was filtered
  if (isset($_GET['tag_id'])) {
    echo '<div id="gallery-wrapper">';
    show_filters($db, $_GET['tag_id']);
    if (count(get_tag_images(intval($_GET['tag_id']), $db)) == 0) {
      show_empty_state();
    } else {
      display_images(get_tag_images(intval($_GET['tag_id']), $db), 3);
    }
    echo '</div>';
    exit();
  }

  // image was just deleted
  function show_empty_state()
  { ?>
    <h2 class="feedback"> Oops! There is no artwork to show. Choose a different tag or add this tag to other art pieces!</h2>
  <?php }


  if (isset($_POST['delete'])) {
    $deleteid = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT);
    delete_image_with_id($db, $deleteid);
  }

  // side menu with filters
  function show_filters($db, $activeid)
  { ?>
    <div id="sidemenu">
      <h2>Filter</h2>
      <h3>Class</h3>
      <?php
      $classes = get_tags($db, 1);
      foreach ($classes as $class) {
        $activecss = 'inactive';
        if ($class['id'] == $activeid) {
          $activecss = 'active';
        }
        echo '<a class="tag ' . $activecss . '" href="index.php?' . http_build_query(array('tag_id' => $class['id'])) . '">' . $class['tag'] . '</a>';
      }
      ?>

      <h3>Medium</h3>
      <?php
      $media = get_tags($db, 2);
      foreach ($media as $medium) {
        $activecss = 'inactive';
        if ($medium['id'] == $activeid) {
          $activecss = 'active';
        }
        echo '<a class="tag ' . $activecss . '" href="index.php?' . http_build_query(array('tag_id' => $medium['id'])) . '">' . $medium['tag'] . '</a>';
      }
      ?>

    </div>

    <?php
  }

  // display each image in an array as a column
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

  // display all images stored in array $records as a gallery with $num_cols columns
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

  ?>


  <div id="gallery-wrapper">

    <?php
    show_filters($db, NULL);
    display_images(get_all_images($db), 3);
    ?>

  </div>


  <script type="text/javascript" src="scripts/gallery.js"></script>
  <script type="text/javascript" src="scripts/jquery-3.4.1.min.js"></script>
</body>

</html>
