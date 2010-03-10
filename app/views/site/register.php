<?=validation_errors()?>

<h2>Register for an account</h2>

<div id="gallery">

<?=form_open('register')?>

 <table border="0" cellpadding="0" cellspacing="0" width="50%" align="center">
  <tr>
   <td><label for="username">Username</label>&nbsp;</td>
   <td><input type="text" name="username" value="<?=set_value('username')?>"/></td>
  </tr>
  <tr>
   <td><label for="password">Password</label>&nbsp;</td>
   <td><input type="password" name="password" value=""/></td>
  </tr>
  <tr>
   <td><label for="email">Email</label>&nbsp;</td>
   <td><input type="text" name="email" value="<?=set_value('email')?>"/></td>
  </tr>
  <tr>
   <td colspan="2"><input type="submit" value="Register"/></td>
  </tr>
 </table>
</form>

</div>

<div id="sidebar">
 <p><b><tt>yoyocase.net</tt> facts:</b></p>
 <ul>
  <li><?=$num_users?> user accounts</li>
  <li><?=$num_yoyos?> yoyos</li>
  <li><?=$num_photos?> photos</li>
  <!--li>TODO: more "fun site facts": largest collection, newest member, newest photo, etc.</li-->
 </ul>

 <br/>

 <p><b>Problems registering?</b></p>
 <ul>
  <li>All fields are required</li>
  <!-- TODO: more help -->
 </ul>
</div>

<div class="clearing"></div>

