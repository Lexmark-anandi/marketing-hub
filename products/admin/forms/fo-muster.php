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
<input type="hidden" data-setparentfield="id_evid" name="id_evid" id="id_evid" class="" value="">
<div class="fieldset" data-formtab="general">
		<div class="formRow">
			<div class="formLabel">
				<label for="device">Gerät</label>
			</div>
			<div class="formField">
				<input type="text" name="device" id="device" class="textfield checkDirectX" value="" data-checkfunction="checkRequired" data-checkmessage="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="active">Aktiv</label>
			</div>
			<div class="formField">
				<input type="radio" name="active" class="fill_TextField radioActive radiocheck" id="active_yes" value="1" data-checksyncyy="languagedevice" /> <label for="active_yes">' .$TEXT['yes'] . '</label>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="active" class="fill_TextField radioActive radiocheck" id="active_no" value="2" /> <label for="active_no">' .$TEXT['no'] . '</label>&nbsp;&nbsp;&nbsp;
				<span class="checkmaster"><input type="radio" name="active" class="fill_TextField radioActive radiocheck radiocheckmaster" id="active_master" value="0" /> <label for="active_master">wie master <span class="radioactual"></span></label></span>
			</div>
		</div>





		<div class="formRow">
			<div class="formLabel">
				<label for="salutation">Anrede</label>
			</div>
			<div class="formField">
				<select name="salutation" id="salutation" class="textfield">
					<option value=""></option>
					<option value="Frau">Frau</option>
					<option value="Herr">Herr</option>
				</select>
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="title">Titel</label>
			</div>
			<div class="formField">
				<input type="text" name="title" id="title" class="textfield" value="" data-checkfunction="checkRequired" data-checkmessage="ddd" data-checksync="language">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="firstname">Vorname</label>
			</div>
			<div class="formField">
				<input type="text" name="firstname" id="firstname" class="textfield" value="" data-checksync="language">
			</div>
		</div>
		<div class="formRow formRowSpace">
			<div class="formLabel">
				<label for="lastname">Nachname</label>
			</div>
			<div class="formField">
				<input type="text" name="lastname" id="lastname" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="email">E-Mail</label>
			</div>
			<div class="formField">
				<input type="text" name="email" id="email" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="newsletterreceive">Newsletter-Bezug</label>
			</div>
			<div class="formField">
				<span data-name="newsletterreceive" class="textfield textfieldRead">ja</span> 
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="nl_optin_at">OptIn am</label>
			</div>
			<div class="formField">
				<input type="text" name="nl_optin_at" id="nl_optin_at" class="textfield textfieldRead" value="" readonly="">
				<input type="text" name="nl_optin_confirm_at" id="nl_optin_confirm_at" class="textfield textfieldRead" value="" readonly="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="nl_optout_at">OptOut am</label>
			</div>
			<div class="formField">
				<input type="text" name="nl_optout_at" id="nl_optout_at" class="textfield calendartime hasDatepicker" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="email_additional">zusätzl. E-Mail</label>
			</div>
			<div class="formField">
				<input type="text" name="email_additional" id="email_additional" class="textfield" value="">
			</div>
		</div>
		<div class="formRow formRowSpace">
			<div class="formLabel">
				<label for="id_typeid">Status</label>
			</div>
			<div class="formField">
				<span data-name="membtype" class="textfield textfieldRead">Gast</span> 
			</div>
		</div>

		<div class="formRow">
			<div class="formLabel">
				<label for="position">Position</label>
			</div>
			<div class="formField">
				<input type="text" name="position" id="position" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="birthday">Geburtstag</label>
			</div>
			<div class="formField">
				<input type="text" name="birthday" id="birthday" class="textfield calendar hasDatepicker" value="">
			</div>
		</div>
		
		<div class="formRow">
			<div class="formLabel">
				<label for="comments">Bemerkungen</label>
			</div>
			<div class="formField">
				<textarea name="comments" id="comments" class="textfield h100"></textarea>
			</div>
		</div>
</div>



<div class="fieldset" data-formtab="contact">
		<div class="formRow">
			<div class="formLabel">
				<label for="company">Firma</label>
			</div>
			<div class="formField">
				<input type="text" name="company" id="company" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="street">Straße</label>
			</div>
			<div class="formField">
				<input type="text" name="street" id="street" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="zip">PLZ</label>
			</div>
			<div class="formField">
				<input type="text" name="zip" id="zip" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="city">Ort</label>
			</div>
			<div class="formField">
				<input type="text" name="city" id="city" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="id_countid">Land</label>
			</div>
			<div class="formField">
				<select name="id_countid" id="id_countid" class="textfield">
				<option value="0"></option><option value="1">Deutschland</option><option value="4">Dubai</option><option value="3">Luxemburg</option><option value="2">Schweiz</option>
				</select>
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="phone">Telefon</label>
			</div>
			<div class="formField">
				<input type="text" name="phone" id="phone" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="mobile">Mobil</label>
			</div>
			<div class="formField">
				<input type="text" name="mobile" id="mobile" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="fax">Fax</label>
			</div>
			<div class="formField">
				<input type="text" name="fax" id="fax" class="textfield" value="">
			</div>
		</div>
</div>



<div class="fieldset" data-formtab="membership">
		<div class="formRow">
			<div class="formLabel">
				<label for="membership_start">Mitglied Eintritt</label>
			</div>
			<div class="formField">
				<input type="text" name="membership_start" id="membership_start" class="textfield calendar hasDatepicker" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="membership_end">Mitglied Austritt</label>
			</div>
			<div class="formField">
				<input type="text" name="membership_end" id="membership_end" class="textfield calendar hasDatepicker" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="membership_amountfree">Beitragsbefreit</label>
			</div>
			<div class="formField">
				<input type="radio" name="membership_amountfree" class="fill_TextField radioMembership_amountfree radiocheck" id="membership_amountfree_yes" value="1"> <label for="membership_amountfree_yes">ja</label>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="membership_amountfree" class="fill_TextField radioMembership_amountfree radiocheck" id="membership_amountfree_no" value="2"> <label for="membership_amountfree_no">nein</label>&nbsp;&nbsp;&nbsp;
				<span class="checkmaster" style="display: none;"><input type="radio" name="membership_amountfree" class="fill_TextField radioMembership_amountfree radiocheck radiocheckmaster" id="membership_amountfree_master" value="0"> <label for="membership_amountfree_master">wie Master <span class="radioactual"></span></label></span>
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="membership_amount">Mitgliedsbeitrag</label>
			</div>
			<div class="formField">
				<input type="text" name="membership_amount" id="membership_amount" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
//			<div class="formLabel">
//				<label for="id_mrid">Beitragssatz</label>
//			</div>
//			<div class="formField">
//				<select name="id_mrid" id="id_mrid" class="textfield">
//				
//				</select>
//			</div>
//		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="id_pmid">Bezahlart</label>
			</div>
			<div class="formField">
				<select name="id_pmid" id="id_pmid" class="textfield">
				<option value="0"></option><option value="1">Bankeinzug</option><option value="2">Rechnung</option><option value="3">Überweisung</option>
				</select>
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="bank">Bank</label>
			</div>
			<div class="formField">
				<input type="text" name="bank" id="bank" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="iban">IBAN</label>
			</div>
			<div class="formField">
				<input type="text" name="iban" id="iban" class="textfield" value="">
			</div>
		</div>
		<div class="formRow">
			<div class="formLabel">
				<label for="bic">BIC</label>
			</div>
			<div class="formField">
				<input type="text" name="bic" id="bic" class="textfield" value="">
			</div>
		</div>
	<input type="hidden" name="create_at" id="create_at" class="" value="2015-09-05 11:52:13">
</div>



<div class="fieldset" data-formtab="groups">
		<div class="formRow">
			<div class="formLabel">
				<label for="groupname">Gruppe</label>
			</div>
			<div class="formField">
				<div><input type="checkbox" name="groupname[]" id="groupname_1" value="1"> <label for="groupname_1">Presse</label></div><div><input type="checkbox" name="groupname[]" id="groupname_2" value="2"> <label for="groupname_2">VIP</label></div>
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