<?php
function show_overlay($id, $db)
{
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
?>
  <div id="overlay-wrapper" onclick="closeOverlay()">
    <div class="overlay">
      <div class="large-img">
        <img src="uploads/images/<?php echo $file; ?>">
      </div>
      <div class="img-info">
        <h3>Title</h3>
        <p><?php echo $art_title; ?></p>
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
