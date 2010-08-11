<h2><?=$title?></h2>

<p>Note: <?=$note?></p>

<table class="report" width="100%">
 <tr>
 <?php foreach ($fields as $column => $title): ?>
  <th><?=$title?></th>
 <?php endforeach; ?>
 </tr>
<?php foreach ($data as $row): ?>
 <tr>
 <?php foreach ($fields as $column => $title): ?>
  <td><?=$row[$column]?></td>
 <?php endforeach; ?>
 </tr>
<?php endforeach; ?>
</table>

