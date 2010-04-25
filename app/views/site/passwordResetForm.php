<?=validation_errors()?>

<h2>Reset your password</h2>

<div id="gallery">

<div class="soloform">

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

</div>

</div>

<div id="sidebar"></div>

<div class="clearing"></div>

