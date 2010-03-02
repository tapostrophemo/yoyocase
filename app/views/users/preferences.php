<?=validation_errors()?>

<h2>Edit preferences</h2>

<div id="gallery">

 <?=form_open("/preferences") ?>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
    <td><label for="username">Username</label></td>
    <td><?=$user->username?></td>
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
   <tr>
    <td colspan="2"><input type="submit" value="Update"/></td>
   </tr>
  </table>
 </form>

 <hr/>

 <?=form_open("/update_flickr_info_1")?>
  <input type="submit" value="Verify flickr account"/>
  <br/>
  <small>Note: clicking this button will redirect your browser to flickr's site to verify your
   flickr account and to give yoyocase.net permission to read from your flickr account.<br/><br/>
   Note 2: If the following is filled in, you have successfully verified your flickr account,
   and unless your flickr account has changed, you don't need to re-verify it:<br/>
   user_id: <?=$user->flickr_userid?>
  </small>
 </form>

 <br/><br/>
 <input type="button" value="Cancel" onclick="document.location.href='/account'"/>
</div>

<div id="sidebar">
</div>

<div class="clearing"></div>

