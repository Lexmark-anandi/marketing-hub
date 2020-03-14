<?php
//if($FORM_BOTTOM_LEFT == ''){
//	$FORM_BOTTOM_LEFT = '
//		<input type="hidden" class="fieldFModul" value="' . $varSQL['idModul'] . '" name="idModul">
//		<input type="hidden" class="fieldID" value="' . intval($varSQL['id']) . '" name="id">
//		<input type="hidden" class="" value="' . intval($varSQL['id']) . '" name="id_copy">
//		<input type="hidden" class="fieldData" value="" name="data">
//		<input type="hidden" class="fieldMode" value="" name="mode">
//		<input type="hidden" class="fieldType" value="' . $varSQL['type'] . '" name="type">
//		<input type="hidden" class="fieldAction" value="" name="action">
//		
//		<button onclick="cancelForm()" class="formButton" value="" name="cancel" id="cancel" type="button"> ' . $TEXT['Cancel'] . '</button>
//		<button onclick="f_' . $varSQL['idModul'] . '.sendForm(\'save\')" class="formButton" value="" name="save" id="save" type="submit">' . $TEXT['Save'] . '</button>
//		<button onclick="f_' . $varSQL['idModul'] . '.sendForm(\'close\')" class="formButton" value="" name="close" id="close" type="submit">' . $TEXT['SaveClose'] . '</button>
//		<div class="errorMess" id="errorMessage"></div>
//	';
//	
//	if($varSQL['type'] == 'read'){
//		$FORM_BOTTOM_LEFT = '
//			<input type="hidden" class="fieldFModul" value="' . $varSQL['idModul'] . '" name="idModul">
//			<input type="hidden" class="fieldID" value="' . intval($varSQL['id']) . '" name="id">
//			<input type="hidden" class="" value="' . intval($varSQL['id']) . '" name="id_copy">
//			<input type="hidden" class="fieldData" value="" name="data">
//			<input type="hidden" class="fieldMode" value="read" name="mode">
//			<input type="hidden" class="fieldAction" value="" name="action">
//			<button onclick="cancelForm()" class="formButton" value="" name="cancel" id="cancel" type="button"> ' . $TEXT['Close'] . '</button>';
//	}
//}
//
//
////if($FORM_BOTTOM_RIGHT == '' && $varSQL['type'] != 'read'){
//if($FORM_BOTTOM_RIGHT == ''){
////	$FORM_BOTTOM_RIGHT = '
////		<span class="formFooterButtons"></span>
////		<button onclick="f_' . $varSQL['idModul'] . '.gridAdd(\'\')" class="formButton formButtonGridView" value="" name="save" id="save" type="button" title="' . $TEXT['gridview'] . '">&nbsp;</button>
////	';
//}
//?>
<!--
<div class="formLeft">
    <div class="formLeftInner">
        <div class="formTabs">
            <ul>
            </ul>
        </div>
        <form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="iFormLeft" id="iFormLeft" class="inputForm">
            <?php //echo $FORM_TOP_LEFT ?>
            <div class="formFooter">
                <?php //echo $FORM_BOTTOM_LEFT ?>
            </div>
        </form>
    </div>
</div>
<div class="formMiddle" onclick="openAssignedWideManually()"></div>
<div class="formRight">
    <div class="formRightInner">
        <div class="formTabs">
            <ul>
            </ul>
        </div>
        <form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="iFormRight" id="iFormRight" class="inputForm">
            <?php //echo $FORM_TOP_RIGHT ?>
            <div class="formFooter">
            	<div class="formFooterInner">
	                <?php //echo $FORM_BOTTOM_RIGHT ?>
                </div>
            </div>
        </form>
    </div>
</div>

-->






	<div class="formLeft">
	<div class="formLeftInner">

	<div class="formTabs"><ul></ul></div>
    
	<form enctype="multipart/form-data" onsubmit="return false" action="" method="post" name="iFormLeft" id="" class="inputForm">
            <?php echo $FORM_TOP_LEFT ?>
            

            <div class="formFooter">
                
		<input type="hidden" class="field_id_data" value="" name="id_data">
		<input type="hidden" class="field_id" value="" name="id">
		<input type="hidden" class="field_formdata" value="" name="formdata">
        
        
        
        
		<!--<input type="hidden" class="fieldMode" value="" name="mode">
		<input type="hidden" class="fieldType" value="" name="type">
		<input type="hidden" class="fieldAction" value="" name="action">
		<input type="hidden" class="fieldFModul" value="bae5350231f0d60c91e7ecdd038b47ec" name="idModul">-->
		
		<button onclick="cancelForm('<?php echo $varSQL['modul'] ?>')" class="formButton" type="button"> Abbrechen</button>
		<button onclick="f_<?php echo $varSQL['modul'] ?>.sendForm('<?php echo $varSQL['modul'] ?>', 'save')" class="formButton" value="" name="save" id="save" type="submit">Speichern</button>
		<button onclick="f_<?php echo $varSQL['modul'] ?>.sendForm('<?php echo $varSQL['modul'] ?>', 'close')" class="formButton" value="" name="close" id="close" type="submit">Speichern / Schließen</button>
		<div class="errorMess" id="errorMessage">&nbsp;</div>
	            </div>
        </form>

	</div>
	</div>
	<div class="formMiddle" onclick="openAssignedWideManually()"></div>
	<div class="formRight">
	<div class="formRightInner">
	<div class="formTabs" style="height: 41px;"></div>
	</div>
	</div>

