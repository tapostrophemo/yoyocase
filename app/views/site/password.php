<?=validation_errors()?>

<h2>Reset your password</h2>

<div id="gallery">

<div class="soloform">

<p>Enter your username below and click the button. An email will be sent to your registered email
 address containing instructions for re-entering <tt>yoyocase.net</tt>.</p>

<br/>

<?=form_open('passwordreset')?>
<table>
 <tr>
  <td><label for="username">Username</label></td>
  <td><input type="text" name="username" value="<?=set_value('username')?>"/></td>
 </tr>
 <tr>
  <td colspan="2"><input type="submit" value="Reset password"/></td>
 </tr>
</table>
</form>

<br/>
<br/>
<br/>

<p>Remember your password? <a href="/login">Login</a>.</p>

</div>

</div>

<div id="sidebar"></div>

<div class="clearing"></div>

