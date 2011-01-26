<h2>Welcome to yoyocase.net</h2>

<div id="gallery">

<p>What is <tt>yoyocase.net</tt>? It is a website that lets you manage your yoyo collection online.
 Managing your collection online means you'll be able to easily share your collection with
 friends, traders, and other yoyo enthusiasts.</p>

<br/>

<p>The site is new and growing, but already you can:</p>
<ul>
 <li>link pictures of your collection from flickr or Photobucket accounts</li>
 <li>post yoyo details (manufacturer, model name, notes, etc.)</li>
 <li>include advanced details (cost, estimated value, condition, etc.)</li>
 <li>view photo galleries of other users' collections</li>
</ul>

<br/>

<p>Some of the features planned for <tt>yoyocase.net</tt> include:</p>
<ul>
 <li>option to upload your photos directly to this site</li>
 <li>link pictures from other popular image hosting sites</li>
 <li>keep in touch with other yoyo enthusiasts</li>
</ul>

<br/>

<p><a href="/tour">Tour some of the features</a> of the site.</p>

<br/>

<p><small>Note: this site is focused only on yo-yos and yo-yo related items. Postings with illegal
 or inappropriate material can and may be removed by the site operators.</small></p>

</div>

<div id="sidebar">

<div id="smallLogin">
 <p><a href="/register">Register for an account</a></p>
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

