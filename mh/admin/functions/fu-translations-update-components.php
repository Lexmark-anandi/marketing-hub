<?php
#########################################
// save components
if(!isset($aArgs['data']['components'])) $aArgs['data']['components'] = '';
if($aArgs['data']['components'] != ''){
	$aDataComponents = json_decode($aArgs['data']['components'], true);
	foreach($aDataComponents['pages'] as $kPage => $aPageComponents){
		foreach($aPageComponents as $kComponent => $aComponent){
			$id_tpeid = $aComponent['id_tpeid'];
			
			if(!array_key_exists('n_' . $id_tpeid, $aArgsSaveTPE)){
				$aArgsSaveTPE['n_' . $id_tpeid] = array();
				$aArgsSaveTPE['n_' . $id_tpeid]['id_data'] = $id_tpeid;
				$aArgsSaveTPE['n_' . $id_tpeid]['table'] = $CONFIG['db'][0]['prefix'] . '_templatespageselements_';
				$aArgsSaveTPE['n_' . $id_tpeid]['primarykey'] = 'id_tpeid';
				$aArgsSaveTPE['n_' . $id_tpeid]['allVersions'] = array();
				$aArgsSaveTPE['n_' . $id_tpeid]['changedVersions'] = array();
			
				$aArgsSaveTPE['n_' . $id_tpeid]['columns'] = array(); 
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['id_tpeid'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['id_caid'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['id_tempid'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['id_tpid'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['id_tcid'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['page_id'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['page'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['elementtitle'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['position_left'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['position_top'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['width'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['height'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['fontsize'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['fontcolor'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['fontstyle'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['background_color'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['content'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['content_transrequired'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['max_char'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['alignment'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['verticalalignment'] = 's';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['editable'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['active'] = 'i';
				$aArgsSaveTPE['n_' . $id_tpeid]['columns']['fixed'] = 'i';
	
				$aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'] = array();
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'id_tpeid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'id_caid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'id_tempid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'id_tpid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'id_tcid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'page');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'position_left');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'position_top');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'width');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'height');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'fontsize');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'fontstyle');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'max_char');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'content_transrequired');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'editable');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'active');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsNumbers'], 'fixed');
	
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni'] = array();
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['id_tpeid'] = array('');
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['id_caid'] = array('');
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['id_tempid'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['id_tpid'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['id_tcid'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['page_id'] = array('');
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['page'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['elementtitle'] = array('');
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['position_left'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['position_top'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['width'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['height'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['fontsize'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['fontcolor'] = array('');
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['fontstyle'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['background_color'] = array('');
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['content'] = array('');
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['content_transrequired'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['max_char'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['alignment'] = array('');
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['verticalalignment'] = array('');
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['editable'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['active'] = array('',0);
				$aArgsSaveTPE['n_' . $id_tpeid]['excludeUpdateUni']['fixed'] = array('',0);
	
				$aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'] = array();
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'id_tpeid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'id_caid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'id_tempid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'id_tpid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'id_tcid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'page_id');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'page');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'elementtitle');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'position_left');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'position_top');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'width');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'height');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'fontsize');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'fontcolor');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'fontstyle');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'background_color');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'content');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'content_transrequired');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'max_char');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'alignment');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'verticalalignment');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'editable');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'active');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'], 'fixed');
	
				$aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'] = array();
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'id_tpeid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'id_caid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'id_tempid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'id_tpid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'id_tcid');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'page_id');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'page');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'elementtitle');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'position_left');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'position_top');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'width');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'height');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'fontsize');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'fontcolor');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'fontstyle');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'background_color');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'content');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'content_transrequired');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'max_char');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'alignment');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'verticalalignment');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'editable');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'active');
				array_push($aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveNotMaster'], 'fixed');
	
				$aArgsTPE['n_' . $id_tpeid]['fields'] = array();
				//$aArgsTPE['n_' . $id_tpeid]['fields']['bool2text']['feldname'] = array('text'=>'check');
			}
	
			$aArgsTPE['n_' . $id_tpeid]['data'] = array();
			$aArgsTPE['n_' . $id_tpeid]['data']['id_tpeid'] = $id_tpeid;
			$aArgsTPE['n_' . $id_tpeid]['data']['id_caid'] = $aComponent['id_caid'];
			$aArgsTPE['n_' . $id_tpeid]['data']['id_tempid'] = $aArgsSave['id_data'];
			$aArgsTPE['n_' . $id_tpeid]['data']['id_tpid'] = $aComponent['id_tpid'];
			$aArgsTPE['n_' . $id_tpeid]['data']['id_tcid'] = $aComponent['id_tcid'];
			$aArgsTPE['n_' . $id_tpeid]['data']['page_id'] = $aComponent['pageid'];
			$aArgsTPE['n_' . $id_tpeid]['data']['page'] = $aComponent['page'];
			$aArgsTPE['n_' . $id_tpeid]['data']['elementtitle'] = $aComponent['elementtitle'];
			$aArgsTPE['n_' . $id_tpeid]['data']['position_left'] = $aComponent['left'];
			$aArgsTPE['n_' . $id_tpeid]['data']['position_top'] = $aComponent['top'];
			$aArgsTPE['n_' . $id_tpeid]['data']['width'] = $aComponent['width'];
			$aArgsTPE['n_' . $id_tpeid]['data']['height'] = $aComponent['height'];
			$aArgsTPE['n_' . $id_tpeid]['data']['fontsize'] = $aComponent['fontsize'];
			$aArgsTPE['n_' . $id_tpeid]['data']['fontcolor'] = $aComponent['fontcolor'];
			$aArgsTPE['n_' . $id_tpeid]['data']['fontstyle'] = $aComponent['fontstyle'];
			$aArgsTPE['n_' . $id_tpeid]['data']['background_color'] = $aComponent['background_color'];
			$aArgsTPE['n_' . $id_tpeid]['data']['content'] = $aComponent['content'];
			$aArgsTPE['n_' . $id_tpeid]['data']['content_transrequired'] = $aComponent['transrequired'];
			$aArgsTPE['n_' . $id_tpeid]['data']['max_char'] = $aComponent['maxchars'];
			$aArgsTPE['n_' . $id_tpeid]['data']['alignment'] = $aComponent['alignment'];
			$aArgsTPE['n_' . $id_tpeid]['data']['verticalalignment'] = $aComponent['verticalalignment'];
			$aArgsTPE['n_' . $id_tpeid]['data']['editable'] = $aComponent['editable'];
			$aArgsTPE['n_' . $id_tpeid]['data']['active'] = $aComponent['active'];
			$aArgsTPE['n_' . $id_tpeid]['data']['fixed'] = (isset($aComponent['fixed'])) ? $aComponent['fixed'] : NULL;

			$aArgsTPE['n_' . $id_tpeid]['data']['id_count'] = $row['id_count'];
			$aArgsTPE['n_' . $id_tpeid]['data']['id_lang'] = $row['id_lang'];
			$aArgsTPE['n_' . $id_tpeid]['data']['id_dev'] = $row['id_dev'];
			$aArgsTPE['n_' . $id_tpeid]['data']['id_cl'] = $row['id_cl'];
	

			$aArgsSaveTPE['n_' . $id_tpeid]['aData'] = setValuesSave($aArgsTPE['n_' . $id_tpeid]);
			$aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_count'] = $row['id_count'];
			$aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_lang'] = $row['id_lang'];
			$aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_dev'] = $row['id_dev'];
			$aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_cl'] = $row['id_cl'];
			$aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_data'] = $id_tpeid;
	
			$aChangeTPE = checkChanges($aArgsSaveTPE['n_' . $id_tpeid]);
	
			$col = '';
			$val = '';
			$upd = '';
			foreach($aChangeTPE['aChangedFields'] as $field){
				if(($variation == 'master' && in_array($field, $aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster']))){
					if($field != $aArgsSaveTPE['n_' . $id_tpeid]['primarykey']){
						$col .= ', ' . $field;
						$val .= ', :' . $field . '';
						$upd .= $field.' = (:'.$field.'), ' ;
					}
				}
			}
	
			$qry = 'INSERT INTO ' . $aArgsSaveTPE['n_' . $id_tpeid]['table'] . 'loc
						(' . $aArgsSaveTPE['n_' . $id_tpeid]['primarykey'] . ', id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from' . $col . ')
					VALUES
						(:id, :id_count, :id_lang, :id_dev, :id_cl, :now, :create_from, :create_from' . $val . ')
					ON DUPLICATE KEY UPDATE 
						' . $upd . '
						change_from = (:create_from),
						del = (:nultime)
					';
			$queryTPEC = $CONFIG['dbconn'][0]->prepare($qry);
			$queryTPEC->bindValue(':id', $aArgsSaveTPE['n_' . $id_tpeid]['id_data'], PDO::PARAM_INT);
			$queryTPEC->bindValue(':id_count', $aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_count'], PDO::PARAM_INT);
			$queryTPEC->bindValue(':id_lang', $aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_lang'], PDO::PARAM_INT);
			$queryTPEC->bindValue(':id_dev', $aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_dev'], PDO::PARAM_INT);
			$queryTPEC->bindValue(':id_cl', $aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_cl'], PDO::PARAM_INT);
			$queryTPEC->bindValue(':now', $now, PDO::PARAM_STR);
			$queryTPEC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
			$queryTPEC->bindValue(':create_from', $CONFIG['user']['id_real'], PDO::PARAM_INT); 
			
			foreach($aChangeTPE['aChangedFields'] as $field){
				if(($variation == 'master' && in_array($field, $aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster'])) || ($variation == 'local' && in_array($field, $aArgsSaveTPE['n_' . $id_tpeid]['aFieldsSaveMaster']))){
					if($field != $aArgsSaveTPE['n_' . $id_tpeid]['primarykey']){
						if($aArgsSaveTPE['n_' . $id_tpeid]['columns'][$field] == 'i' || $aArgsSaveTPE['n_' . $id_tpeid]['columns'][$field] == 'si' || $aArgsSaveTPE['n_' . $id_tpeid]['columns'][$field] == 'b'){
							$queryTPEC->bindValue(':'.$field, (is_array($aArgsSaveTPE['n_' . $id_tpeid]['aData'][$field])) ? json_encode($aArgsSaveTPE['n_' . $id_tpeid]['aData'][$field]) : trim($aArgsSaveTPE['n_' . $id_tpeid]['aData'][$field]), PDO::PARAM_INT);
						}else{ 
							$queryTPEC->bindValue(':'.$field, (is_array($aArgsSaveTPE['n_' . $id_tpeid]['aData'][$field])) ? json_encode($aArgsSaveTPE['n_' . $id_tpeid]['aData'][$field]) : trim($aArgsSaveTPE['n_' . $id_tpeid]['aData'][$field]), PDO::PARAM_STR);
						}
					}
				}
			}
			$queryTPEC->execute();
			$numTPEC = $queryTPEC->rowCount();


			array_push($aArgsSaveTPE['n_' . $id_tpeid]['allVersions'], array($aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_count'], $aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_lang'], $aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_dev']));
			if($numTPEC > 0 || count($aChangeTPE['aDataOld'] == 0)) array_push($aArgsSaveTPE['n_' . $id_tpeid]['changedVersions'], array($aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_count'], $aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_lang'], $aArgsSaveTPE['n_' . $id_tpeid]['aData']['id_dev']));
		}
	}
}

?>