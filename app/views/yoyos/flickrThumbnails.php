<?php if (count($thumbnails) > 0): ?>

 <table width="100%" border="0" cellpadding="3" cellspacing="0">
  <tr>
  <?php $i = 0; foreach ($thumbnails as $p): ?>
   <td align="center">
    <input id="photo_<?=$i?>" type="checkbox" name="photos[]" value="<?=$p['url']?>"/>
    <br/>
    <img src="<?=$p['thumbnail']?>"/>
   </td>
   <?php $i++; if (($i % 3) == 0) echo '</tr><tr>'; ?>
  <?php endforeach; ?>
  </tr><tr>
  </tr>
 </table>

<?php else: ?>

 <p>You need to add some photos to your flickr photostream.</p>

<?php endif; ?>

