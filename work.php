<!DOCTYPE html>

<html lang="en">

<body>
  <?php
  include("includes/init.php");
  include("includes/head.php");


  if (isset($_GET['img_id'])) {
    $id = intval($_GET['img_id']);
    $alltags_sql = "SELECT * FROM tags";
    $all_tags = exec_sql_query($db, $alltags_sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  if (isset($_POST['deleted'])) {
    $deleted = $_POST['deleted'];
    if (!empty($deleted)) {
      foreach ($deleted as $tag) {
        $deletesql = 'DELETE FROM image_tags WHERE tag_id = :id';
        $deleteparams = array(
          ':id' => $tag
        );
        exec_sql_query($db, $deletesql, $deleteparams);

        // $gettagsql = 'SELECT * FROM image_tags WHERE tag_id = :id';
        // $gettagparams = array(':id' => $tag);
        // $num_imgs = count(exec_sql_query($db, $gettagsql, $gettagparams)->fetchAll(PDO::FETCH_ASSOC));

        // if ($num_imgs == 0) {
        //   $deletetagsql = 'DELETE FROM tags WHERE id = :id';
        //   $deletetagparams = array(
        //     ':id' => $tag
        //   );
        //   exec_sql_query($db, $deletetagsql, $deletetagparams);
        // }
      }
    }
  }


  if (isset($_POST['addtag'])) {
    $addtagid = trim(filter_input(INPUT_POST, 'addtag', FILTER_SANITIZE_NUMBER_INT));
    if (!empty($addtagid)) {
      $current_sql = 'SELECT tag_id FROM image_tags WHERE image_id = :id';
      $current_tagids = exec_sql_query($db, $current_sql, array(':id' => $id))->fetchAll(PDO::FETCH_ASSOC);
      $isduplicate = false;
      foreach ($current_tagids as $currtagid) {
        if ($currtagid['tag_id'] == $addtagid) {
          $isduplicate = true;
          break;
        }
      }

      if (!$isduplicate) {
        $addtagsql = 'INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :tag_id)';
        $addtagparams = array(
          ':image_id' => $id,
          ':tag_id' => $addtagid
        );
        exec_sql_query($db, $addtagsql, $addtagparams);
      }
    }
  }


  if (isset($_POST['newtag'])) {
    $newtag = trim(ucwords(filter_input(INPUT_POST, 'newtag', FILTER_SANITIZE_STRING)));
    if (!empty($newtag)) {
      $newtagsql = 'INSERT INTO tags (tag, type_id) VALUES (:tag, :type)';
      exec_sql_query($db, $newtagsql, array(':tag' => $newtag, ':type' => 2));
      $tag_id = $db->lastInsertId("id");
      $newimgtag_sql = 'INSERT INTO image_tags (image_id, tag_id) VALUES (:img_id, :tag_id)';
      $newimgtag_params = array(
        ':img_id' => $id,
        ':tag_id' => $tag_id
      );
      exec_sql_query($db, $newimgtag_sql, $newimgtag_params);
    }
  }


  function print_tags($tags)
  {
    echo '<p class="worktags">Tags: ';
    foreach ($tags as $tag) {
      $label = htmlspecialchars($tag['tag']);

      echo '<a class="taglink" href="index.php?' . http_build_query(array("tag_id" => $tag['id'])) . '">' . $label . '</a>';
    }
    echo '</p>';
  }



  $art_sql = "SELECT * FROM images WHERE (id = :img_id )";
  $art_params = array(
    ':img_id' => strval($id)
  );
  $art_record = exec_sql_query($db, $art_sql, $art_params)->fetchAll(PDO::FETCH_ASSOC)[0];
  $file = $art_record['id'] . '.' . $art_record['file_ext'];
  $art_title = $art_record['title'];
  $description = $art_record['description'];
  if (!empty($art_record['width']) && (!empty($art_record['height']))) {
    $size = $art_record['width'] . ' x ' . $art_record['height'] . ' in.';
  }
  $portfolio = $art_record['contact'];
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


  <div class="full-img">
    <div id="Xicon"><a href="index.php"><img class="iconsmall" src="documents/X.png"></a></div>
    <!-- Original icon made by Cindy Huang -->

    <div class="modal" id="deletemodal">
      <div class="modalcontent">
        <div id="Xicon"><img class="iconsmall" src="documents/X.png" onclick="closeModal('deletemodal')"></div>
        <!-- Original icon made by Cindy Huang -->
        <p>Are you sure you want to delete this artwork?</p>
        <form method="post" id="deleteform" action="index.php">
          <button name='delete' value="<?php echo $id; ?>">I'm sure</button>
        </form>
      </div>
    </div>

    <div class="modal" id="addtagmodal">
      <div class="modalcontent">
        <!-- <div class="darkoverlay"></div> -->

        <div id="Xicon"><img class="iconsmall" src="documents/X.png" onclick="closeModal('addtagmodal')"></div>
        <!-- Original icon made by Cindy Huang -->
        <form method="post" id="addtagform" action="work.php?<?php echo http_build_query(array("img_id" => $id)); ?>">
          <p>Add an existing tag to this artwork</p>
          <select name="addtag" id="tagselect">
            <option value="" id="emptyoption">Select an existing tag</option>
            <?php
            foreach ($all_tags as $tag) {
              echo '<option value="' . $tag['id'] . '">' . $tag['tag'] . '</option>';
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
        <div id="Xicon"><img class="iconsmall" src="documents/X.png" onclick="closeModal('deletetagmodal')"></div>
        <!-- Original icon made by Cindy Huang -->
        <form method="post" id="deletetagform" action="work.php?<?php echo http_build_query(array("img_id" => $id)); ?>">
          <p>Delete tags</p>

          <?php
          foreach ($tags as $tag) {
            echo '<div class="checkbox-row">';
            echo '<input type="checkbox" id="' . $tag['tag'] . '" name="deleted[]" value="' . $tag['id'] . '">';
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
          <!-- <p class="cursor" onclick="showModal('deletemodal')">Delete image</p>
          <p class='cursor' onclick="showModal('addtagmodal')">Add tags</p>
          <p class='cursor' onclick="showModal('deletetagmodal')">Remove tags</p> -->
          <a href="#deletemodal">
            <p>Delete image</p>
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
        <p><?php echo htmlspecialchars($art_title); ?></p>
        <p class="metadata"><?php echo htmlspecialchars($size); ?></p>
        <h3>Artist</h3>
        <p><?php echo htmlspecialchars($artist); ?></p>
        <a href="<?php echo $portfolio; ?>">
          <p class="metadata url">Portfolio</p>
        </a>
        <h3>Artist's Statement</h3>
        <p><?php echo htmlspecialchars($description); ?><p>
            <?php print_tags($tags); ?>
      </div>

    </div>
  </div>


  <script type="text/javascript" src="scripts/gallery.js"></script>
  <script type="text/javascript" src="scripts/jquery-3.4.1.min.js"></script>

</body>

</html>
