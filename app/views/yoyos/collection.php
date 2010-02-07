<h2>Your collection</h2>

<div style="display:none"><!-- HACK: preload iamges -->
 <img src="/res/icon_unknown.png" alt=""/>
 <img src="/res/icon_unknown_highlight.png" alt=""/>
</div>

<div id="gallery">
<?php if (count($yoyos) == 0): ?>
 <p>You don't have any yoyos in your collection.</p>
 <p><a href="/yoyo">Add one?</a></p>
 <p><a href="/yoyo"><img src="/res/icon_unknown.png" alt="" onmouseover="this.src='/res/icon_unknown_highlight.png'" onmouseout="this.src='/res/icon_unknown.png'"/></a></p>
<?php else: ?>
 <ul id="collectionList">
 <?php foreach ($yoyos as $yoyo): ?>
  <li>
   <a href="/yoyo/<?=$yoyo->id?>">
   <?php if (empty($yoyo->first_photo)): ?>
    <img src="/res/icon_unknown.png" alt=""/>
   <?php else: ?>
    <img src="/res/cornerOverlay.gif" alt=""
         style="background: #aeaeae url(<?=$yoyo->first_photo?>) 45% 45% no-repeat"/>
   <?php endif; ?>
   </a>
   <br/>
   <a href="/yoyo/<?=$yoyo->id?>">
    <?=$yoyo->model_year?> <?=$yoyo->manufacturer?> <?=$yoyo->model_name?>
   </a>
  </li>
 <?php endforeach; ?>

  <li>
   <a href="/yoyo"><img src="/res/icon_unknown.png" alt="" onmouseover="this.src='/res/icon_unknown_highlight.png'" onmouseout="this.src='/res/icon_unknown.png'"/></a>
   <br/>
   <a href="/yoyo">Add anoter?</a>
  </li>
 </ul>
<?php endif; ?>
</div>

<div id="sidebar">
 <?php $this->load->view('yoyos/sidebar', array('yoyos' => $yoyos)) ?>
</div>

<div class="clearing"></div>

