<?=validation_errors()?>

<h2>Register for an account</h2>

<?=form_open('register')?>
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
   <td><label for="username">*Username</label>&nbsp;</td>
   <td><input type="text" name="username" value="<?=set_value('username')?>"/></td>
  </tr>
  <tr>
   <td><label for="password">*Password</label>&nbsp;</td>
   <td><input type="password" name="password" value=""/></td>
  </tr>
  <tr>
   <td><label for="email">*Email</label>&nbsp;</td>
   <td><input type="text" name="email" value="<?=set_value('email')?>"/></td>
  </tr>
  <tr>
   <td colspan="2">
    <p>By registering for an account, you agree to abide by the following:</p>
    <div id="tosRegBlock">
     <?=$this->load->view('pages/tos')?>
    </div>
   </td>
  </tr>
 </table>
 <input type="submit" value="Register"/>
</form>

<br/>
<br/>

<p>Already registered? <a href="/login">Login.</a></p>

<div>
 <?php $this->load->view('site/facts', array('facts' => $facts)); ?>

 <br/>

 <p><b>Problems registering?</b></p>
 <ul>
  <li>All fields are required</li>
  <!-- TODO: more help -->
 </ul>
</div>
