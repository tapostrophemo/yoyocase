<div id="flickr" class="thumbnails">
 <hr/>

<?php if (!$this->session->userdata('flickr_userid')): ?>
 <p>Go to the <a href="/preferences">preferences</a> screen to verify your flickr account.</p>
<?php endif; ?>

<?php if (count($flickrThumbnails) > 0): ?>
 <p><label>Link photos from your flickr photostream?</label></p>
 <table width="100%" border="0" cellpadding="3" cellspacing="0">
  <tr>
  <?php $i = 0; foreach ($flickrThumbnails as $p): ?>
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
 <?php if ($this->session->userdata('flickr_userid')): ?>
 <p>Your flickr account is authorized, but you need to add some photos to your photostream.</p>
 <?php endif; ?>
<?php endif; ?>
</div>


<div id="photobucket" class="thumbnails">
 <hr/>

<?php if (!$this->session->userdata('photobucket_username')): ?>
 <p>Go to the <a href="/preferences">preferences</a> screen to verify your Photobucket account.</p>
<?php endif; ?>

<?php if (count($photobucketThumbnails) > 0): ?>
 <p><label>Link photos from your Photobucket albums?</label></p>
 <table width="100%" border="0" cellpadding="3" cellspacing="0">
  <tr>
  <?php $i = 0; foreach ($photobucketThumbnails as $p): ?>
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
 <?php if ($this->session->userdata('photobucket_username')): ?>
 <p>Your Photobucket account is authorized, but you need to add some photos to your albums.</p>
 <?php endif; ?>
<?php endif; ?>
</div>

