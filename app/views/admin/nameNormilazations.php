<h2>Normalized names</h2>

<?php if (count($manufacturers['new']) > 0 || count($models['new']) > 0): ?>

<fieldset>
 <h3>Pending</h3>

 <div style="width:50%; float:left">
  <b>Manufacturers</b>
  <ul>
  <?php foreach ($manufacturers['new'] as $newMfr): ?>
   <li><a href="/admin/normalize/mfr" data-raw="<?=urlencode($newMfr)?>"><?=htmlspecialchars($newMfr)?></a></li>
  <?php endforeach; ?>
  </ul>
 </div>

 <div style="width:50%; float:right">
  <b>Models</b>
  <ul>
  <?php foreach ($models['new'] as $newModel): ?>
   <li><a href="/admin/normalize/model" data-raw="<?=$newModel?>"><?=$newModel?></a></li>
  <?php endforeach; ?>
  </ul>
 </div>
</fieldset>

<div class="clearing"></div>

<div id="nameDialog" class="rounded dialog">
 <a href="#" class="dialogClose" onclick="$('#nameDialog').hide(); return false;">close</a>
 <div id="nameContent"></div>
</div>

<script type="text/javascript" src="/res/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  jQuery.each($("fieldset a"), function (i, link) {
    $(link).click(function () {
      $("#nameContent").html('<div class="loading"><img src="/res/wait.gif" alt="loading..."></div>');
      var data = $(link).attr("data-raw");
      $("#nameContent").load(link.href, { "raw": data });
      var middleOffset = Math.round($(window).height()/2 + $(document).scrollTop() - $("#userDetailDialog").height()/2 - parseInt($("body").css("margin-bottom"))/2);
      $("#nameDialog").css({"top": middleOffset+"px"}).show();
      return false;
    });
  });
});
</script>

<?php else: ?>

<p>All manufacturer- or model-name normalizations are up to date.</p>

<?php endif; ?>

<div style="width:50%; float:left">
 <h3>Manufacturers</h3>
 <ul>
 <?php foreach ($manufacturers['current'] as $mfr): ?>
  <li><?= $mfr ?></li>
 <?php endforeach; ?>
 </ul>
</div>

<div style="width:50%; float:right">
 <h3>Models</h3>
 <ul>
 <?php foreach ($models['current'] as $model): ?>
  <li><?= $model ?></li>
 <?php endforeach; ?>
 </ul>
</div>

<div class="clearing"></div>
