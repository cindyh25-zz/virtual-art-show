<!DOCTYPE html>

<html lang="en">

<body>
  <?php
  include("includes/init.php");
  include("includes/head.php");



  if (isset($_GET['img_id'])) {
    $id = intval($_GET['img_id']);
  }

  function print_tags($tags)
  {
    echo '<p>';
    foreach ($tags as $tag) {
      $label = $tag['tag'];

      echo '<a class="taglink" href="index.php?' . http_build_query(array("tag_id" => $tag['id'])) . '">' . $label . '</a>';
    }
    echo '</p>';
  }

  $alltags_sql = "SELECT * FROM tags";
  $all_tags = exec_sql_query($db, $alltags_sql)->fetchAll(PDO::FETCH_ASSOC);

  $art_sql = "SELECT * FROM images WHERE (id = :img_id )";
  $art_params = array(
    ':img_id' => strval($id)
  );
  $art_record = exec_sql_query($db, $art_sql, $art_params)->fetchAll(PDO::FETCH_ASSOC)[0];
  $file = $art_record['id'] . '.' . $art_record['file_ext'];
  $art_title = $art_record['title'];
  $description = $art_record['description'];
  $size = $art_record['width'] . ' x ' . $art_record['height'] . ' in.';
  $artist_id = $art_record['artist_id'];

  $artist_sql = "SELECT name FROM artists WHERE (id = :id)";
  $artist_params = array(
    ':id' => strval($artist_id)
  );
  $artist = exec_sql_query($db, $artist_sql, $artist_params)->fetchAll(PDO::FETCH_ASSOC)[0]['name'];

  $tags_sql = "SELECT tags.tag, tags.id FROM tags INNER JOIN image_tags ON image_tags.tag_id = tags.id INNER JOIN images ON image_tags.image_id = images.id WHERE images.id = :imgid";
  $tags_params = array(
    ":imgid" => $id
  );
  $tags = exec_sql_query($db, $tags_sql, $tags_params)->fetchAll(PDO::FETCH_ASSOC);
  ?>


  <div id="overlay-wrapper">
    <div class="overlay">
      <div id="Xicon"><a href="index.php"><img class="iconsmall" src="documents/X.png"></a></div>
      <!-- Original icon made by Cindy Huang -->

      <div class="modal" id="deletemodal">
        <div class="modalcontent">
          <p>Are you sure you want to delete this artwork?</p>
          <form method="post" id="deleteform" action="index.php">
            <input type="submit" value="I'm sure">
          </form>
        </div>
      </div>

      <div class="modal" id="addtagmodal">
        <div class="modalcontent">
          <form method="post" id="addtagform" action="work.php">
            <p>Add an existing tag to this artwork</p>
            <select name="addtag" id="tagselect">
              <?php
              foreach ($all_tags as $tag) {
                echo '<option value=' . $tag['id'] . '>' . $tag['tag'] . '</option>';
              }
              ?>
            </select>
            <p>Or, make a new tag</p>
            <input type="text" name="newtag" id="newtagbox">
            <input type="submit" value="Add">
          </form>
        </div>
      </div>

      <div class="modal" id="deletetagmodal">
        <div class="modalcontent">
          <form method="post" id="deletetagform" action="work.php">
            <p>Delete tags</p>

            <?php
            foreach ($tags as $tag) {
              echo '<div class="checkbox-row">';
              echo '<input type="checkbox" id=' . $tag['tag'] . 'name="deleted[]" value=' . $tag['id'] . '>';
              echo '<label for="' . $tag['tag'] . '">' . $tag['tag'] . '</label>';
              echo '</div>';
            }
            ?>

            <input type="submit" value="Delete">
          </form>
        </div>
      </div>


      <div class="large-img">
        <img src="uploads/images/<?php echo $file; ?>">
      </div>
      <div class="img-info">

        <div id="settings">
          <div id="optionsicon">
            <img class="iconsmall" src="documents/options.png" onclick="showSettings()">
          </div>
          <!-- Original icon made by Cindy Huang -->

          <div id="settingscontent">
            <a href="#deletemodal">
              <p class="red">Delete image</p>
            </a>
            <a href="#addtagmodal">
              <p>Add tags</p>
            </a>
            <a href="#deletetagmodal">
              <p>Remove tags</p>
            </a>
          </div>
        </div>


        <div id="text">
          <h3>Title</h3>
          <p><?php echo $art_title; ?></p>
          <h3>Artist</h3>
          <p><?php echo $artist; ?></p>
          <a href="#">
            <p class="metadata url">cindyhuang.me</p>
          </a>
          <p class="metadata"><?php echo $size; ?></p>
          <?php print_tags($tags); ?>
          <h3>Artist's Statement</h3>
          <p><?php echo $description; ?><p>
        </div>

      </div>
    </div>
  </div>

  <script type="text/javascript" src="scripts/gallery.js"></script>
  <script type="text/javascript" src="scripts/jquery-3.4.1.min.js"></script>

</body>

</html>
