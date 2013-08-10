<h2>Accounts</h2>

<div id="userDetailDialog" class="rounded dialog">
 <a href="#" class="dialogClose" onclick="$('#userDetailDialog').hide(); return false;">close</a>
 <div id="userDetailContent">
 </div>
</div>

<table class="report">
 <thead>
  <tr>
   <th><a href="/admin/accounts/username">Username</a></th>
   <th><a href="/admin/accounts/reg">Registered on</a></th>
   <th><a href="/admin/accounts/login">Last login</a></th>
  </tr>
 </thead>
 <tbody>
 <?php foreach ($accounts as $account): ?>
  <tr>
   <td><a href="/admin/userDetail/<?=$account->id?>"><?=$account->username?></a> <small>(<?=$account->num_yoyos?>y / <?=$account->num_photos?>p)</small></td>
   <td><?=$account->created_at?></td>
   <td><?=$account->last_login_at?></td>
  </tr>
 <?php endforeach; ?>
 </tbody>
</table>

<br/>

<p><a href="/admin/accounts" class="lsf-icon refresh">refresh</a></p>

<script type="text/javascript" src="/res/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {

  jQuery.each($(".report tbody a"), function (i, link) {
    $(link).click(function () {
      $("#userDetailContent").html('<div class="loading"><img src="/res/wait.gif" alt="loading..."/></div>');
      $("#userDetailContent").load(link.href);
      var middleOffset = Math.round($(window).height()/2 + $(document).scrollTop() - $("#userDetailDialog").height()/2 - parseInt($("body").css("margin-bottom"))/2);
      $("#userDetailDialog").css({"top": middleOffset+"px"}).show();
      return false;
    });
  });

});
</script>

