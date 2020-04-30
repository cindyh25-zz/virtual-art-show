
function fetch_image(imgID) {

  // $.get("index.php", "&img_id=" + imgID, function (imgID) {
  //   document.getElementById("overlay-wrapper").style.display = "block";
  // });

  $.get("index.php", "&img_id=" + imgID, function (results) {
    $("#replace").html(results);
  }
  );

}

function closeOverlay() {
  alert("hi");
  document.getElementById("overlay-wrapper").style.display = "none";
}

function showSideMenu() {
  document.getElementById("sidemenu").style.display = "inline-block";
  document.getElementById("menuicon").style.display = "none";
}

function hideSideMenu() {

  document.getElementById("sidemenu").style.display = "none";
}

function showSettings() {
  document.getElementById("settingscontent").style.display = "inline-block";
}
