<h2>Review archives</h2>

<table>
 <tr>
  <th>Username&nbsp;(ID)</th>
  <th>Deletes</th>
  <th>Start date</th>
  <th>End date</th>
 </tr>
<?php foreach ($data as $row): ?>
 <tr>
  <td><?=$row['username'] ? $row['username'] : 'DELETED'?> (<?=$row['user_id']?>)</td>
  <td class="u-text-right"><a href="/admin/archives/<?=$row['user_id']?>"><?=$row['num_deletes']?></a></td>
  <td><?=$row['start_date']?></td>
  <td><?=$row['end_date']?></td>
 </tr>
<?php endforeach; ?>
</table>
