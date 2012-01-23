<?php if (count($yoyos) == 0): ?>
  <p class="err miniRound">User "<?=$username?>" has no yoyos in their collection</p>
<?php else: ?>
  <p class="msg miniRound"><?=count($yoyos)?> yoyo<?=count($yoyos)>1?'s':''?> in <?=$username?>'s collection</p>
<script type="text/javascript" src="/res/yoxview/yoxview-init.js"></script>
<?php endif; ?>

<div id="galleryDetail">
<?php foreach ($yoyos as $yoyo): ?>
 <a name="<?=$yoyo->id?>"></a>
 <div class="yoxview">
 <?php if (count($yoyo->photos)): ?>
  <?php foreach ($yoyo->photos as $photo): ?>
   <?php if (file_exists($_SERVER['DOCUMENT_ROOT'].'/thumbs/'.$photo->id.'.jpg')): ?>
    <a href="<?=$photo->url?>"><img src="/thumbs/<?=$photo->id?>.jpg" alt="" title="<?=$photo->url?>"/></a>
   <?php else: ?>
    <span class="thumbPending"><a href="<?=$photo->url?>">icon&nbsp;pending...</a></span>
   <?php endif; ?>
  <?php endforeach; ?>
 <?php else: ?>
  <img src="/res/icon_unknown.png" alt="no photos"/>
 <?php endif; ?>
  <p><label><?=$yoyo->model_year?> <?=$yoyo->manufacturer?> <?=$yoyo->model_name?></label>
  <?php if ($yoyo->notes): ?>
   <span><?=nl2br($this->security->xss_clean($yoyo->notes))?></span>
  <?php endif; ?>
   <span class="d"><?=isset($yoyo->updated_at) ? $yoyo->updated_at : $yoyo->created_at?></span>
  </p>
 </div>
<?php endforeach; ?>
</div><!-- /#gallery -->

<p style="text-align:center"><a href="/galleries">view other yoyo collections</a></p>

