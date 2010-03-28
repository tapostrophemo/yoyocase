<h2>Galleries</h2>

<div id="gallery">
 <ul id="userList">
 <?php foreach ($users as $user): ?>
  <li>
   <a href="/yoyos/<?=urlencode($user->username)?>"><?=$user->username?></a>
   <small>(<?=$user->num_yoyos?> yo's, <?=$user->num_photos?> pics)</small>
  </li>
 <?php endforeach; ?>
 </ul>
</div>

<div id="sidebar">
 <?php $this->load->view('site/facts', array('facts' => $facts)); ?>
</div>

<div class="clearing"></div>

