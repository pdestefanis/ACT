
<?php foreach($options AS $k=>$v) : ?>
<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
<?php endforeach; ?>


<?php if (isset ($canhide)) { ?>
<style type="text/css">
<?php foreach($canhide AS $k=>$v) : ?>
	<?php echo "." . $k; ?> {<?php echo $v; ?>}
<?php endforeach; ?>
</style>

<?php } ?>