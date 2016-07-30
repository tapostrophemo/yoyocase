<?=validation_errors()?>

<h2>Edit preferences</h2>

<?=form_open("/preferences") ?>
  <table border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td style="line-height:2.25em"><label for="username">Username</label></td>
    <td style="line-height:2.25em"><?=$user->username?></td>
   </tr>
   <tr>
    <td><label for="email">Email</label></td>
    <td><input type="text" name="email" value="<?=$user->email?>"/></td>
   </tr>
   <tr>
    <td><label for="password">Password</label></td>
    <td><input type="password" name="password" value=""/></td>
   </tr>
   <tr>
    <td><label for="passconf">Confirm Password</label></td>
    <td><input type="password" name="passconf" value=""/></td>
   </tr>
  </table>
  <input type="submit" value="Update"/>
  <a class="button" href="/account">Cancel</a>
</form>

<hr/>

<?=form_open("/update_flickr_info_1")?>
  <p><small>Clicking this button will redirect your browser to flickr's site to verify your
   flickr account and to give <tt>yoyocase.net</tt> permission to read from that account.</small></p>
  <input type="submit" value="Verify flickr account"/><br/><br/>
  <p><small>Note: If the following is filled in, you have successfully verified your flickr account,
   and unless that account has changed, you don't need to re-verify it:</small></p>
  <label>flickr user id:</label> <?=$user->flickr_userid?>
</form>

<hr/>

<?=form_open('/update_photobucket_info_1')?>
  <p><small>Clicking this button will redirect your browser to Photobucket's site to verify your
   Photobucket account and give <tt>yoyocase.net</tt> permission to read from that account.</small></p>
  <input type="submit" value="Verify Photobucket account"/><br/><br/>
  <p><small>Note: If the following is filled in, you have successfully verified your Photobucket account,
   and unless that account has changed, you don't need to re-verify it:</small></p>
  <label>Photobucket username:</label> <?=$user->photobucket_username?>
</form>
