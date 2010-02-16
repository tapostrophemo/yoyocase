<?php
$username = htmlspecialchars($username);
?>

<?php if (count($yoyos) == 0): ?>
  <p class="err">User "<?=$username?>" has no yoyos in their collection</p>
<?php else: ?>
  <p class="msg"><?=count($yoyos)?> yoyo(s) collected by "<?=$username?>"</p>
<?php endif; ?>

<!-- TODO: side-scroller/animation -->
<?php foreach ($yoyos as $yoyo): ?>
<div style="text-align:center">
 <img src="<?=isset($yoyo->first_photo) ? $yoyo->first_photo : '/res/icon_unknown.png'?>" alt=""/><br/>
 <b><?=$yoyo->model_year?> <?=$yoyo->manufacturer?> <?=$yoyo->model_name?></b>
</div>
<?php endforeach; ?>

