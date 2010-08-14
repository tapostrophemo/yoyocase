<?php if (count($yoyos) == 0): ?>
  <p class="err">User "<?=$username?>" has no yoyos in their collection</p>
<?php else: ?>
  <p class="msg"><?=count($yoyos)?> yoyo<?=count($yoyos)>1?'s':''?> in <?=$username?>'s collection</p>
<script type="text/javascript" src="/res/yoxview/yoxview-init.js"></script>
<?php endif; ?>

<?php foreach ($yoyos as $yoyo): ?>
<div style="text-align:center" class="yoxview">
<?php if (count($yoyo->photos)): ?>
 <?php foreach ($yoyo->photos as $photo): ?>
 <a href="<?=$photo->url?>"><img src="/thumbs/<?=$photo->id?>.jpg" alt="" title="<?=$photo->url?>"/></a>
 <?php endforeach; ?>
<?php else: ?>
 <img src="/res/icon_unknown.png" alt="no photos"/>
<?php endif; ?>
 <br/><b><?=$yoyo->model_year?> <?=$yoyo->manufacturer?> <?=$yoyo->model_name?></b>
</div>
<br/><br/>
<?php endforeach; ?>

<p style="text-align:center"><a href="/galleries">view other yoyo collections</a></p>

