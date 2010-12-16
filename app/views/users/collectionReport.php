<h2>Collection report</h2>

<p>
 Number of yoyos: <?=$summary->num ? $summary->num : 0?>
 <br/>
 Total paid for yoyos: $<?=$summary->price ? $summary->price : 0?>
 <br/>
 Total value of yoyos: $<?=$summary->value ? $summary->value : 0?>
</p>

<table class="report">
<thead>
 <tr>
  <th>Year</th>
  <th>Manufacturer (Country)</th>
  <th>Model</th>
  <th>Value</th>
  <th>Condition</th>
  <th>Serial #</th>
  <th>Acquired</th>
  <th>From</th>
  <th>Price</th>
  <th>Notes</th>
</thead>
<tbody>
<?php foreach ($details as $yoyo): ?>
 <tr>
  <td><?=$yoyo->model_year?></td>
  <td><?=$yoyo->manufacturer?><?php if ($yoyo->country) print " ({$yoyo->country})"; ?></td>
  <td><?=$yoyo->model_name?></td>
  <td><?php if ($yoyo->value) print '$'.$yoyo->value; ?></td>
  <td><?=$yoyo->condition?></td>
  <td><?=$yoyo->serialnum?></td>
  <td><?=$yoyo->acq_type?> <?=$yoyo->acq_date?></td>
  <td><?=$yoyo->party?></td>
  <td><?php if ($yoyo->price) print '$'.$yoyo->price; ?></td>
  <td><?=$yoyo->notes?></td>
 </tr>
<?php endforeach; ?>
</tbody>
</table>

