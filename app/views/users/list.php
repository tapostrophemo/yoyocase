<h2>Galleries</h2>

<div id="gallery">
 <ul id="userList">
 <?php foreach ($users as $user): ?>
  <li><a href="/yoyos/<?=$user->username?>"><?=$user->username?></a></li>
 <?php endforeach; ?>
 </ul>
</div>

<div id="sidebar">
 <?php $this->load->view('site/facts', array('facts' => $facts)); ?>
</div>

<div class="clearing"></div>

