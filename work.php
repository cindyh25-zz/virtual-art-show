<!DOCTYPE html>

<html lang="en">

<body>
  <?php
  include("includes/init.php");
  include("includes/head.php");


  if (isset($_GET['img_id'])) {
    $id = intval($_GET['img_id']);
    $all_tags = get_all_tags($db);
  }

  if (isset($_POST['deleted'])) {
    $deleted = $_POST['deleted'];
    if (!empty($deleted)) {
      foreach ($deleted as $tag) {
        delete_imagetag_with_tagid($db, $tag);
      }
    }
  }


  if (isset($_POST['addtag'])) {
    $addtagid = trim(filter_input(INPUT_POST, 'addtag', FILTER_SANITIZE_NUMBER_INT));
    if (!empty($addtagid)) {
      $current_tagids = current_tags_of_image($db, $id);
      $isduplicate = false;
      foreach ($current_tagids as $currtagid) {
        if ($currtagid['tag_id'] == $addtagid) {
          $isduplicate = true;
          break;
        }
      }

      if (!$isduplicate) {
        insert_image_tag($db, $id, $addtagid);
      }
    }
  }


  if (isset($_POST['newtag'])) {
    $newtag = trim(ucwords(filter_input(INPUT_POST, 'newtag', FILTER_SANITIZE_STRING)));
    $type = filter_input(INPUT_POST, 'tagtype', FILTER_SANITIZE_NUMBER_INT);

    if (!(empty($newtag)) && !(empty($type))) {
      create_tag($db, $id, $newtag, $type);
    } else {
      echo 'Please specify the tag name and type of tag you are adding.';
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




  $art_record = get_image_with_id($db, $id);
  $file = $art_record['id'] . '.' . $art_record['file_ext'];
  $art_title = $art_record['title'];
  $description = $art_record['description'];
  if (!empty($art_record['width']) && (!empty($art_record['height']))) {
    $size = $art_record['width'] . ' x ' . $art_record['height'] . ' in.';
  }
  $portfolio = $art_record['contact'];
  $artist_id = $art_record['artist_id'];

  $artist = get_artist_with_id($db, $artist_id);


  $tags = get_tags_of_image($db, $id);
  ?>


  <div class="full-img">
    <div id="Xicon"><a href="index.php"><img class="iconsmall" src="documents/X.png"></a></div>
    <!-- Original icon made by Cindy Huang -->

    <div class="modal" id="deletemodal">
      <div class="modalcontent">
        <div id="Xicon"><img class="iconsmall" src="documents/X.png" onclick="closeModal('deletemodal')"></div>
        <!-- Original icon made by Cindy Huang -->
        <h3>Are you sure you want to delete this artwork?</h3>
        <form method="post" id="deleteform" action="index.php">
          <button name='delete' value="<?php echo $id; ?>">I'm sure</button>
        </form>
      </div>
    </div>

    <div class="modal" id="addtagmodal">
      <div class="modalcontent">
        <!-- <div class="darkoverlay"></div> -->

        <div id="Xicon"><a href="#"><img class=" iconsmall" src="documents/X.png"></a></div>
        <!-- Original icon made by Cindy Huang -->
        <form method="post" id="addtagform" action="work.php?<?php echo http_build_query(array("img_id" => $id)); ?>">
          <h3>Add an existing tag to this artwork</h3>
          <select name="addtag" id="tagselect">
            <option value="" id="emptyoption">Select an existing tag</option>
            <?php
            foreach ($all_tags as $tag) {
              echo '<option value="' . $tag['id'] . '">' . $tag['tag'] . '</option>';
            }
            ?>
          </select>
          <h3>Or, make a new tag</h3>
          <input type="text" name="newtag" id="newtagbox">
          <p>Select what type of tag this is</p>
          <div>
            <div class="radio-row">
              <input type="radio" value="1" name="tagtype" id="classradio">
              <label for="classradio" class="radiolabel">A class</label>
            </div>

            <div class="radio-row">
              <input type="radio" value="2" name="tagtype" id="mediumradio">
              <label for="mediumradio" class="radiolabel">An art medium</label>
            </div>
          </div>

          <input type="submit" value="Add">
        </form>
      </div>
    </div>

    <div class="modal" id="deletetagmodal">
      <div class="modalcontent">
        <div id="Xicon"><img class="iconsmall" src="documents/X.png" onclick="closeModal('deletetagmodal')"></div>
        <!-- Original icon made by Cindy Huang -->
        <form method="post" id="deletetagform" action="work.php?<?php echo http_build_query(array("img_id" => $id)); ?>">
          <h3>Delete tags</h3>

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