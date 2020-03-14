<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php'); 
$CONFIG['page'] = json_decode($_SERVER['HTTP_PAGE'], true);
$varSQL = getPostData(json_decode($_POST['data'], true));




$listRoles = '<option value="0"></option>';
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'system_roles.id_r,
										' . $CONFIG['db'][0]['prefix'] . 'system_roles.role
									FROM ' . $CONFIG['db'][0]['prefix'] . 'system_roles 
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'system_roles.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'system_roles.show = (:active)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'system_roles.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':active', 1, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

foreach($rows as $row){
	$role = (isset($TEXT[$row['role']]) && $TEXT[$row['role']] != '') ? $TEXT[$row['role']] : $row['role'];
	$listRoles .= '<option value="' . $row['id_r'] . '">' . $role . '</option>';
}


//										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid IN ('. implode(',', $_SESSION['admin']['USER']['country']) . ')

$listCountries = '';
$query = $CONFIG['dbconn']->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_langid
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_countid
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND (' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:clid)
											OR ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_clid = (:nul))

										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
										AND (' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:clid)
											OR ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_clid = (:nul))

										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages.id_count2lang IN ('. implode(',', $CONFIG['USER']['count2lang']) . ')

									ORDER BY ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->bindValue(':clid', $CONFIG['USER']['activeClient'], PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();

$aResult = array();
foreach($rows as $row){
	$row['country'] = (isset($TEXT[$row['country']])) ? $TEXT[$row['country']] : $row['country'];
	$row['language'] = (isset($TEXT[$row['language']])) ? $TEXT[$row['language']] : $row['language'];
	$aResult[$row['id']] = $row['country'] . ' (' . $row['language'] . ')';
}

asort($aResult, SORT_NATURAL | SORT_FLAG_CASE);
foreach($aResult as $id => $term){
	$listCountries .= '<div><input type="checkbox" name="countries[]" id="countries_'.$id.'" value="'.$id.'"> <label for="countries_'.$id.'">'.$term.'</label></div>';
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
			<label for="firstname">' . $TEXT['firstname'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="firstname" id="firstname" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="lastname">' . $TEXT['lastname'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="lastname" id="lastname" class="textfield" value="">
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
			<label for="id_r">' . $TEXT['role'] . '</label>
		</div>
		<div class="formField">
			<select name="id_r" id="id_r" class="textfield">
				' . $listRoles . '
			</select>
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="username">' . $TEXT['username'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="username" id="username" class="textfield" value="">
		</div>
	</div>
	<div class="formRow">
		<div class="formLabel">
			<label for="password">' . $TEXT['password'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="password" id="password" class="textfield" value="">
		</div>
	</div>
</div>


<div class="fieldset" data-formtab="CountriesLanguages">
	<div class="formRow">
		<div class="formLabel">
			<label for="countries">' . $TEXT['CountriesLanguages'] . '</label>
		</div>
		<div class="formField">
			'.$listCountries.'
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