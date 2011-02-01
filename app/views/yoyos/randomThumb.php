<div id="slideshow">
<?php foreach ($this->Misc->randomThumbs() as $thumb): if (mt_rand(0, 42) > 41): ?>
 <img src="<?=site_url().'thumbs/'.$thumb?>"/>
<?php endif; endforeach; ?>
</div>

<script type="text/javascript">
var playSlideshow = (function () {
  var index = 0;
  var slides = document.getElementById("slideshow").children;
  var time = 450;
  setTimeout("playSlideshow()", time);

  return function () {
    for (var i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    slides[index++ % slides.length].style.display = "block";
    setTimeout("playSlideshow()", time);
  };
})();
</script>

