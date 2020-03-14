<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));




$listTimezones = '<option value="0"></option>';
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.id_tz,
										' . $CONFIG['db'][0]['prefix'] . 'system_timezones.timezone,
										' . $CONFIG['db'][0]['prefix'] . 'system_timezones.abbr
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_timezones 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_timezones.timezone
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listTimezones .= '<option value="'.$row['id_tz'].'">'.$row['timezone'].' ('.$row['abbr'].')</option>';
}


$listFD = '<option value="0"></option>';
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.id_fd,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_date.format
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_format_date
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_format_date.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listFD .= '<option value="'.$row['id_fd'].'">'.$row['format'].'</option>';
}


$listFT = '<option value="0"></option>';
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.id_ft,
										' . $CONFIG['db'][0]['prefix'] . 'system_format_time.format
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_format_time
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_format_time.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listFT .= '<option value="'.$row['id_ft'].'">'.$row['format'].'</option>';
}





$listLanguages = '';
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language as term
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:keyCountry)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:keyLanguage)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:keyDevice)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':keyCountry', 0, PDO::PARAM_INT);
$query->bindValue(':keyLanguage', 0, PDO::PARAM_INT);
$query->bindValue(':keyDevice', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$aResult = array();
foreach($rows as $row){
	$row['term'] = (isset($TEXT[$row['term']])) ? $TEXT[$row['term']] : $row['term'];
	$aResult[$row['id']] = $row['term'];
}

asort($aResult, SORT_NATURAL | SORT_FLAG_CASE);
foreach($aResult as $id => $term){
	$listLanguages .= '<div>
						<div class="formInlineblock" style="width:150px"><input type="checkbox" name="languages[]" id="languages_'.$id.'" value="'.$id.'" data-checkfunction=""> <label for="languages_'.$id.'">'.$term.'</label></div>
						<div class="formInlineblock"><input type="radio" name="default_" id="defaultlang_'.$id.'" value="'.$id.'"></div>
					   </div>';
}




 
$FORM_TOP_LEFT = ''; 
$FORM_BOTTOM_LEFT = '';
$FORM_TOP_RIGHT = '';
$FORM_BOTTOM_RIGHT = '';

######################################################################
$FORM_TOP_LEFT = '
<div class="fieldset" data-formtab="general">
	<div class="formRow">
		<div class="formLabel">
			<label for="country">' . $TEXT['Country'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="country" id="country" class="textfield" value="" data-checkfunction="checkRequired">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="code">' . $TEXT['Code'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="code" id="code" class="textfield" value="" data-checkfunction="checkRequired">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="code_add">' . $TEXT['CodeAdd'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="code_add" id="code_add" class="textfield" value="">
		</div>
	</div>
	
	<div class="formRow">
		<div class="formLabel">
			<label for="currency">' . $TEXT['Currency'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="currency" id="currency" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="tax_name">' . $TEXT['tax_name'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="tax_name" id="tax_name" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="tax">' . $TEXT['tax'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="tax" id="tax" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="fee_name">' . $TEXT['fee_name'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="fee_name" id="fee_name" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="sep_decimal">' . $TEXT['sep_decimal'] . '</label>
		</div>
		<div class="formField">
			<select name="sep_decimal" id="sep_decimal" class="textfield">
				<option value=""></option>
				<option value=".">' . $TEXT['dot'] . ' (.)</option>
				<option value=",">' . $TEXT['comma'] . ' (,)</option>
			</select>
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="sep_thousand">' . $TEXT['sep_thousand'] . '</label>
		</div>
		<div class="formField">
			<select name="sep_thousand" id="sep_thousand" class="textfield">
				<option value=""></option>
				<option value=".">' . $TEXT['dot'] . ' (.)</option>
				<option value=",">' . $TEXT['comma'] . ' (,)</option>
			</select>
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="id_tz">' . $TEXT['id_tz'] . '</label>
		</div>
		<div class="formField">
			<select name="id_tz" id="id_tz" class="textfield">
			'.$listTimezones.'
			</select>
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="id_fd">' . $TEXT['id_fd'] . '</label>
		</div>
		<div class="formField">
			<select name="id_fd" id="id_fd" class="textfield">
			'.$listFD.'
			</select>
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="id_ft">' . $TEXT['id_ft'] . '</label>
		</div>
		<div class="formField">
			<select name="id_ft" id="id_ft" class="textfield">
			'.$listFT.'
			</select>
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="email_sender">' . $TEXT['email_sender'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="email_sender" id="email_sender" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="email_sendername">' . $TEXT['email_sendername'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="email_sendername" id="email_sendername" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="active">' . $TEXT['Active'] . '</label>
		</div>
		<div class="formField">
			<input type="radio" name="active" class="fill_TextField radioActive radiocheck" id="active_yes" value="1"> <label for="active_yes">' .$TEXT['yes'] . '</label>&nbsp;&nbsp;&nbsp;
			<input type="radio" name="active" class="fill_TextField radioActive radiocheck" id="active_no" value="2"> <label for="active_no">' .$TEXT['no'] . '</label>&nbsp;&nbsp;&nbsp;
			<span class="checkmaster"><input type="radio" name="active" class="fill_TextField radioActive radiocheck radiocheckmaster" id="active_master" value="0"> <label for="active_master">' .$TEXT['likemaster'] . ' <span class="radioactual"></span></label></span>
		</div>
	</div>
</div>


<div class="fieldset" data-formtab="Languages">
	<div class="formRow">
		<div class="formLabel">
			<label for="languages">' . $TEXT['Languages'] . '</label>
		</div>
		<div class="formField">
			<div style="padding-left:140px">' . $TEXT['defaultlanguage'] . '</div>
			'.$listLanguages.'
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