<?php
// check current php version to ensure it meets 2300's requirements
function check_php_version()
{
  if (version_compare(phpversion(), '7.0', '<')) {
    define('VERSION_MESSAGE', "PHP version 7.0 or higher is required for 2300. Make sure you have installed PHP 7 on your computer and have set the correct PHP path in VS Code.");
    echo VERSION_MESSAGE;
    throw VERSION_MESSAGE;
  }
}
check_php_version();

function config_php_errors()
{
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 0);
  error_reporting(E_ALL);
}
config_php_errors();

// Open a connection to an SQLite database stored in filename: $db_filename.
// If database does not exists, will execute .sql file from $init_sql_filename
// to create and initialize the database. No database is created if there is
// an error the initialization SQL.
// Returns: Connection to database.
// Example: $db = open_or_init_sqlite_db('secure/gallery.sqlite', 'secure/init.sql');
function open_or_init_sqlite_db($db_filename, $init_sql_filename)
{
  // If the init SQL script does not exist, quit!
  if (!file_exists($init_sql_filename)) {
    throw new Exception("No such file: " . $init_sql_filename);
  }

  // create checksum of initialization script.
  $init_sql = file_get_contents($init_sql_filename);
  $init_checksum = md5($init_sql);

  // checksum used to create the database
  $init_checksum_filename = $init_sql_filename . ".checksum";

  // If the database doesn't exist, then the existing checksum is invalid. Delete it.
  if (!file_exists($db_filename)) {
    unlink($init_checksum_filename);
  }

  // If the database exists, but no checksum file exists, then we have a consistency problem with the DB.
  if (file_exists($db_filename) && !file_exists($init_checksum_filename)) {
    throw new Exception("No checksum for existing database. Please regenerate your database (delete .sqlite file).");
  }

  // Get the existing checksum and compare it the init checksum.
  if (file_exists($init_checksum_filename)) {
    $current_checksum = file_get_contents($init_checksum_filename);

    if ($init_checksum != $current_checksum) {
      throw new Exception("Database initialization script has changed. Please regenerate your database (delete .sqlite file).");
    }
  }

  // If the database does not exist, create it!
  if (!file_exists($db_filename)) {
    // Create new database
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
      // initialize database using .sql script
      $result = $db->exec($init_sql);
      if ($result) {
        file_put_contents($init_checksum_filename, $init_checksum);
        return $db;
      }
    } catch (PDOException $exception) {
      // If we had an error, then the DB did not initialize properly,
      // so let's delete it!
      unlink($db_filename);
      throw $exception;
    }
  } else {
    // database was already initialized. Just open it!
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }

  return null;
}

// Execute a query ($sql) against a datbase ($db).
// Returns query results if query was successful.
// Returns null if query was not successful.
function exec_sql_query($db, $sql, $params = array())
{
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return null;
}

$db = open_or_init_sqlite_db('secure/gallery.sqlite', 'secure/init.sql');

//// SQL queries

// Returns records from the tags table with a certan type_id
function get_tags($db, $type_id)
{
  $sql = 'SELECT * FROM tags WHERE type_id = :type_id';
  $params = array(
    ':type_id' => $type_id
  );

  return exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
}

// Returns the image id and file extention of the images with a the tag $tag_id
function get_tag_images($tag_id, $db)
{
  $sql = "SELECT images.id, images.file_ext FROM images INNER JOIN image_tags ON image_tags.image_id = images.id INNER JOIN tags ON image_tags.tag_id = tags.id WHERE image_tags.tag_id = :tag_id";
  $params = array(
    ":tag_id" => $tag_id
  );

  return exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
}

// Returns all images in the database
function get_all_images($db)
{
  return exec_sql_query($db, "SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);
}

// Deletes image from images with id $deleteid
function delete_image_with_id($db, $deleteid)
{
  $deletesql = 'DELETE FROM images WHERE id = :id';
  $deleteparams = array(
    ':id' => $deleteid
  );
  exec_sql_query($db, $deletesql, $deleteparams);
}

// Deletes image tag with image id $deleteid
function delete_imagetag_with_imageid($db, $deleteid)
{
  $deletetagsql = 'DELETE FROM image_tags WHERE image_id = :id';
  $deletetagparams = array(
    ':id' => $deleteid
  );
  exec_sql_query($db, $deletetagsql, $deletetagparams);
}

// Deletes image tag with tag id $tagid
function delete_imagetag_with_tagid($db, $tagid)
{
  $deletesql = 'DELETE FROM image_tags WHERE tag_id = :id';
  $deleteparams = array(
    ':id' => $tagid
  );
  exec_sql_query($db, $deletesql, $deleteparams);
}

// Returns all tags in the database
function get_all_tags($db)
{
  $alltags_sql = "SELECT * FROM tags";
  return exec_sql_query($db, $alltags_sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Returns the current tag ids of the tags associated with image with image id $id
function current_tags_of_image($db, $id)
{
  $current_sql = 'SELECT tag_id FROM image_tags WHERE image_id = :id';
  return exec_sql_query($db, $current_sql, array(':id' => $id))->fetchAll(PDO::FETCH_ASSOC);
}

// Inserts new image tag with image_tag $imgid and tag_id $addtagid
function insert_image_tag($db, $imgid, $addtagid)
{
  $addtagsql = 'INSERT INTO image_tags (image_id, tag_id) VALUES (:image_id, :tag_id)';
  $addtagparams = array(
    ':image_id' => $imgid,
    ':tag_id' => $addtagid
  );
  exec_sql_query($db, $addtagsql, $addtagparams);
}

// Inserts  a newtag into tags with tag name $tag and type_id $type. Inserts image_tag with same tag_id and image_id $image_id
function create_tag($db, $image_id, $tag, $type)
{
  $newtagsql = 'INSERT INTO tags (tag, type_id) VALUES (:tag, :type)';
  exec_sql_query($db, $newtagsql, array(':tag' => $tag, ':type' => $type));
  $tag_id = $db->lastInsertId("id");

  $newimgtag_sql = 'INSERT INTO image_tags (image_id, tag_id) VALUES (:img_id, :tag_id)';
  $newimgtag_params = array(
    ':img_id' => $image_id,
    ':tag_id' => $tag_id
  );
  exec_sql_query($db, $newimgtag_sql, $newimgtag_params);
}

// Returns image with id $id
function get_image_with_id($db, $id)
{
  $art_sql = "SELECT * FROM images WHERE (id = :img_id )";
  $art_params = array(
    ':img_id' => strval($id)
  );
  return exec_sql_query($db, $art_sql, $art_params)->fetchAll(PDO::FETCH_ASSOC)[0];
}

// Returns artist with id $id
function get_artist_with_id($db, $id)
{
  $artist_sql = "SELECT name FROM artists WHERE (id = :id)";
  $artist_params = array(
    ':id' => strval($id)
  );
  return exec_sql_query($db, $artist_sql, $artist_params)->fetchAll(PDO::FETCH_ASSOC)[0]['name'];
}

// Returns tag information of all tags associated with image with id $id
function get_tags_of_image($db, $id)
{
  $tags_sql = "SELECT tags.tag, tags.id FROM tags INNER JOIN image_tags ON image_tags.tag_id = tags.id INNER JOIN images ON image_tags.image_id = images.id WHERE images.id = :imgid";
  $tags_params = array(
    ":imgid" => $id
  );
  return exec_sql_query($db, $tags_sql, $tags_params)->fetchAll(PDO::FETCH_ASSOC);
}

// Inserts a new artist with name $name
function insert_artist($db, $name)
{
  $artist_sql = "INSERT INTO artists (name) VALUES (:artist_name) ";
  $artist_params = array(':artist_name' => $name);
  exec_sql_query($db, $artist_sql, $artist_params);
}

// Inserts image
function insert_image($db, $file_name, $file_ext, $artist_id, $title, $width, $height, $description, $contact)
{
  $img_sql = "INSERT INTO images (file_name, file_ext, artist_id, title, width, height, description, contact) VALUES (:file_name, :file_ext, :artist_id, :title, :width, :height, :description, :contact)";
  $img_params = array(
    ':file_name' => $file_name,
    ':file_ext' => $file_ext,
    ':artist_id' => $artist_id,
    ":title" => $title,
    ':width' => $width,
    ':height' => $height,
    ':description' => $description,
    ':contact' => $contact
  );
  exec_sql_query($db, $img_sql, $img_params);
}
