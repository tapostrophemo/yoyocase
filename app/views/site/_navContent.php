<ul>
  <?php if ($this->session->userdata('logged_in')): ?>
    <li><a href="/yoyos" class="lsf-icon collection">collection</a></li>
    <li><a href="/preferences" class="lsf-icon preferences">preferences</a></li>
    <?php if ($this->session->userdata('is_admin')): echo '<li><a href="/admin" class="lsf-icon admin">site admin</a></li>'; endif; ?>
    <li><a href="/galleries" class="lsf-icon galleries">galleries</a></li>
    <li><a href="/logout" class="lsf-icon logout">logout</a></li>
  <?php else: ?>
    <li><a href="/register" class="lsf-icon register">register</a></li>
    <li><a href="/galleries" class="lsf-icon galleries">galleries</a></li>
    <li><a href="/login" class="lsf-icon login">login</a></li>
  <?php endif; ?>
</ul>
