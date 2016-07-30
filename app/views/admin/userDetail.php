<table>
 <tr><td><label>ID</label></td><td><?=$user->id?></td></tr>
 <tr><td><label>Username</label></td><td><?=$user->username?></td></tr>
 <tr><td><label>Administrator?</label></td><td><?=$user->is_admin ? 'Yes' : 'No'?></td></tr>
 <tr><td><label>Email</label></td><td><?=$user->email?></td></tr>
 <tr><td><label>Registered on</label></td><td><?=$user->created_at?></td></tr>
 <tr><td><label>Last login</label></td><td><?=$user->last_login_at?></td></tr>
 <tr><td><label>Flickr userid</label></td><td><?=$user->flickr_userid?></td></tr>
 <tr><td><label>Photobucket username</label></td><td><?=$user->photobucket_username?></td></tr>
</table>

<?php if ($this->session->userdata('userid') != $user->id): ?>
<br/>
<div style="text-align:center">
 <a href="/admin/impersonate/<?=$user->id?>">impersonate</a>
 &nbsp;&nbsp;
 <a href="/admin/deleteUser/<?=$user->id?>">delete</a>
</div>
<?php endif; ?>
