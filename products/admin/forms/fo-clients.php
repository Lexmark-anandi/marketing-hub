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
			<label for="client">' . $TEXT['client'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="client" id="client" class="textfield" value="" data-checkfunction="checkRequired">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="street">' . $TEXT['street'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="street" id="street" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="zip">' . $TEXT['zip'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="zip" id="zip" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="city">' . $TEXT['cityShort'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="city" id="city" class="textfield" value="">
		</div>
	</div>
	<!--<div class="formRow">
		<div class="formLabel">
			<label for="id_countid">' . $TEXT['country'] . '</label>
		</div>
		<div class="formField">
			<select name="id_countid" id="id_countid" class="textfield">
			'.$listCountries.'
			</select>
		</div>
	</div>-->
	<div class="formRow">
		<div class="formLabel">
			<label for="phone">' . $TEXT['phone'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="phone" id="phone" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="mobile">' . $TEXT['mobile'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="mobile" id="mobile" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="fax">' . $TEXT['fax'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="fax" id="fax" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="email">' . $TEXT['email'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="email" id="email" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="web">' . $TEXT['web'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="web" id="web" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="active">' . $TEXT['Active'] . '</label>
		</div>
		<div class="formField">
			<input type="radio" name="active" class="fill_TextField radioActive radiocheck" id="active_yes" value="1"> <label for="active_yes">'.$TEXT['yes'].'</label>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="active" class="fill_TextField radioActive radiocheck" id="active_no" value="2"> <label for="active_no">'.$TEXT['no'].'</label>
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