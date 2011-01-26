<div id="slideshow">
<?php foreach ($this->Misc->randomThumbs() as $thumb): if (mt_rand(0, 42) > 41): ?>
 <img src="<?=site_url().'thumbs/'.$thumb?>" style="display:none"/>
<?php endif; endforeach; ?>
</div>

<script type="text/javascript">
var index = 0;
var slides = null;
var time = 450;

function playSlideshow() {
  for (var i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[index++ % slides.length].style.display = "block";
  setTimeout("playSlideshow()", time);
}

function startSlideshow() {
  slides = document.getElementById("slideshow").children;
  setTimeout("playSlideshow()", time);
};

startSlideshow();
</script>

