<h2>Images without thumbnails</h2>

<?php if (count($photos)): ?>

<table class="report">
 <tr>
  <th>ID</th>
  <th>Username</th>
  <th>Yoyo</th>
  <th>URL</th>
 </tr>
<?php foreach ($photos as $photo): ?>
 <tr>
  <td><?=$photo->id?></td>
  <td><?=$photo->username?></td>
  <td><?=$photo->model_name?></td>
  <td><?=htmlspecialchars($photo->url)?></td>
 </tr>
<?php endforeach; ?>
</table>

<!--
<?=form_open("admin/generateThumbnails/$max")?>
 <input type="submit" value="Generate Thumbnails"/>
</form>
-->

<?php else: ?>

<p>All thumbnails are up to date.</p>

<?php endif; ?>

