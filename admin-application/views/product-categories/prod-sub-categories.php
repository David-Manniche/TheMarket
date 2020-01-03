<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
if(count($childCategories) > 0){
    $arr_flds = array(
            'select_all'=>Labels::getLabel('LBL_Select_all', $adminLangId),
            'prodcat_display_order'=>Labels::getLabel('LBL_POS', $adminLangId),
            'prodcat_identifier'=>Labels::getLabel('LBL_Category_Name', $adminLangId),
            'category_products' => Labels::getLabel('LBL_Products', $adminLangId),
            'prodcat_active' => Labels::getLabel('LBL_Publish', $adminLangId),
            'action' => Labels::getLabel('', $adminLangId),
        );
        
    foreach ($childCategories as $sn => $row) {
        $tr = new HtmlElement('tr', array());
        if ($row['prodcat_active'] == applicationConstants::ACTIVE) {
            $tr->setAttribute("id", $row['prodcat_id']);
            $tr->setAttribute("class", $row['prodcat_parent'].'-subcategory');
        }

        if ($row['prodcat_active'] != applicationConstants::ACTIVE) {
            $tr->setAttribute("class", "nodrag nodrop");
        }   
        foreach ($arr_flds as $key => $val) { 
            $td = $tr->appendElement('td');
            switch ($key) {
                case 'select_all':
                    $td->appendElement('plaintext', array(), '<label><span class="checkbox"><input class="selectItem--js" type="checkbox" name="prodcat_ids[]" value='.$row['prodcat_id'].'><i class="input-helper"></i></span></label>', true);
                    break;
                case 'prodcat_display_order':
                    $td->appendElement('plaintext', array(), '<input class="form-control form-control-sm form-control-position" type="text" value="'.$row[$key].'" name="prodcat_display_order">', true);    
                    break;
                case 'prodcat_identifier':                    
                    $td->appendElement('plaintext', array(), '<a href="javascript:void(0);" onClick="displaySubCategories('.$row['prodcat_id'].')">'.$row[$key].' <i class="ion-chevron-right"></i></a>', true);
                    break;
                case 'category_products':
                    $td->appendElement('plaintext', array(), '<a href="javascript:void(0);" class="badge badge-secondary badge-pill">'.$row[$key].'</a>', true);
                    break;
                case 'prodcat_active':
                    $active = "";
                    if ($row['prodcat_active']) {
                        $active = 'checked';
                    }
                    $statusAct = ($canEdit === true) ? 'toggleStatus(event,this,' .applicationConstants::YES. ')' : 'toggleStatus(event,this,' .applicationConstants::NO. ')';
                    $statusClass = ($canEdit === false) ? 'disabled' : '';
                    $str='<label class="statustab -txt-uppercase">
                     <input '.$active.' type="checkbox" id="switch'.$row['prodcat_id'].'" value="'.$row['prodcat_id'].'" onclick="'.$statusAct.'" class="switch-labels"/>
                    <i class="switch-handles '.$statusClass.'"></i></label>';
                    $td->appendElement('plaintext', array(), $str, true);
                    break;
                case 'action':
           
                    if ($canEdit) {
                        $div = $td->appendElement("div", array("class"=>"hidden-tools"));
                        $innerDiv = $div->appendElement("div", array('class'=>'btn-group'));
                        $innerDiv->appendElement('button', array('class'=>'btn btn-secondary btn-sm dropdown-toggle', 'type'=>'button', 'data-toggle'=>'dropdown', 'aria-haspopup'=>'true', 'aria-expanded'=>'false'), '<i class="ion ion-ios-settings"></i>', true);
                        
                        $dropDownDiv = $innerDiv->appendElement("div", array('class'=>'dropdown-menu'));
                        $dropDownDiv->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'dropdown-item', 'title'=>Labels::getLabel('LBL_Add_Product', $adminLangId)), Labels::getLabel('LBL_Add_Product', $adminLangId), true);
                        $dropDownDiv->appendElement("div", array('class'=>'dropdown-divider'));
                        $url = commonHelper::generateUrl('ProductCategories', 'form', array($row['prodcat_id']));
                        if($row['prodcat_parent'] > 0){
                            $url = commonHelper::generateUrl('ProductCategories', 'form', array($row['prodcat_id'], $row['prodcat_parent']));
                        }
                        $dropDownDiv->appendElement('a', array('href'=>$url, 'class'=>'dropdown-item', 'title'=>Labels::getLabel('LBL_Edit', $adminLangId)), Labels::getLabel('LBL_Edit', $adminLangId), true);
                        $dropDownDiv->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'dropdown-item', 'title'=>Labels::getLabel('LBL_Delete', $adminLangId), "onclick"=>"deleteRecord(".$row['prodcat_id'].")"), Labels::getLabel('LBL_Delete', $adminLangId), true);
                    }
                    break;
                default:
                    $td->appendElement('plaintext', array(), $row[$key], true);
                    break;
            }
        }
        echo $tr->getHtml();
    }
}


    

