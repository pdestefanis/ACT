<?php 
if (!empty($options )) {
	if ($select)
		echo '<option value="">' . __('---Select---', true) . '</option>';
	foreach($options AS $k=>$v) : ?>
<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
<?php endforeach; 
}
?>
