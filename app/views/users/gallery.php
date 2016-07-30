<?php if (count($yoyos) == 0): ?>
  <p>User "<?=$username?>" has no yoyos in their collection</p>
<?php else: ?>
  <section>
    <p><?=count($yoyos)?> yoyo<?=count($yoyos)>1?'s':''?> in <?=$username?>'s collection</p>
    <p>Similar collections:
      <?php $count = 0; foreach (similarCollections($user->id) as $userId => $arr): ?>
        <a href="/yoyos/<?= $arr['username'] ?>"><?= $arr['username'] ?></a>
      <?php $count++; if ($count > 5) break; endforeach; ?>
    </p>
  </section>

  <?php foreach ($yoyos as $yoyo): ?>
    <section>
      <a name="<?=$yoyo->id?>"></a>
      <div class="yoxview">
        <?php if (count($yoyo->photos)): ?>
          <?php foreach ($yoyo->photos as $photo): ?>
            <?php if (file_exists($_SERVER['DOCUMENT_ROOT'].'/thumbs/'.$photo->id.'.jpg')): ?>
              <a href="<?=$photo->url?>"><img src="/thumbs/<?=$photo->id?>.jpg" title="<?=$photo->url?>"/></a>
            <?php else: ?>
              <span class="thumb-pending"><a href="<?=$photo->url?>">icon&nbsp;pending...</a></span>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php else: ?>
          <img src="/res/icon_unknown.png" alt="no photos"/>
        <?php endif; ?>
        <p class="block-small u-justify">
          <label><?php echo $yoyo->model_year ?> <?php echo $yoyo->manufacturer ?> <?php echo $yoyo->model_name ?></label>
          <?php if ($yoyo->notes): ?>
            <span><?php echo nl2br($this->security->xss_clean($yoyo->notes)) ?></span>
          <?php endif; ?>
          <span class="d"><?php echo isset($yoyo->updated_at) ? $yoyo->updated_at : $yoyo->created_at ?></span>
        </p>
      </div>
    </section>
  <?php endforeach; ?>
  <script type="text/javascript" src="/res/yoxview/yoxview-init.js"></script>
<?php endif; ?>

<p class="u-text-center"><a href="/galleries">view other yoyo collections</a></p>
