<?= validation_errors() ?>

<?=form_open("admin/normalize/$what")?>
 <label>Raw name</label><br/>
 <?=urldecode($raw)?><input type="hidden" name="raw" value="<?=$raw?>"/><br/>
 <label>Normalized name</label><br/>
 <input type="text" name="normalized"><br/>
 <input type="submit" value="Save">
</form>
