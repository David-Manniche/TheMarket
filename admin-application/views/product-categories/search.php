<?php defined('SYSTEM_INIT') or die('Invalid Usage.');  
    $arr_flds = array(
            'select_all'=>Labels::getLabel('LBL_Select_all', $adminLangId),
            'prodcat_display_order'=>Labels::getLabel('LBL_POS', $adminLangId),
            'prodcat_identifier'=>Labels::getLabel('LBL_Name', $adminLangId),
            'category_products' => Labels::getLabel('LBL_Products', $adminLangId),
            'prodcat_active' => Labels::getLabel('LBL_Publish', $adminLangId),
            'action' => '',
        );
    $tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table--hovered   table-category-accordion','id'=>'prodcat'));
    $th = $tbl->appendElement('thead')->appendElement('tr');
    foreach ($arr_flds as $key => $val) {
        if ('select_all' == $key) {
            $th->appendElement('th', array('width'=>'5%'))->appendElement('plaintext', array(), '<label class="checkbox"><input title="'.$val.'" type="checkbox" onclick="selectAll( $(this) )" class="selectAll-js"><i class="input-helper"></i></label>', true);
        } else if('prodcat_display_order' == $key || 'action' == $key){
            $e = $th->appendElement('th', array('width'=>'10%'), $val);
        } else if('category_products' == $key || 'prodcat_active' == $key){
            $e = $th->appendElement('th', array('width'=>'15%'), $val);
        } else if('prodcat_identifier' == $key){
            $e = $th->appendElement('th', array('width'=>'35%'), $val);
        } else {
            $e = $th->appendElement('th', array(), $val);
        }
    }

    foreach ($arr_listing as $sn => $row) {
        $tr = $tbl->appendElement('tr');
        if ($row['prodcat_active'] == applicationConstants::ACTIVE) {
            $tr->setAttribute("id", $row['prodcat_id']);
        }
        foreach ($arr_flds as $key => $val) { 
            $td = $tr->appendElement('td');
            switch ($key) {
                case 'select_all':
                    $td->appendElement('plaintext', array(), '<label><span class="checkbox"><input class="selectItem--js" type="checkbox" name="prodcat_ids[]" value='.$row['prodcat_id'].'><i class="input-helper"></i></span></label>', true);
                    break;
                case 'prodcat_display_order':
                    $td->appendElement('plaintext', array(), '', true);                    break;
                case 'prodcat_identifier':                    
                    $td->appendElement('plaintext', array(), '<a href="javascript:void(0);" onClick="displaySubCategories(this, 1)">'.$row[$key].' <i class="ion-chevron-right"></i></a>', true);
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
    }
    
    if (count($arr_listing) == 0) {
        $tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Labels::getLabel('LBL_No_Records_Found', $adminLangId));
    }

    $frm = new Form('frmProdCatListing', array('id'=>'frmProdCatListing'));
    $frm->setFormTagAttribute('class', 'web_form last_td_nowrap');
    $frm->setFormTagAttribute('onsubmit', 'formAction(this, reloadList ); return(false);');
    $frm->setFormTagAttribute('action', CommonHelper::generateUrl('ProductCategories', 'toggleBulkStatuses'));
    $frm->addHiddenField('', 'status');
    echo $frm->getFormTag();
    echo $frm->getFieldHtml('status');
	echo $tbl->getHtml(); 
?>
	</form>

