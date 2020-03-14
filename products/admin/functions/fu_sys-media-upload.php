<?php
$CONFIG['system']['pathInclude'] = '../../';
include_once($CONFIG['system']['pathInclude'] . 'admin/config-admin.php');

$varSQL = getPostData();

$link = basename($varSQL['url']);
include_once($CONFIG['system']['pathInclude'] . $CONFIG['system']['pathFunctionsAdmin'] . 'fu_sys-usercheck-function.php'); 

if($checkfunction == 'ok') { 
	getConnection(0); 
	
	foreach($_FILES as $field=>$file){
		//$targetpath = $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathMedia'] . $varSQL['target'];
		$targetpath = $CONFIG['system']['pathInclude'] . $CONFIG['system']['pathMedia'];
		
		// Find root folder
		$queryR = $CONFIG['dbconn']->prepare('
											SELECT ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_mpid
											FROM ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full 
											WHERE ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.del = (:nultime)
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_count = (:count)
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_lang = (:lang)
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_dev = (:dev)
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_clid = (:id_clid)
												AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_parent = (:id_parent)
											');
		$queryR->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
		$queryR->bindValue(':count', 0, PDO::PARAM_INT);
		$queryR->bindValue(':lang', 0, PDO::PARAM_INT);
		$queryR->bindValue(':dev', 0, PDO::PARAM_INT);
		$queryR->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
		$queryR->bindValue(':id_parent', 0, PDO::PARAM_INT);
		$queryR->execute();
		$rowsR = $queryR->fetchAll(PDO::FETCH_ASSOC);
		$numR = $queryR->rowCount();


		// Process Folder
		$aTarget = explode('/', $varSQL['target']);
		$idParent = $rowsR[0]['id_mpid'];
		$idPath = $rowsR[0]['id_mpid'];

		foreach($aTarget as $target){
			if($target != ''){
				$query = $CONFIG['dbconn']->prepare('
													SELECT ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_mpid_data,
														' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_mpid,
														' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_parent
													FROM ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full 
													WHERE ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.del = (:nultime)
														AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_count = (:count)
														AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_lang = (:lang)
														AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_dev = (:dev)
														AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_clid = (:id_clid)
														AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_parent = (:id_parent)
														AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.folder = (:folder)
													');
				$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
				$query->bindValue(':count', 0, PDO::PARAM_INT);
				$query->bindValue(':lang', 0, PDO::PARAM_INT);
				$query->bindValue(':dev', 0, PDO::PARAM_INT);
				$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
				$query->bindValue(':id_parent', $idParent, PDO::PARAM_INT);
				$query->bindValue(':folder', trim($target), PDO::PARAM_STR);
				$query->execute();
				$rows = $query->fetchAll(PDO::FETCH_ASSOC);
				$num = $query->rowCount();

				if($num == 0){
					$date = new DateTime();
					$now = $date->format('Y-m-d H:i:s');
				
					$query = $CONFIG['dbconn']->prepare('
														INSERT INTO ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_id
														(id_clid, create_at, create_from, change_from)
														VALUES
														(:id_clid, :create_at, :create_from, :create_from)
														');
					$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
					$query->bindValue(':create_at', $now, PDO::PARAM_STR);
					$query->bindValue(':create_from', $_SESSION['admin']['USER']['id_real'], PDO::PARAM_INT);
					$query->execute();
					$idNew = $CONFIG['dbconn']->lastInsertId();

					$query = $CONFIG['dbconn']->prepare('
														INSERT INTO ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data
														(
															id_mpid,
															id_count,
															id_lang,
															id_dev,
															id_clid,
															id_parent,
															folder,
															protected,
															create_at,
															create_from,
															change_from
														)
														VALUES
														(
															:id_mpid,
															:id_count,
															:id_lang,
															:id_dev,
															:id_clid,
															:id_parent,
															:folder,
															:protected,
															:create_at,
															:create_from,
															:change_from
														)
														');
					$query->bindValue(':id_mpid', $idNew, PDO::PARAM_INT);
					$query->bindValue(':id_count', 0, PDO::PARAM_INT);
					$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
					$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
					$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
					$query->bindValue(':id_parent', trim($idParent), PDO::PARAM_INT);
					$query->bindValue(':folder', trim($target), PDO::PARAM_STR);
					$query->bindValue(':protected', 1, PDO::PARAM_INT);
					$query->bindValue(':create_at', $now, PDO::PARAM_STR);
					$query->bindValue(':create_from', trim($_SESSION['admin']['USER']['id_real']), PDO::PARAM_INT);
					$query->bindValue(':change_from', trim($_SESSION['admin']['USER']['id_real']), PDO::PARAM_INT);
					$query->execute();
					
					
					$aListLanguagesByCountries = readLanguagesByCountries();
					$aListDevices = readDevices();
					foreach($aListLanguagesByCountries as $keyCountry=>$valLanguage){
						foreach($valLanguage as $keyLanguage){
							foreach($aListDevices as $keyDevice=>$valDevice){
								$query = $CONFIG['dbconn']->prepare('
																	SELECT ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_mpid_data
																	FROM ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full 
																	WHERE ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.del = (:nultime)
																		AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_count = (:count)
																		AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_lang = (:lang)
																		AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_dev = (:dev)
																		AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_clid = (:id_clid)
																		AND ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full.id_mpid = (:id_mpid)
																	');
								$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
								$query->bindValue(':count', $keyCountry, PDO::PARAM_INT);
								$query->bindValue(':lang', $keyLanguage, PDO::PARAM_INT);
								$query->bindValue(':dev', $keyDevice, PDO::PARAM_INT);
								$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
								$query->bindValue(':id_mpid', $idNew, PDO::PARAM_INT);
								$query->execute();
								$num = $query->rowCount();
								
								if($num == 0){
									$query = $CONFIG['dbconn']->prepare('
																		INSERT INTO ' . $CONFIG['db'][0]['prefix_sys'] . 'mediapath_data_full
																		(
																			id_mpid,
																			id_count,
																			id_lang,
																			id_dev,
																			id_clid,
																			id_parent,
																			folder,
																			protected,
																			create_at,
																			create_from, 
																			change_from
																		)
																		VALUES
																		(
																			:id_mpid,
																			:id_count,
																			:id_lang,
																			:id_dev,
																			:id_clid,
																			:id_parent,
																			:folder,
																			:protected,
																			:create_at,
																			:create_from,
																			:change_from
																		)
																		');
									$query->bindValue(':id_mpid', $idNew, PDO::PARAM_INT);
									$query->bindValue(':id_count', 0, PDO::PARAM_INT);
									$query->bindValue(':id_lang', 0, PDO::PARAM_INT); 
									$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
									$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
									$query->bindValue(':id_parent', trim($idParent), PDO::PARAM_INT);
									$query->bindValue(':folder', trim($target), PDO::PARAM_STR);
									$query->bindValue(':protected', 1, PDO::PARAM_INT);
									$query->bindValue(':create_at', $now, PDO::PARAM_STR);
									$query->bindValue(':create_from', trim($_SESSION['admin']['USER']['id_real']), PDO::PARAM_INT);
									$query->bindValue(':change_from', trim($_SESSION['admin']['USER']['id_real']), PDO::PARAM_INT);
									$query->execute();
								}
							}
						}
					}
					
					$idParent = $idNew;
					$idPath = $idNew;
				}else{
					$idParent = $rows[0]['id_mpid'];
					$idPath = $rows[0]['id_mpid'];
				}
			}
		}
		
		
		// Process File
		$num = 0;
		$filenameOrg = $_FILES[$field]['name'];
		$lastCharOrg = strrpos($filenameOrg,".");
		$filenameOrgBase = substr($filenameOrg, 0, $lastCharOrg);
		$filenameOrgEnd = strtolower(substr(strrchr($filenameOrg, "."), 1));
		$filenameBase = md5($filenameOrgBase);
		$filename = $filenameBase . '.' . $filenameOrgEnd;
	
		$handle = opendir($targetpath);
		while(file_exists($targetpath . $filename)){
			$num++;
			$filename = $filenameBase . "-" . $num . '.' . $filenameOrgEnd;
		}
		closedir($handle);
		
		move_uploaded_file($_FILES[$field]['tmp_name'], $targetpath . $filename);
		chmod($targetpath . $filename , 0777);

		
		$fmediatype = finfo_open(FILEINFO_MIME_TYPE);
		$picInfo = getimagesize($targetpath . $filename);
		$picWidth = $picInfo[0];
		$picHeight = $picInfo[1];
		
		$aData = array();
		$aData[0] = array();
		$aData[0][0] = array();
		$aData[0][0][0] = array();
		$aData[0][0][0]['id_mpid'] = $idPath;
		$aData[0][0][0]['filename'] = $filenameOrg;
		$aData[0][0][0]['filesys_filename'] = $filename;
		$aData[0][0][0]['thumbnail'] = '';
		$aData[0][0][0]['width'] = $picWidth;
		$aData[0][0][0]['height'] = $picHeight;
		$aData[0][0][0]['mediatype'] = finfo_file($fmediatype, $targetpath . $filename);
		$aData[0][0][0]['size'] = filesize($targetpath . $filename);
		$aData[0][0][0]['filetype'] = $filenameOrgEnd;
		$aData[0][0][0]['alttext'] = '';
		$aData[0][0][0]['keywords'] = '';
		$aData[0][0][0]['id_page'] = $varSQL['page'];
		$aData[0][0][0]['fieldname'] = $field;
		$aData[0][0][0]['filehash'] = md5_file($targetpath . $filename);
		

		$aFields = array();
		$aFields['timestamps'] = array();
		$aFields['floats'] = array();
		$aData = setValuesSave($aData, $aFields, 0, 0, 0);



		$date = new DateTime();
		$now = $date->format('Y-m-d H:i:s');
	
		$query = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix_sys'] . 'media_id
											(id_clid, create_at, create_from, change_from)
											VALUES
											(:id_clid, :create_at, :create_from, :create_from)
											');
		$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->bindValue(':create_from', $_SESSION['admin']['USER']['id_real'], PDO::PARAM_INT);
		$query->execute();
		$idNew = $CONFIG['dbconn']->lastInsertId();

	
		$query = $CONFIG['dbconn']->prepare('
											INSERT INTO ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data
											(
												id_mid,
												id_count,
												id_lang,
												id_dev,
												id_clid,
												id_mpid,
												id_page,
												fieldname,
												filename,
												filesys_filename,
												filehash,
												width,
												height,
												mediatype,
												size,
												filetype,
												alttext,
												keywords,
												create_at,
												create_from,
												change_from
											)
											VALUES
											(
												:id_mid,
												:id_count,
												:id_lang,
												:id_dev,
												:id_clid,
												:id_mpid,
												:id_page,
												:fieldname,
												:filename,
												:filesys_filename,
												:filehash,
												:width,
												:height,
												:mediatype,
												:size,
												:filetype,
												:alttext,
												:keywords,
												:create_at,
												:create_from,
												:change_from
											)
											');
		$query->bindValue(':id_mid', $idNew, PDO::PARAM_INT);
		$query->bindValue(':id_count', 0, PDO::PARAM_INT);
		$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
		$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
		$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
		$query->bindValue(':id_mpid', trim($aData[0][0][0]['id_mpid']), PDO::PARAM_INT);
		$query->bindValue(':id_page', trim($aData[0][0][0]['id_page']), PDO::PARAM_INT);
		$query->bindValue(':fieldname', trim($aData[0][0][0]['fieldname']), PDO::PARAM_INT);
		$query->bindValue(':filename', trim($aData[0][0][0]['filename']), PDO::PARAM_STR);
		$query->bindValue(':filesys_filename', trim($aData[0][0][0]['filesys_filename']), PDO::PARAM_STR);
		$query->bindValue(':filehash', trim($aData[0][0][0]['filehash']), PDO::PARAM_STR);
		$query->bindValue(':width', trim($aData[0][0][0]['width']), PDO::PARAM_INT);
		$query->bindValue(':height', trim($aData[0][0][0]['height']), PDO::PARAM_INT);
		$query->bindValue(':mediatype', trim($aData[0][0][0]['mediatype']), PDO::PARAM_STR);
		$query->bindValue(':size', trim($aData[0][0][0]['size']), PDO::PARAM_INT);
		$query->bindValue(':filetype', trim($aData[0][0][0]['filetype']), PDO::PARAM_STR);
		$query->bindValue(':alttext', trim($aData[0][0][0]['alttext']), PDO::PARAM_STR);
		$query->bindValue(':keywords', trim($aData[0][0][0]['keywords']), PDO::PARAM_STR);
		$query->bindValue(':create_at', $now, PDO::PARAM_STR);
		$query->bindValue(':create_from', trim($_SESSION['admin']['USER']['id_real']), PDO::PARAM_INT);
		$query->bindValue(':change_from', trim($_SESSION['admin']['USER']['id_real']), PDO::PARAM_INT);
		$query->execute();



		$aListLanguagesByCountries = readLanguagesByCountries();
		$aListDevices = readDevices();
		foreach($aListLanguagesByCountries as $keyCountry=>$valLanguage){
			foreach($valLanguage as $keyLanguage){
				foreach($aListDevices as $keyDevice=>$valDevice){
					$query = $CONFIG['dbconn']->prepare('
														SELECT ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_mid_data
														FROM ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full 
														WHERE ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.del = (:nultime)
															AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_count = (:count)
															AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_lang = (:lang)
															AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_dev = (:dev)
															AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_clid = (:id_clid)
															AND ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full.id_mid = (:id_mid)
														');
					$query->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
					$query->bindValue(':count', $keyCountry, PDO::PARAM_INT);
					$query->bindValue(':lang', $keyLanguage, PDO::PARAM_INT);
					$query->bindValue(':dev', $keyDevice, PDO::PARAM_INT);
					$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
					$query->bindValue(':id_mid', $idNew, PDO::PARAM_INT);
					$query->execute();
					$num = $query->rowCount();
					
					if($num == 0){
						$query = $CONFIG['dbconn']->prepare('
															INSERT INTO ' . $CONFIG['db'][0]['prefix_sys'] . 'media_data_full
															(
																id_mid,
																id_count,
																id_lang,
																id_dev,
																id_clid,
																id_mpid,
																id_page,
																fieldname,
																filename,
																filesys_filename,
																filehash,
																width,
																height,
																mediatype,
																size,
																filetype,
																alttext,
																keywords,
																create_at,
																create_from,
																change_from
															)
															VALUES
															(
																:id_mid,
																:id_count,
																:id_lang,
																:id_dev,
																:id_clid,
																:id_mpid,
																:id_page,
																:fieldname,
																:filename,
																:filesys_filename,
																:filehash,
																:width,
																:height,
																:mediatype,
																:size,
																:filetype,
																:alttext,
																:keywords,
																:create_at,
																:create_from,
																:change_from
															)
															');
						$query->bindValue(':id_mid', $idNew, PDO::PARAM_INT);
						$query->bindValue(':id_count', 0, PDO::PARAM_INT);
						$query->bindValue(':id_lang', 0, PDO::PARAM_INT);
						$query->bindValue(':id_dev', 0, PDO::PARAM_INT);
						$query->bindValue(':id_clid', $_SESSION['admin']['USER']['activeClient'], PDO::PARAM_INT);
						$query->bindValue(':id_mpid', trim($aData[0][0][0]['id_mpid']), PDO::PARAM_INT);
						$query->bindValue(':id_page', trim($aData[0][0][0]['id_page']), PDO::PARAM_INT);
						$query->bindValue(':fieldname', trim($aData[0][0][0]['fieldname']), PDO::PARAM_INT);
						$query->bindValue(':filename', trim($aData[0][0][0]['filename']), PDO::PARAM_STR);
						$query->bindValue(':filesys_filename', trim($aData[0][0][0]['filesys_filename']), PDO::PARAM_STR);
						$query->bindValue(':filehash', trim($aData[0][0][0]['filehash']), PDO::PARAM_STR);
						$query->bindValue(':width', trim($aData[0][0][0]['width']), PDO::PARAM_INT);
						$query->bindValue(':height', trim($aData[0][0][0]['height']), PDO::PARAM_INT);
						$query->bindValue(':mediatype', trim($aData[0][0][0]['mediatype']), PDO::PARAM_STR);
						$query->bindValue(':size', trim($aData[0][0][0]['size']), PDO::PARAM_INT);
						$query->bindValue(':filetype', trim($aData[0][0][0]['filetype']), PDO::PARAM_STR);
						$query->bindValue(':alttext', trim($aData[0][0][0]['alttext']), PDO::PARAM_STR);
						$query->bindValue(':keywords', trim($aData[0][0][0]['keywords']), PDO::PARAM_STR);
						$query->bindValue(':create_at', $now, PDO::PARAM_STR);
						$query->bindValue(':create_from', trim($_SESSION['admin']['USER']['id_real']), PDO::PARAM_INT);
						$query->bindValue(':change_from', trim($_SESSION['admin']['USER']['id_real']), PDO::PARAM_INT);
						$query->execute();
					}
				}
			}
		}
		
		$return['files'] = array();
		$return['files']['fieldname'] = $field;
		$return['files']['sysname'] = $filename;
		$return['files']['name'] = $filenameOrg;
		$return['files']['idfile'] = $idNew;
		$return['files']['url'] = $varSQL['url'];
		$return['files']['urlRead'] = $varSQL['urlRead'];
		$return['files']['cb'] = $varSQL['cb'];

		echo json_encode($return);
	}
}
?>