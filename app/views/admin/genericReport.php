<h3><?=$title?></h3>

<?php if (isset($note)): ?>
<p>Note: <?=$note?></p>
<?php endif; ?>

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

<br/>

