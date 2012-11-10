<h2>Yoyo collecting for everyone</h2>

<div id="gallery">

<h3>Put your yoyo collection online to share with friends, traders, and other yoyo enthusiasts.</h3>

<p><tt>yoyocase.net</tt> is a website where you can show off and manage your yoyo collection. The
 site is new and growing, but already you can:</p>
<ul>
 <li>post yoyo details (manufacturer, model name, notes, etc.)</li>
 <li>include advanced details (cost, estimated value, condition, etc.)</li>
 <li>link pictures of your collection from flickr or Photobucket accounts</li>
 <li>view photo galleries of other users' collections</li>
</ul>

<br/>

<span style="font-size:17px; border:2px solid #B24D17; border-radius:5px; text-align:center; padding:7px 15px">
 <a href="/register">Sign-up for a free account →</a>
</span>

<br/><br/><br/>

<p>Not sure if you should join? View some of the <a href="/tour">features</a> of the site.</p>

<br/><br/>

<p><small>Note: this site is focused only on yo-yos and yo-yo related items. Postings with illegal
 or inappropriate material can and may be removed by the site operators.</small></p>

</div>

<div id="sidebar">

<div id="smallLogin">
 <p><small>Current users, login</small></p>
 <?=form_open('login')?>
  <label for="username">Username</label><br/>
  <input type="text" name="username"/><br/>
  <label for="password">Password</label><br/>
  <input type="password" name="password"/><br/>
  <input type="submit" value="Login"/>
 </form>
 <br/>
 <p><a href="/passwordreset">Forgot your password?</a></p>
</div>

<?php $this->load->view('yoyos/randomThumb'); ?>

</div>

<div class="clearing"></div>

