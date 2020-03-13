<?php

// zuweisung der assets anhand der contactdaten, falls diese durch interne mitarbeiter erstellt wurden - für statistik
// einmaliger aufruf
$date = new DateTime();
$now = $date->format('Y-m-d H:i:s');


$qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_asid,
                ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.content,
                ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_count,
                ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_lang
                 FROM ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni
                 WHERE ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_pcid = (:id_pcid)
                    AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.content LIKE "%partnercontact%"
                    AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_count <> (:nul)
                    AND ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_lang <> (:nul)
                 GROUP BY ' . $CONFIG['db'][0]['prefix'] . '_assetspageselements_uni.id_asid
                ';
$queryP = $CONFIG['dbconn'][0]->prepare($qry);
$queryP->bindValue(':id_pcid', 29, PDO::PARAM_INT);
$queryP->bindValue(':nul', 0, PDO::PARAM_INT);
$queryP->execute();
$rowsP = $queryP->fetchAll(PDO::FETCH_ASSOC);
$numP = $queryP->rowCount();

foreach($rowsP as $rowP){
    $name = '';
    $street = '';
    $zip = '';
    $city = '';
    $email = '';
    $url = '';
    $emailCompl = '';
    $urlCompl = '';
    
    $str = $rowP['content'];
    
    $search = '/(pc_company_name">)(.*)(<\/span>)/U';
    preg_match($search, $str, $matches);
    $name = $matches[2];
    
    $search = '/(pc_address1">)(.*)(<\/span>)/U';
    preg_match($search, $str, $matches);
    $street = $matches[2];
    
    $search = '/(pc_zipcode">)(.*)(<\/span>)/U';
    preg_match($search, $str, $matches);
    $aZip = explode(' ', $matches[2]);
    $zip = array_shift($aZip);
    $city = implode(' ', $aZip);
    
    $search = '/(pc_email">)(.*)(<\/span>)/U';
    preg_match($search, $str, $matches);
    $email = $matches[2];
    $emailCompl = $matches[2];
    
    $search = '/(pc_url">)(.*)(<\/span>)/U';
    preg_match($search, $str, $matches);
    $url = $matches[2];
    $urlCompl = $matches[2];
    $url = str_replace('www.', '', $url);
    $url = str_replace('https://', '', $url);
    $url = str_replace('http://', '', $url);
 
    if($name == '' && $email == '' && $url == ''){
        $search = '/(<span>)(.*)(@)(.*)(<\/span>)/U';
        preg_match($search, $str, $matches);
        $email = $matches[2] . $matches[3] . $matches[4];
        $emailCompl = $matches[2] . $matches[3] . $matches[4];

        $search = '/(>)(.*)(www.)(.*)(<\/span>)/U';
        preg_match($search, $str, $matches);
        $url = $matches[3] . $matches[4];
        $url = $matches[4];
    }
    
    if($email != ''){
        $aEmail = explode('@', $email);
        $email = $aEmail[1];
    }
    
    ###################################################
    
    if($name != '' || $email != '' || $url != ''){
        ($name == '') ? $nameS = 'xxx' : $nameS = $name;
        ($email == '') ? $emailS = 'xxx' : $emailS = $email;
        ($url == '') ? $urlS = 'xxx' : $urlS = $url;
        
        $qry = 'SELECT ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid,
                        ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name,
                        ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.zipcode,
                        ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.city,
                        ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.email,
                        ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.url,
                        ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_countid,
                        ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_langid
                         FROM ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
                         WHERE (' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.company_name LIKE "%' . $nameS . '%"
                            OR ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.email LIKE "%' . $emailS . '%"
                            OR ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.url LIKE "%' . $urlS . '%")
                            AND ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni.id_pcid <> 29
                        ';
        $queryP2 = $CONFIG['dbconn'][0]->prepare($qry);
        $queryP2->execute();
        $rowsP2 = $queryP2->fetchAll(PDO::FETCH_ASSOC);
        $numP2 = $queryP2->rowCount();
        $pcid = '';
        if($numP2 == 1) $pcid = $rowsP2[0]['id_pcid'];
        if($numP2 > 1){
            $pcid = $rowsP2[0]['id_pcid'];
            $aPC = array();
            foreach($rowsP2 as $rowP2){
                if(!array_key_exists($rowP2['id_pcid'], $aPC)) $aPC[$rowP2['id_pcid']] = 0;
                if(substr_count($str, $rowP2['company_name']) > 0 && $rowP2['company_name'] != ''){
                    $aPC[$rowP2['id_pcid']] += 5;
                }
                if($rowP2['company_name'] == $name  && $rowP2['company_name'] != '') $aPC[$rowP2['id_pcid']] += 5;
                
                if(substr_count($str, $rowP2['zipcode']) > 0 && $rowP2['zipcode'] != ''){
                    $aPC[$rowP2['id_pcid']] += 3;
                }
                if($rowP2['zipcode'] == $zip  && $rowP2['zipcode'] != '') $aPC[$rowP2['id_pcid']] += 3;
                
                if(substr_count($str, $rowP2['city']) > 0 && $rowP2['city'] != ''){
                    $aPC[$rowP2['id_pcid']] += 3;
                }
                if($rowP2['city'] == $city  && $rowP2['city'] != '') $aPC[$rowP2['id_pcid']] += 3;
                
                if(substr_count($str, $rowP2['email']) > 0 && $rowP2['email'] != ''){
                    $aPC[$rowP2['id_pcid']] += 1;
                }
                if($rowP2['email'] == $emailCompl  && $rowP2['email'] != '') $aPC[$rowP2['id_pcid']] += 3;
                
                if(substr_count($str, $rowP2['url']) > 0 && $rowP2['url'] != ''){
                    $aPC[$rowP2['id_pcid']] += 1;
                }
                if($rowP2['url'] == $urlCompl  && $rowP2['url'] != '') $aPC[$rowP2['id_pcid']] += 1;
                
                
                if($rowP2['id_countid'] == $rowP['id_count']) $aPC[$rowP2['id_pcid']] += 5;
                if($rowP2['id_langid'] == $rowP['id_lang']) $aPC[$rowP2['id_pcid']] += 5;
                if($rowP2['id_countid'] == $rowP['id_count'] && $rowP2['id_langid'] == $rowP['id_lang']){
                    $aPC[$rowP2['id_pcid']] += 5;
                }
            }
            arsort($aPC);
            $pcid = key($aPC);
        }
        
        if($pcid == ''){
            $queryI = $CONFIG['dbconn'][0]->prepare('
                                    INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_
                                    (id_count, id_lang, id_dev, id_cl, create_at, create_from, change_from)
                                    VALUES
                                    (:nul, :nul, :nul, :id_cl, :create_at, :create_from, :create_from)
                                    ');
            $queryI->bindValue(':nul', 0, PDO::PARAM_INT);
            $queryI->bindValue(':id_cl', 1, PDO::PARAM_INT);
            $queryI->bindValue(':create_at', $now, PDO::PARAM_STR);
            $queryI->bindValue(':create_from', 888888888, PDO::PARAM_INT);
            $queryI->execute();
            $pcid = $CONFIG['dbconn'][0]->lastInsertId();
            
            
            if(!isset($name)) $name = '';
            if(!isset($street)) $street = '';
            if(!isset($zip)) $zip = '';
            if(!isset($city)) $city = '';
            if(!isset($emailCompl)) $emailCompl = '';
            if(!isset($urlCompl)) $urlCompl = '';
             
            $qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_ext
                                    (id_count, id_lang, id_dev, id_cl, restricted_all, id_pcid, reseller_id, organisationid, company_name, address1, address2, address3, zipcode, city, id_countid, id_langid, phone, mobile, email, url, program_tier, bsd_silver, bsd_gold, bsd_diamond, parent_program_name, create_at, create_from, change_from)
                            VALUES
                                    (:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_pcid, :reseller_id, :organisationid, :company_name, :address1, :address2, :address3, :zipcode, :city, :id_countid, :id_langid, :phone, :mobile, :email, :url, :program_tier, :bsd_silver, :bsd_gold, :bsd_diamond, :parent_program_name, :now, :create_from, :create_from)
                            ';
            $queryC = $CONFIG['dbconn'][0]->prepare($qry);
            $queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
            $queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
            $queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
            $queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
            $queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
            $queryC->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
            $queryC->bindValue(':reseller_id', '', PDO::PARAM_STR);
            $queryC->bindValue(':organisationid', '', PDO::PARAM_STR);
            $queryC->bindValue(':company_name', $name, PDO::PARAM_STR);
            $queryC->bindValue(':address1', $street, PDO::PARAM_STR);
            $queryC->bindValue(':address2', '', PDO::PARAM_STR);
            $queryC->bindValue(':address3', '', PDO::PARAM_STR);
            $queryC->bindValue(':zipcode', $zip, PDO::PARAM_STR);
            $queryC->bindValue(':city', $city, PDO::PARAM_STR);
            $queryC->bindValue(':id_countid', $rowP['id_count'], PDO::PARAM_STR);
            $queryC->bindValue(':id_langid', $rowP['id_lang'], PDO::PARAM_STR);
            $queryC->bindValue(':phone', '', PDO::PARAM_STR);
            $queryC->bindValue(':mobile', '', PDO::PARAM_STR);
            $queryC->bindValue(':email', $emailCompl, PDO::PARAM_STR);
            $queryC->bindValue(':url', $urlCompl, PDO::PARAM_STR);
            $queryC->bindValue(':program_tier', '', PDO::PARAM_STR);
            $queryC->bindValue(':bsd_silver', '', PDO::PARAM_STR);
            $queryC->bindValue(':bsd_gold', '', PDO::PARAM_STR);
            $queryC->bindValue(':bsd_diamond', '', PDO::PARAM_STR);
            $queryC->bindValue(':parent_program_name', '#NV', PDO::PARAM_STR);
            $queryC->bindValue(':now', $now, PDO::PARAM_STR);
            $queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
            $queryC->bindValue(':create_from', 888888888, PDO::PARAM_INT); 
            $queryC->execute();
            $numC = $queryC->rowCount();
            
            $qry = 'INSERT INTO ' . $CONFIG['db'][0]['prefix'] . '_partnercompanies_uni
                                    (id_count, id_lang, id_dev, id_cl, restricted_all, id_pcid, reseller_id, organisationid, company_name, address1, address2, address3, zipcode, city, id_countid, id_langid, phone, mobile, email, url, program_tier, bsd_silver, bsd_gold, bsd_diamond, parent_program_name, create_at, create_from, change_from)
                            VALUES
                                    (:id_count, :id_lang, :id_dev, :id_cl, :restricted_all, :id_pcid, :reseller_id, :organisationid, :company_name, :address1, :address2, :address3, :zipcode, :city, :id_countid, :id_langid, :phone, :mobile, :email, :url, :program_tier, :bsd_silver, :bsd_gold, :bsd_diamond, :parent_program_name, :now, :create_from, :create_from)
                            ';
            $queryC = $CONFIG['dbconn'][0]->prepare($qry);
            $queryC->bindValue(':id_count', 0, PDO::PARAM_INT);
            $queryC->bindValue(':id_lang', 0, PDO::PARAM_INT);
            $queryC->bindValue(':id_dev', 0, PDO::PARAM_INT);
            $queryC->bindValue(':id_cl', 1, PDO::PARAM_INT);
            $queryC->bindValue(':restricted_all', 0, PDO::PARAM_INT);
            $queryC->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
            $queryC->bindValue(':reseller_id', '', PDO::PARAM_STR);
            $queryC->bindValue(':organisationid', '', PDO::PARAM_STR);
            $queryC->bindValue(':company_name', $name, PDO::PARAM_STR);
            $queryC->bindValue(':address1', $street, PDO::PARAM_STR);
            $queryC->bindValue(':address2', '', PDO::PARAM_STR);
            $queryC->bindValue(':address3', '', PDO::PARAM_STR);
            $queryC->bindValue(':zipcode', $zip, PDO::PARAM_STR);
            $queryC->bindValue(':city', $city, PDO::PARAM_STR);
            $queryC->bindValue(':id_countid', $rowP['id_count'], PDO::PARAM_INT);
            $queryC->bindValue(':id_langid', $rowP['id_lang'], PDO::PARAM_INT);
            $queryC->bindValue(':phone', '', PDO::PARAM_STR);
            $queryC->bindValue(':mobile', '', PDO::PARAM_STR);
            $queryC->bindValue(':email', $emailCompl, PDO::PARAM_STR);
            $queryC->bindValue(':url', $urlCompl, PDO::PARAM_STR);
            $queryC->bindValue(':program_tier', '', PDO::PARAM_STR);
            $queryC->bindValue(':bsd_silver', '', PDO::PARAM_STR);
            $queryC->bindValue(':bsd_gold', '', PDO::PARAM_STR);
            $queryC->bindValue(':bsd_diamond', '', PDO::PARAM_STR);
            $queryC->bindValue(':parent_program_name', '#NV', PDO::PARAM_STR);
            $queryC->bindValue(':now', $now, PDO::PARAM_STR);
            $queryC->bindValue(':nultime', '0000-00-00 00:00:00', PDO::PARAM_STR);
            $queryC->bindValue(':create_from', 888888888, PDO::PARAM_INT); 
            $queryC->execute();
            $numC = $queryC->rowCount();    
            
        }
        
        
        $aTables = array('assets', 'assetsproducts', 'assetspages', 'assetspageselements');
        $aTab = array('ext','loc','res','tmp','uni');
        
        foreach($aTables as $table){
            foreach($aTab as $tab){
                $qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_' . $table . '_' . $tab . ' SET
                            id_pcid = (:id_pcid)
                        WHERE id_asid = (:id_asid)
                        ';
                $queryA = $CONFIG['dbconn'][0]->prepare($qry);
                $queryA->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
                $queryA->bindValue(':id_asid', $rowP['id_asid'], PDO::PARAM_INT);
                $queryA->execute();
                $numA = $queryA->rowCount();                   
                $numA = $queryA->rowCount(); 
                echo $numA . '-';
            }
        }
        echo '<br>';

        $qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assets2export_ SET
                    id_pcid = (:id_pcid)
                WHERE id_asid = (:id_asid)
                ';
        $queryA = $CONFIG['dbconn'][0]->prepare($qry);
        $queryA->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
        $queryA->bindValue(':id_asid', $rowP['id_asid'], PDO::PARAM_INT);
        $queryA->execute();
        $numA = $queryA->rowCount();    

        $qry = 'UPDATE ' . $CONFIG['db'][0]['prefix'] . '_assets2download_ SET
                    id_pcid = (:id_pcid)
                WHERE id_asid = (:id_asid)
                ';
        $queryA = $CONFIG['dbconn'][0]->prepare($qry);
        $queryA->bindValue(':id_pcid', $pcid, PDO::PARAM_INT);
        $queryA->bindValue(':id_asid', $rowP['id_asid'], PDO::PARAM_INT);
        $queryA->execute();
        $numA = $queryA->rowCount();    

        echo $rowP['id_asid'] . ' - ' . $name . ' - ' . $street . ' - ' . $zip . ' - ' . $city . ' - ' . $emailCompl . ' - ' . $urlCompl . ' --- ' . $pcid .'#'.$numP2.'<br>';

    }
}
?>