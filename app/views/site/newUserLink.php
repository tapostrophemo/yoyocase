<?php
if ($this->session->userdata('logged_in') && $this->session->flashdata('new_user')):
  $this->session->keep_flashdata('new_user');
?>

 <p><small><a href="/help" class="lsf-icon help">Instructions for new users...</a></small></p>

<?php endif; ?>

