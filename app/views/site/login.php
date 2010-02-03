<?=validation_errors()?>

<h2>Login</h2>

<?=form_open('login')?>
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
   <td colspan="2"><input type="submit" value="Login"/></td>
  </tr>
 </table>
</form>

