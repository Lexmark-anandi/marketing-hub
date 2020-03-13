<?php  
$FORM_TOP_LEFT = ''; 
$FORM_BOTTOM_LEFT = '';
$FORM_TOP_RIGHT = '';
$FORM_BOTTOM_RIGHT = '';
$f_fieldshidden = ''; 


$listSub = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_cssid as id,
										' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.category AS term
									FROM ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_categories_specsheets_uni.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listSub .= '<option value="' . $row['id'] . '">' . $row['term'] . '</option>';
}
 



######################################################################
$FORM_TOP_LEFT = '
<div class="fieldset" data-formtab="AssetCategory">
	<div class="formRow formRowNoBorder">
		<div class="formLabel">
			<label for="title_' . $modulpath . '">' . $TEXT['templatetitle'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="title" id="title_' . $modulpath . '" class="textfield checkDirect" value="" data-checkfunction="checkRequired" data-checkmessage="">
		</div>
	</div>
	
	<div class="formRow ">
	  <div class="formLabel">
		<label for="title_transrequired_' . $modulpath . '">' . $TEXT['transrequired'] . '</label>
	  </div>
	  <div class="formField">
		<div class="inlineRadiofield">
		  <label><input type="radio" name="title_transrequired" id="title_transrequired_' . $modulpath . '_1" class="booleanfield" value="1" data-checkfunction="" data-checkmessage=""> ' . $TEXT['yes'] . '</label>
		</div>
		<div class="inlineRadiofield">
		  <label><input type="radio" name="title_transrequired" id="title_transrequired_' . $modulpath . '_2" class="booleanfield" value="2" data-checkfunction="" data-checkmessage=""> ' . $TEXT['no'] . '</label>
		</div>
		<div class="inlineRadiofield">
		  <label><input type="radio" name="title_transrequired" id="title_transrequired_' . $modulpath . '_0" class="booleanfield" value="0" data-checkfunction="" data-checkmessage=""> ' . $TEXT['master'] . ' <span class="valuedefault"></span></label>
		</div>
	  </div>
	</div>

	<div class="formRow">
		<div class="formLabel">
			<label for="id_css_' . $modulpath . '">' . $TEXT['subcategory'] . '</label>
		</div>
		<div class="formField">
			<select name="id_css" id="id_css_' . $modulpath . '" class="textfield" value="" data-checkfunction="" data-checkmessage="">
				' . $listSub . '
			</select>
		</div>
	</div>

</div>



<div class="fieldset" data-formtab="countries">
  <div class="formRow ">
    <div class="formField formFieldFullwidth">
      ' . $listCountries . '
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
					<input type="hidden" name="id_tempid" id="id_tempid_112-0-111" class="textfield " value="' . $CONFIG['page']['id_data'] . '" data-checkfunction="" data-checkmessage="">
					' . $f_fieldshidden . '
				
					<button class="formButton cancelForm" type="button">' . $TEXT['Close'] . '</button>
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

?>