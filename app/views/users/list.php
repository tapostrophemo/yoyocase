<h2>Galleries</h2>

<ul>
<?php foreach ($users as $user): ?>
 <li><a href="/yoyos/<?=$user->username?>"><?=$user->username?></a></li>
<?php endforeach; ?>
</ul>

