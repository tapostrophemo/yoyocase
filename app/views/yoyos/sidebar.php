<b>Collection facts:</b>
<ul>
 <li><?=count($yoyos)?> yoyo(s) total</li>
</ul>

<?php if ($this->uri->segment(1) && $this->uri->segment(1) == 'yoyo' && $this->uri->segment(2)): ?>
<a href="/yoyos/<?=urlencode($this->session->userdata('username'))?>#<?=$this->uri->segment(2)?>" target="blank">view this in gallery</a>
<?php else: ?>
<a href="/yoyos/<?=urlencode($this->session->userdata('username'))?>" target="blank">view gallery</a>
<?php endif; ?>
<br/>
<a href="/yoyoreport">collection report</a>

