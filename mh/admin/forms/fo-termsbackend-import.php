<?php
include_once(__DIR__ . '/../config-admin.php');
$varSQL = getPostData();




$FORM = '';  


######################################################################
$FORM = '
	<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="formImport" id="" class="inputForm">
	<div class="formRow">
		<div class="formLabel"> 
			<label for="uploadfile">' . $TEXT['uploadfile'] . '</label> 
		</div>
		<div class="formField">
			<input type="file" name="uploadfile" id="uploadfile" class="textfield fileupload" value="" data-fieldname="uploadfile" data-target="upload/" data-maxsize="" data-allowedtypes="xlsx"  data-checkfunction="checkRequired" data-checkmessage="" data-forceupload="yes">
		</div>
	</div>
	<input type="hidden" class="field_id_data" value="" name="id_data">
	<input type="hidden" class="field_id" value="" name="id">
	<input type="hidden" class="field_formdata" value="" name="formdata">
	</form>
';


echo $FORM;


?>