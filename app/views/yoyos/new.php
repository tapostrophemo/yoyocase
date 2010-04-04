<script type="text/javascript" src="/res/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {

  $("#advShow").click(function () {
    jQuery.each($(".adv"), function (i, row) {
      row.className = "advDisplay";
    });
    $(this).hide();
    $("#advHide").show();
    this.parentNode.className = "expanded";
  });

  $("#advHide").click(function () {
    jQuery.each($(".advDisplay"), function (i, row) {
      row.className = "adv";
    });
    $(this).hide();
    $("#advShow").show();
    this.parentNode.className = "";
  });

});
</script>

<?=validation_errors()?>

<h2>Add to your collection</h2>

<div id="gallery">

<p id="advToggle">
 <a href="#" id="advShow">show advanced fields</a>
 <a href="#" id="advHide" style="display:none">hide advanced fields</a>
</p>

<?=form_open('yoyo')?>
 <table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
   <td><label for="model_name">*Model name</label></td>
   <td><input type="text" name="model_name" value="<?=set_value('model_name')?>"/></td>
  </tr>
  <tr>
   <td><label for="manufacturer">Manufacturer</label></td>
   <td><input type="text" name="manufacturer" value="<?=set_value('manufacturer')?>"/></td>
  </tr>
  <tr class="adv">
   <td></td>
   <td class="advField">
    <label for="mod">Modded by</label>
    <input type="text" name="mod" value="<?=set_value('mod')?>"/>
   </td>
  </tr>
  <tr>
   <td><label for="country">Country</label></td>
   <td><input type="text" name="country" value="<?=set_value('country')?>"/></td>
  </tr>
  <tr>
   <td><label for="model_year">Model year</label></td>
   <td><input type="text" name="model_year" size="4" maxlength="4" value="<?=set_value('model_year')?>"/></td>
  </tr>
  <tr class="adv">
   <td><label for="serialnum">Serial number</label></td>
   <td><input type="text" name="serialnum" value="<?=set_value('serialnum')?>"/></td>
  </tr>
  <tr class="adv">
   <td><label for="condition">Condition</label></td>
   <td><select name="condition">
        <option value=""></option>
        <option value="mint" <?=set_select('condition', 'mint')?>>Mint</option>
        <option value="excellent" <?=set_select('condition', 'excellent')?>>Excellent</option>
        <option value="good" <?=set_select('condition', 'good')?>>Good</option>
        <option value="fair" <?=set_select('condition', 'fair')?>>Fair</option>
        <option value="poor" <?=set_select('condition', 'poor')?>>Poor</option>
       </select></td>
  </tr>
  <tr class="adv">
   <td><label for="value">Value</label>&nbsp;<small>($)</small></td>
   <td><input type="text" name="value" size="10" value="<?=set_value('value')?>"/>
  </tr>
  <tr class="adv">
   <td><label for="acq_date">Acquired on</label>&nbsp;<small>(yyyy-mm-dd)</small></td>
   <td class="advField">
    <input type="text" name="acq_date" size="10" maxlength="10" value="<?=set_value('acq_date')?>"/>
    <br/>
    <label>by:</label> <input type="radio" name="acq_type" value="purchase"/><label>purchase</label>&nbsp;&nbsp;
    <input type="radio" name="acq_type" value="trade"/><label>trade</label>
    <input type="radio" name="acq_type" value="gift"/><label>gift</label>
    <br/>
    <label for="acq_party">from:</label>
    <input type="text" name="acq_party" value="<?=set_value('acq_party')?>"/>
    <br/>
    <label for="acq_price">price:</label>
    <input type="text" name="acq_price" size="10" value="<?=set_value('acq_price')?>"/>
   </td>
  </tr>
  <tr>
   <td><label for="notes">Notes</label></td>
   <td><textarea name="notes" rows="2" cols="25"><?=set_value('notes')?></textarea></td>
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

