<!DOCTYPE html>

<html lang="en">
<?php
include("includes/init.php");
include("includes/head.php");
//include("includes/queries.php");
?>

<body>


  <?php
  $about_css = "inactive";
  $submit_css = "active";

  include("includes/header.php");
  // all existing tags
  $class_tags = get_tags($db, 1);
  $media_tags = get_tags($db, 2);


  function show_feedback($success)
  {
    if ($success) { ?>
      <h2 class="feedback"> Successfully added! 🎉</h2>
      <div class="feedback">
        <a href="add.php" class="button primary"> View it in the gallery</a>
        <a href="add.php" class="button"> Add another piece</a>
      </div>
    <?php
      exit();
    } else { ?>

      <h2 class="feedback red"> Upload failed! 😢</h2>
      <div class="feedback">
        <a href="add.php" class="button primary"> Try again</a>
      </div>
  <?php
      exit();
    }
  }

  // display radio inputs with all of the existing tags
  function display_tags_radio($tags)
  {
    foreach ($tags as $tag) {
      $name = $tag['tag'];
      $tagid = $tag['id'];
      echo '<div class="radio-row">';
      echo '<input type="radio" name="class" id="' . $name . '" value="' . $tagid . '" />';
      echo '<label for="' . $name . '" class="radiolabel">' . $name . '</label>';
      echo '</div>';
    }
  }

  // filters text inputs
  function filter_text($input, $output)
  {
    if (!empty($input)) {
      return filter_var($input, FILTER_SANITIZE_STRING);
    } else {
      return $output;
    }
  }

  // Returns an array with the ids of the existing tags to add to the imae
  function tags_to_insert()
  {
    $tags = array();
    if (!empty($_POST['class'])) {
      array_push($tags, intval($_POST['class']));
    }
    $mediums = $_POST['medium'];
    if (!empty($mediums)) {
      foreach ($mediums as $medium) {
        array_push($tags, intval($medium));
      }
    }
    return $tags;
  }

  function insert_image_tags($tags, $img_id, $db)
  {
    foreach ($tags as $tag) {
      $sql = "INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :tag_id);";
      $params = array(
        ":image_id" => $img_id,
        ":tag_id" => $tag
      );
      exec_sql_query($db, $sql, $params);
    }
  }

  /// need to add new tags and do feedback

  if (isset($_POST['upload_submit'])) {
    $upload_info = $_FILES["art_file"];
    if ($upload_info['error'] == UPLOAD_ERR_OK) { //successful upload
      show_feedback(true);
      // filter inputs
      $file_name = basename($upload_info['name']);
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $art_title = ucwords(filter_text($_POST['title'], "Untitled"));
      $artist_name = ucwords(filter_text($_POST['artist'], "Anonymous"));
      $description = ucfirst(filter_text($_POST['description'], ""));
      $contact = (!empty($_POST['portfolio']) ? filter_var($_POST['portfolio'], FILTER_SANITIZE_URL) : NULL);
      $art_width = (!empty($_POST['width']) ? filter_var($_POST['width'], FILTER_SANITIZE_NUMBER_FLOAT) : NULL);
      $art_height = (!empty($_POST['height']) ? filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_FLOAT) : NULL);
      $new_class = (!empty($_POST['newclass']) ? ucwords(filter_var($_POST['newclass'], FILTER_SANITIZE_STRING)) : NULL);
      $new_medium = (!empty($_POST['newmedium']) ? ucwords(filter_var($_POST['newmedium'], FILTER_SANITIZE_STRING)) : NULL);

      // add artist to database
      insert_artist($db, $artist_name);
      $artist_id = $db->lastInsertId("id");
      // add image to database and server
      insert_image($db, $file_name, $file_ext, $artist_id, $art_title, $art_width, $art_height, $description, $contact);
      $img_id = $db->lastInsertId("id");
      $new_path = "uploads/images/" . $img_id . "." . $file_ext;
      move_uploaded_file($upload_info["tmp_name"], $new_path);

      // add image tags
      if (!empty($new_class)) {
        create_tag($db, $img_id, $new_class, 1);
      }
      if (!empty($new_medium)) {
        create_tag($db, $img_id, $new_medium, 2);
      }
      $tags = tags_to_insert();
      insert_image_tags($tags, $img_id, $db);
    } else {
      show_feedback(false);
    }
  }

  ?>

  <!-- add image form -->
  <div id="addformwrapper">
    <h2>Submit Your Artwork</h2>

    <form id="addform" method="POST" action="add.php" enctype="multipart/form-data" novalidate>
      <!-- file upload -->
      <div class="forminput">
        <input type="hidden" name="MAX_FILE_SIZE" VALUE="1000000" />
        <label for="art_file">Upload image</label>
        <input type="file" accept="image/*" name="art_file" />
      </div>
      <!-- artwork info -->
      <div class="forminput">
        <label for="title">Title</label>
        <input type="text" name="title" />
      </div>
      <div class="forminput">
        <label for="artist">Artist</label>
        <input type="text" name="artist" />
      </div>
      <div class="forminput">
        <label for="portfolio">Artist's portfolio</label>
        <input type="url" name="portfolio" />
      </div>
      <div class="forminput">
        <label>Size (inches)</label>
        <input type="number" name="width" class="inline-block smallinput" placeholder="Width" step="0.5" />
        <span> x </span>
        <input type="number" name="height" class="inline-block smallinput" placeholder="Height" step="0.5" />
      </div>

      <!-- add tags -->
      <div class="forminput" id="tags">
        <!-- choose existing tags -->
        <div class="inline-block" id="class">
          <label>Class</label>
          <?php
          display_tags_radio($class_tags)
          ?>
          <!-- add new class tag -->
          <input type="text" name="newclass" placeholder="Other" class="fullwidth">
        </div>

        <!-- choose existing tags -->
        <div class="inline-block" id="medium">
          <label>Medium</label>
          <?php display_tags_radio($media_tags); ?>
          <!-- add new art medium tag -->
          <input type="text" name="newmedium" placeholder="Other" class="fullwidth">
        </div>
      </div>

      <div class="forminput">
        <label for="description">Description or Artist's Statement</label>
        <textarea name="description" rows="6" cols="50"></textarea>
      </div>

      <!-- submit -->
      <div class="forminput">
        <input type="submit" name="upload_submit" value="Submit" />
      </div>
    </form>
  </div>

</body>

</html>
