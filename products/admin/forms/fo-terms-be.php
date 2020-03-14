<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));
 
$FORM_TOP_LEFT = ''; 
$FORM_BOTTOM_LEFT = '';
$FORM_TOP_RIGHT = '';
$FORM_BOTTOM_RIGHT = '';

######################################################################
$FORM_TOP_LEFT = '
<div class="fieldset" data-formtab="general">
	<div class="formRow">
		<div class="formLabel">
			<label for="var">' . $TEXT['var'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="var" id="var" class="textfield" value="" data-checkfunction="checkRequired;checkUnique" data-checkmessage="" data-checksync="all">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="term">' . $TEXT['term'] . '</label>
		</div>
		<div class="formField">
			<textarea name="term" id="term" class="textfield h100" data-checkfunction="checkRequired" data-checkmessage=""></textarea>
		</div>
	</div>
</div>
';
######################################################################


######################################################################
$FORM_BOTTOM_LEFT = '';
######################################################################




######################################################################
$FORM_TOP_RIGHT = ' ';
######################################################################




######################################################################
$FORM_BOTTOM_RIGHT = ' ';
######################################################################


include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathAdmin'] . 'templates/form.php');


?>