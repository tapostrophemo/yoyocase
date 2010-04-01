<h2><?=$yoyo->model_year?> <?=$yoyo->manufacturer?> <?=$yoyo->model_name?></h2>

<div id="gallery">

<table border="0" cellspacing="0" cellpadding="0" width="100%">
 <tr>
  <td><label>Manufacturer</label></td>
  <td><?=$yoyo->manufacturer?></td>
 </tr>
 <tr>
  <td><label>Country</label></td>
  <td><?=$yoyo->country?></td>
 </tr>
 <tr>
  <td><label>Model year</label></td>
  <td><?=$yoyo->model_year?></td>
 </tr>
 <tr>
  <td><label>Model name</label></td>
  <td><?=$yoyo->model_name?></td>
 </tr>
 <tr>
  <td><label>Condition</label></td>
  <td><?=$yoyo->condition?></td>
 </tr>
 <tr>
  <td><label>Notes</label></td>
  <td><?=$yoyo->notes?></td>
 </tr>
</table>

<br/>

<?=form_open("yoyo/{$yoyo->id}/edit", array('method' => 'GET'))?>
 <input type="submit" value="Edit"/>
 <input type="button" value="Back to collection" onclick="document.location.href='<?=$cancel_url?>'"/>
</form>

<hr/>

<?php $i = 1; foreach ($photos as $photo): ?>
<b>Pic <?=$i?></b><br/>
<img src="<?=$photo->url?>"/>
<?php $i++; if ($i <= count($photos)): echo '<br/>'; endif; ?>
<?php endforeach; ?>

<br/>

<?=form_open("yoyo/{$yoyo->id}/edit", array('method' => 'GET'))?>
 <input type="submit" value="Edit"/>
 <input type="button" value="Back to collection" onclick="document.location.href='<?=$cancel_url?>'"/>
</form>

</div>

<div id="sidebar">
 <?php $this->load->view('yoyos/sidebar', array('yoyos' => $yoyos)) ?>
</div>

<div class="clearing"></div>

