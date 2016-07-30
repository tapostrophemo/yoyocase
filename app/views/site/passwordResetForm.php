<?=validation_errors()?>

<h2>Reset your password</h2>

<?=form_open('newpass')?>
<table>
 <tr>
  <td><label for="password">New password</label></td>
  <td><input type="password" name="password"/></td>
 </tr>
 <tr>
  <td><label for="passconf">Confirm&nbsp;password</label></td>
  <td><input type="password" name="passconf"/></td>
 </tr>
 <tr>
  <td colspan="2"><input type="submit" value="Reset password"/></td>
 </tr>
</table>
</form>
