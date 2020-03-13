<?php 
$FORM_TOP_LEFT = ''; 
$FORM_BOTTOM_LEFT = ''; 
$FORM_TOP_RIGHT = '';
$FORM_BOTTOM_RIGHT = '';
$f_fieldshidden = '';

  
$listAC = '<option value="0"></option>';
$query = $CONFIG['dbconn'][0]->prepare('
									SELECT ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_caid as id,
										' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.category AS term
									FROM ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni 
									
									WHERE ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_count = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_lang = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_dev = (:nul)
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.id_cl IN (0, ' . $CONFIG['activeSettings']['id_clid'] . ')
										AND ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.del = (:nultime)
									ORDER BY ' . $CONFIG['db'][0]['prefix'] . '_categories_assets_uni.rank
									');
$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
$query->bindValue(':nul', 0, PDO::PARAM_INT);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$num = $query->rowCount();
foreach($rows as $row){
	$listAC .= '<option value="'.$row['id'].'">' . $row['term'] . '</option>';
}




######################################################################
$FORM_TOP_LEFT = '
<div class="fieldset" data-formtab="AssetCategory">
	<div class="formRow">
		<div class="formLabel">
			<label for="title_' . $modulpath . '">' . $TEXT['templatetitle'] . '</label>
		</div>
		<div class="formField">
			<input type="text" name="title" id="title_' . $modulpath . '" class="textfield checkDirect" value="" data-checkfunction="checkRequired" data-checkmessage="">
		</div>
	</div>
	<div class="formRow formRowNoBorder">
		<div class="formLabel">
			<label for="id_caid_' . $modulpath . '">' . $TEXT['AssetCategory'] . '</label>
		</div>
		<div class="formField">
			<select name="id_caid" id="id_caid_' . $modulpath . '" class="textfield checkDirect selectContentselect" value="" data-checkfunction="checkSelectNotEqual0" data-checkmessage="">
			'.$listAC.'
			</select>
		</div>
	</div>

	<div class="formRow formRowContentSource" style="display: none">
		<div class="formLabel">
			<label for="contentselect_' . $modulpath . '">' . $TEXT['selectsource'] . '</label>
		</div>
		<div class="formField">
			<div class="inlineRadiofield">
			  <label><input type="radio" name="contentselect" id="contentselect_' . $modulpath . '_kiado" class="radioContentselect" value="kiado" data-checkfunction="checkContentselectRequired" data-checkmessage=""> ' . $TEXT['kiado_code'] . '</label>
			</div>
			<div class="inlineRadiofield">
			  <label><input type="radio" name="contentselect" id="contentselect_' . $modulpath . '_pdf" class="radioContentselect" value="pdf" data-checkfunction="checkContentselectRequired" data-checkmessage=""> ' . $TEXT['pdf_upload'] . '</label>
			</div>
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
					<button class="formButton saveForm" value="" name="save" type="submit">' . $TEXT['NextStep'] . '</button>
					
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