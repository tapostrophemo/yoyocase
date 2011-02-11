<div id="slideshow">
<?php foreach ($this->Misc->randomThumbs() as $thumb): ?>
 <img src="/thumbs/<?=$thumb?>"/>
<?php endforeach; ?>
</div>

<script type="text/javascript">
var playSlideshow = (function () {
  var index = 0;
  var slides = document.getElementById("slideshow").children;

  return function () {
    for (var i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    slides[index++ % slides.length].style.display = "block";
    setTimeout("playSlideshow()", 450);
  };
})();
playSlideshow();
</script>

