<h2>Archive detail</h2>

<?php if (count($data)): $row = $data[0]; ?>
<p>Username (ID): <?=$row['username'] ? $row['username'] : 'DELETED'?> (<?=$row['user_id']?>)</p>
<?php endif; ?>

<table>
 <tr>
  <th>ID</th>
  <th>Yoyo&nbsp;ID</th>
  <th>Archived&nbsp;on</th>
 </tr>
 <tr>
  <th colspan="3">Raw&nbsp;data</th>
 </tr>
<?php foreach ($data as $row): ?>
 <tr>
  <td><?=$row['id']?></td>
  <td><?=$row['yoyo_id']?></td>
  <td><?=$row['date']?></td>
 </tr>
 <tr>
  <td colspan="3"><pre style="width:625px; overflow:auto; font-size:9px"><?php print_r(unserialize($row['data']))?></pre></td>
 </tr>
<?php endforeach; ?>
</table>
