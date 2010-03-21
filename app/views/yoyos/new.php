<?=validation_errors()?>

<h2>Add to your collection</h2>

<div id="gallery">
<?=form_open('yoyo')?>
 <table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
   <td><label for="manufacturer">Manufacturer</label></td>
   <td><input type="text" name="manufacturer" value="<?=set_value('manufacturer')?>"/></td>
  </tr>
  <tr>
   <td><label for="country">Country</label></td>
   <td><input type="text" name="country" value="<?=set_value('country')?>"/></td>
  </tr>
  <tr>
   <td><label for="model_year">Model year</label></td>
   <td><input type="text" name="model_year" size="4" maxlength="4" value="<?=set_value('model_year')?>"/></td>
  </tr>
  <tr>
   <td><label for="model_name">Model name</label></td>
   <td><input type="text" name="model_name" value="<?=set_value('model_name')?>"/></td>
  </tr>
  <tr>
   <td><label for="description">Description</label></td>
   <td><textarea name="description" rows="2" cols="25"><?=set_value('description')?></textarea></td>
  </tr>
  <tr>
   <td colspan="2">
    <input type="submit" value="Save"/>
    <input type="button" value="Cancel" onclick="document.location.href='<?=$cancel_url?>'"/>
   </td>
  </tr>

  <tr>
   <td colspan="2">

   <?php if (!($this->session->userdata('flickr_userid') || $this->session->userdata('photobucket_username'))): ?>

    <hr/>

    <p>Photo uploads are not implemented at <tt>yoyocase.net</tt>. However, if you have a flickr
     or Photobucket account, you can link photos from there to here.</p>
    <p>First, click "Save" on this screen. Then go to your account "preferences" screen and click
     either "Verify flickr account" or "Verify Photobucket account" and follow the prompts.</p>

   <?php else: ?>

    <?php $this->load->view('yoyos/thumbnailSelect', array(
            'flickrThumbnails' => flickr_thumbnails(),
            'photobucketThumbnails' => photobucket_thumbnails())); ?>

   <?php endif; ?>

   </td>
  </tr>
 </table>

 <div class="submit">
  <input type="submit" value="Save"/>
  <input type="button" value="Cancel" onclick="document.location.href='<?=$cancel_url?>'"/>
 </div>
</form>
</div>

<div id="sidebar">
 <?php $this->load->view('yoyos/sidebar', array('yoyos' => $yoyos)) ?>
</div>

<div class="clearing"></div>

