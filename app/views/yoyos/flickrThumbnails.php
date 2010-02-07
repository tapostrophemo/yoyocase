<!--%
my_photos = []
current_user.yoyos.each do |yoyo|
  yoyo.photos.each do |photo|
    my_photos << photo.url
  end
end

photos = user_flickr_photos.reject { |flickr_photo| my_photos.include? flickr_photo.url(:medium) }
if photos.length > 0
%-->

<table width="100%" border="0" cellpadding="3" cellspacing="0">
 <tr>
 <?php $i = 0; foreach ($thumbnails as $p): ?>
  <td align="center">
   <input id="photo_<?=$i?>" type="checkbox" name="photos[]" value="<?=$p['url']?>"/>
   <br/>
   <img src="<?=$p['thumbnail']?>"/>
  </td>
  <?php $i++; if (($i % 3) == 0) echo '</tr><tr>'; ?>
 <?php endforeach; ?>
 </tr><tr>
 </tr>
</table>

<!--
<% else %>

<p>You need to add some photos to your flickr photostream.</p>

<% end %>
-->
