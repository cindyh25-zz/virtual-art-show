
function fetch_image(imgID) {

  // $.get("index.php", "&img_id=" + imgID, function (imgID) {
  //   document.getElementById("overlay-wrapper").style.display = "block";
  // });

  $.get("index.php", "&img_id=" + imgID, function (results) {
    $("#replace").html(results);
  }
  );

}
