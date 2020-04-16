<!DOCTYPE html>

<html lang="en">
<?php
include("includes/init.php");
include("includes/head.php");
?>

<body>
  <!-- <div class="add-form-block">
      <label for="contact" class="add-label">Contact Information</label>
      <p class="metadata">How people can reach you (LinkedIn profile, email...)</p>
      <input type="text" name="contact" value=<?php echo $new_contact; ?>>
      <p class="feedback <?php echo $contact_feedback; ?>">You must provide a way for people to contact you about referrals.</p>
    </div> -->

  <a href="index.php">
    <h1>Virtual Art Show</h1>
  </a>

  <div class="topnav">
    <div id="links">
      <a href="about.php">About</a>
      <a href="add.php" class="button">Submit Artwork</a>
    </div>
  </div>

  <h2>Submit Your Artwork</h2>

  <form id="addform" method="POST" action="add.php" enctype="multipart/form-data" novalidate>
    <div class="forminput">
      <input type="hidden" name="MAX_FILE_SIZE" VALUE="1000000" />
      <label for="upload">Upload image</label>
      <input type="file" accept="image/*" name="upload" />
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
      <label>Size</label>
      <input type="text" name="width" class="inline smallinput" placeholder="Width (ex. 11 in.)" />
      <span> x </span>
      <input type="text" name="height" class="inline smallinput" placeholder="Height (ex. 14 in.)" />
    </div>
    <div class="forminput">
      <label for="description">Description or Artist's Statement</label>
      <textarea name="description" rows="6" cols="50"></textarea>
    </div>
    <div class="forminput">
      <input type="submit" />
    </div>
  </form>

</body>

</html>
