<h2 class="u-text-center">Yoyo collecting for everyone</h2>

<?php $this->load->view('yoyos/randomThumb'); ?>

<h3 class="u-text-center">Put your yoyo collection online to share with friends, traders, and other yoyo enthusiasts.</h3>

<p><tt>yoyocase.net</tt> is a website where you can show off and manage your yoyo collection. The
 site is new and growing, but already you can:</p>
<ul>
 <li>post yoyo details (manufacturer, model name, notes, etc.)</li>
 <li>include advanced details (cost, estimated value, condition, etc.)</li>
 <li>link pictures of your collection from flickr or Photobucket accounts</li>
 <li>view photo galleries of other users' collections</li>
</ul>

<br/>

<a class="button button-primary six columns offset-by-two" href="/register">Sign-up for a free account →</a>

<br/><br/><br/>

<p>Not sure if you should join? View some of the <a href="/tour">features</a> of the site.</p>

<br/><br/>

<p><small>Note: this site is focused only on yo-yos and yo-yo related items. Postings with illegal
 or inappropriate material can and may be removed by the site operators.</small></p>

</div>

<div>
 <p><small>Current users, login</small></p>
 <?=form_open('login')?>
  <label for="username">Username</label>
  <input type="text" name="username"/>
  <label for="password">Password</label>
  <input type="password" name="password"/>
  <br/>
  <input type="submit" value="Login"/>
 </form>
 <a href="/passwordreset">Forgot your password?</a>
</div>
