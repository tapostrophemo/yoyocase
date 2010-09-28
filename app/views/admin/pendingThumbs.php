<h2>Images Without Thumbnails</h2>

<?php if (count($photos)): ?>

<table class="report" width="100%">
 <tr>
  <th>Id</th>
  <th>Url</th>
 </tr>
<?php foreach ($photos as $photo): ?>
 <tr>
  <td><?=$photo->id?></td>
  <td><?=htmlspecialchars($photo->url)?></td>
 </tr>
<?php endforeach; ?>
</table>

<?=form_open("admin/generateThumbnails/$max")?>
 <input type="submit" value="Generate Thumbnails"/>
</form>

<?php else: ?>

<p>All thumbnails are up to date.</p>

<?php endif; ?>

