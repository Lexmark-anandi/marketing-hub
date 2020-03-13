<?php 
$FORM_TOP_LEFT = ''; 
$FORM_BOTTOM_LEFT = '';
$FORM_TOP_RIGHT = '';
$FORM_BOTTOM_RIGHT = '';
$f_fieldshidden = '';


$existCountries = array(0);
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count2lang as id,
										' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.language
									FROM ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni 
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_langid
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
									
									INNER JOIN ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_countid
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_count = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_lang = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_dev = (:nul)
											AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries2languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_countries_uni.del = (:nultime)

										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . 'sys_languages_uni.del = (:nultime)
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	array_push($existCountries, $row['id']);
}


getConnection(1); 
$listCountries = '<option value="0"></option>';
$query = $CONFIG['dbconn'][1]->prepare('
									SELECT ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_count2lang as id,
										' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.country,
										' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.language
									FROM ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages 
									
									INNER JOIN ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni
										ON ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_langid = ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_langid
									
									INNER JOIN ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni
										ON ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_countid = ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_countid
									
									WHERE ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.del = (:nultime)
										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.id_clid IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')

										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.del = (:nultime)
										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.id_clid IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
											
										AND ' . $CONFIG['db'][1]['prefix'] . 'sys_countries2languages.id_count2lang NOT IN ('. implode(',', $existCountries) . ')

									ORDER BY ' . $CONFIG['db'][1]['prefix'] . 'sys_countries_uni.country, ' . $CONFIG['db'][1]['prefix'] . 'sys_languages_uni.language
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
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
	$listCountries .= '<option value="'.$id.'">' . $term . '</option>';
}


######################################################################
$FORM_TOP_LEFT = '
<div class="fieldset" data-formtab="general">
	<div class="formRow">
		<div class="formLabel">
			<label for="id_count2lang">' . $TEXT['CountriesLanguages'] . '</label>
		</div>
		<div class="formField">
			<select name="id_count2lang" id="id_count2lang" class="textfield">
			'.$listCountries.'
			</select>
		</div>
	</div>
</div>
';
######################################################################


if(!isset($FORM_TABS_RIGHT)) $FORM_TABS_RIGHT = '<ul></ul>';




######################################################################
$FORM = '
	<div class="formLeft">
		<div class="formLeftInner">

			<div class="formTabs"><ul></ul></div>
	
			<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="iFormLeft" id="form_' . $CONFIG['page']['modulpath'] . '" class="inputForm">
			
				' . $FORM_TOP_LEFT . '
			
				<div class="formFooter">
					<input type="hidden" class="field_id_data" value="" name="id_data">
					<input type="hidden" class="field_formdata" value="" name="formdata">
					' . $f_fieldshidden . '
				
					<button class="formButton cancelForm" type="button">' . $TEXT['Cancel'] . '</button>
					<button class="formButton saveForm" value="" name="save" type="submit">' . $TEXT['Save'] . '</button>
					<button class="formButton closeForm" value="" name="close" type="submit">' . $TEXT['SaveClose'] . '</button>
					
					<div class="errorMess" id="errorMessage">&nbsp;</div>
				</div>
			</form>
			
		</div>
	</div>
	
	<div class="formMiddle"></div>
	
	<div class="formRight">
		<div class="formRightInner">

			<div class="formTabs" style="height: 41px;">' . $FORM_TABS_RIGHT . '</div>
		 
			' . $FORM_TOP_RIGHT . '
		</div>
	</div>
';
######################################################################

echo $FORM;

?>