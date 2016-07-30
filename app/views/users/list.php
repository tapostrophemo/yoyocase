<h2>Collector's galleries</h2>

<ul id="userList">
 <?php foreach ($users as $user): ?>
  <li>
   <a href="/yoyos/<?=urlencode($user->username)?>"><?=$user->username?></a>
   <small>(<?=$user->num_yoyos?> yo's, <?=$user->num_photos?> pics)</small>
  </li>
 <?php endforeach; ?>
</ul>

<div>
 <?php $this->load->view('site/facts', array('facts' => $facts)); ?>
 <?php $this->load->view('yoyos/randomThumb'); ?>
</div>
