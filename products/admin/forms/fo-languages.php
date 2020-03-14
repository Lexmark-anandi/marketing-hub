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
			<label for="language">' . $TEXT['Language'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="language" id="language" class="textfield" value="" data-checkfunction="checkRequired" data-checkmessage="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="code">' . $TEXT['Code'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="code" id="code" class="textfield" value="" data-checkfunction="checkRequired" data-checkmessage="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="code_add">' . $TEXT['CodeAdd'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="code_add" id="code_add" class="textfield" value="" data-checkfunction="" data-checkmessage="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="active">' . $TEXT['Active'] . '</label>
		</div>
		<div class="formField">
			<input type="radio" name="active" class="fill_TextField radioActive radiocheck" id="active_yes" value="1" data-checksyncyy="languagedevice" /> <label for="active_yes">' .$TEXT['yes'] . '</label>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="active" class="fill_TextField radioActive radiocheck" id="active_no" value="2" /> <label for="active_no">' .$TEXT['no'] . '</label>&nbsp;&nbsp;&nbsp;
			<span class="checkmaster"><input type="radio" name="active" class="fill_TextField radioActive radiocheck radiocheckmaster" id="active_master" value="0" /> <label for="active_master">wie master <span class="radioactual"></span></label></span>
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