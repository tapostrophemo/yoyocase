<?=validation_errors()?>

<h2>Login to your account</h2>

<div id="gallery">

<div style="width:50%; margin:0 auto">

<?=form_open('login')?>
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
   <td><label for="username">Username</label>&nbsp;</td>
   <td><input type="text" name="username" value="<?=set_value('username')?>"/></td>
  </tr>
  <tr>
   <td><label for="password">Password</label>&nbsp;</td>
   <td><input type="password" name="password" value=""/></td>
  </tr>
  <tr>
   <td colspan="2"><input type="submit" value="Login"/></td>
  </tr>
 </table>
</form>

<br/>
<br/>

<p>Don't have an account yet? <a href="/register">Register</a> for one.</p>

</div>

</div>

<div id="sidebar">
 <small><a href="/passwordreset">Forgot your password?</a></small>
</div>

<div class="clearing"></div>

