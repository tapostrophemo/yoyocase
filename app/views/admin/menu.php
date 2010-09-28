<h2>Site administration</h2>

<ul>
 <li><a href="/admin/accounts">user accounts</a></li>
 <li><a href="/admin/registrationActivationReport">registration/activation report</a></li>
<?php if ($this->input->server('SERVER_NAME') == 'dev.yoyocase.net'): ?>
 <li><a href="/admin/checkThumbnails">check thumbnails</a></li>
<?php endif; ?>
</ul>

