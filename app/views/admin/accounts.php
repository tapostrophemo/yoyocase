<h2>Accounts</h2>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="report">
 <tr>
  <th>Username</th>
  <th>Registered on</th>
  <th>Last login</th>
  <th>Email</th>
 </tr>
<?php foreach ($accounts as $account): ?>
 <tr>
  <td><?=$account->username?> <small>(<?=$account->num_yoyos?> yo's, <?=$account->num_photos?> pics)</small></td>
  <td><?=$account->created_at?></td>
  <td><?=$account->last_login_at?></td>
  <td><?=$account->email?></td>
 </tr>
<?php endforeach; ?>
</table>

<br/>

<p><a href="/admin/accounts">(refresh)</a></p>

