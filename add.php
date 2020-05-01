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

  $class_tags = get_tags($db, 1);
  $media_tags = get_tags($db, 2);


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

  function filter_text($input, $output)
  {
    if (!empty($input)) {
      return filter_var($input, FILTER_SANITIZE_STRING);
    } else {
      return $output;
    }
  }

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
      // filter inputs
      $file_name = basename($upload_info['name']);
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $art_title = ucwords(filter_text($_POST['title'], "Untitled"));
      $artist_name = ucwords(filter_text($_POST['artist'], "Anonymous"));
      $description = ucfirst(filter_text($_POST['description'], ""));
      $contact = (!empty($_POST['portfolio']) ? filter_var($_POST['portfolio'], FILTER_SANITIZE_URL) : NULL);
      $art_width = (!empty($_POST['width']) ? filter_var($_POST['width'], FILTER_SANITIZE_NUMBER_FLOAT) : NULL);
      $art_height = (!empty($_POST['height']) ? filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_FLOAT) : NULL);

      // insert info into db
      // $artist_sql = "INSERT INTO artists (name) VALUES (:artist_name) ";
      // $artist_params = array(':artist_name' => $artist_name);
      // exec_sql_query($db, $artist_sql, $artist_params);
      insert_artist($db, $artist_name);
      $artist_id = $db->lastInsertId("id");

      // $img_sql = "INSERT INTO images (file_name, file_ext, artist_id, title, width, height, description, contact) VALUES (:file_name, :file_ext, :artist_id, :title, :width, :height, :description, :contact)";
      // $img_params = array(
      //   ':file_name' => $file_name,
      //   ':file_ext' => $file_ext,
      //   ':artist_id' => $artist_id,
      //   ":title" => $art_title,
      //   ':width' => $art_width,
      //   ':height' => $art_height,
      //   ':description' => $description,
      //   ':contact' => $contact
      // );
      // exec_sql_query($db, $img_sql, $img_params);
      insert_image($db, $file_name, $file_ext, $artist_id, $art_title, $art_width, $art_height, $description, $contact);
      $img_id = $db->lastInsertId("id");
      $new_path = "uploads/images/" . $img_id . "." . $file_ext;
      move_uploaded_file($upload_info["tmp_name"], $new_path);

      // add image tags
      $tags = tags_to_insert();
      insert_image_tags($tags, $img_id, $db);
    } else {
      echo 'Image failed to upload. Please try again!';
    }
  }

  ?>

  <div id="addformwrapper">
    <h2>Submit Your Artwork</h2>

    <form id="addform" method="POST" action="add.php" enctype="multipart/form-data" novalidate>
      <div class="forminput">
        <input type="hidden" name="MAX_FILE_SIZE" VALUE="1000000" />
        <label for="art_file">Upload image</label>
        <input type="file" accept="image/*" name="art_file" />
      </div>
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

      <div class="forminput" id="tags">

        <div class="inline-block" id="class">
          <label>Class</label>

          <?php
          display_tags_radio($class_tags)
          ?>

          <input type="text" name="newclass" placeholder="Other" class="fullwidth">
        </div>

        <div class="inline-block" id="medium">
          <label>Medium</label>

          <?php display_tags_radio($media_tags); ?>

          <input type="text" name="newmedium" placeholder="Other" class="fullwidth">

        </div>
      </div>
      <div class="forminput">
        <label for="description">Description or Artist's Statement</label>
        <textarea name="description" rows="6" cols="50"></textarea>
      </div>

      <div class="forminput">
        <input type="submit" name="upload_submit" />
      </div>
    </form>
  </div>

</body>

</html>
