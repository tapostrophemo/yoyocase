<?=validation_errors()?>

<h2>Add to your collection</h2>

<div id="gallery">
<!-- TODO: factor the form out w/common stuff for both new/edit (?) -->
<?=form_open('yoyo')?>
 <table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
   <td><label for="manufacturer">Manufacturer</label></td>
   <td><input type="text" name="manufacturer"/></td>
  </tr>
  <tr>
   <td><label for="country">Country</label></td>
   <td><input type="text" name="country"/></td>
  </tr>
  <tr>
   <td><label for="model_year">Model year</label></td>
   <td><input type="text" name="model_year" size="4" maxlength="4"/></td>
  </tr>
  <tr>
   <td><label for="model_name">Model name</label></td>
   <td><input type="text" name="model_name"/></td>
  </tr>
<!-- TODO: photos
  <% if @yoyo.photos.length > 0 %><tr><td colspan="2"><hr/></td></tr><% end %>
  <tr>
   <td colspan="2">
   <% i = 1; @yoyo.photos.each do |photo| %>
    <label>Pic <%= i %></label> <a href="<%= url_for :controller => 'yoyos', :action => 'remove_photo', :id => photo.id %>">remove</a><br/>
    <img src="<%= photo.url %>"/><br/>
   <% i = i + 1; if i <= @yoyo.photos.length %><br/><% end %>
   <% end %>
   </td>
  </tr>
  <tr><td colspan="2"><hr/></td></tr>
  <tr>
   <td colspan="2">
   <% if current_user.flickr_userid %>
    <label>Link photos from your flickr photostream?</label>
    <br/>
    <br/>
    <%= render :partial => "flickr_thumbnails" %>
   <% else %>
    <p>Photo uploads are not yet implemented at <tt>yoyocase.net</tt>. However, if you have a flickr
     account, you can link photos from there to here.</p>
    <p>First, click "Save" on this screen. Then go to your account "preferences" screen and click
     "Verify flickr account" and follow the prompts.</p>
   <% end %>
   </td>
  </tr>
-->
 </table>

 <div class="submit">
  <input type="submit" value="Save"/>
  <input type="button" value="Cancel" onclick="document.location.href='<?=$cancel_url?>'"/>
 </div>
</form>
</div>

<div id="sidebar">
 <?php $this->load->view('yoyos/sidebar', array('yoyos' => $yoyos)) ?>
</div>

<div class="clearing"></div>

