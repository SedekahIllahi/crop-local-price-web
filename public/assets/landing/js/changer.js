/* Style Changer */

$(document).ready(function () {
  $("#stlChanger").click(function (e) {
    e.preventDefault(); // stop the # link from reloading/jumping
    $(this).toggleClass("open");
  });
});
