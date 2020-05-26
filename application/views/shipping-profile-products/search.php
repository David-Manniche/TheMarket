<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
    'listserial' => Labels::getLabel('LBL_Sr', $siteLangId),
    'image' => '',
    'product_name' => Labels::getLabel('LBL_Name', $siteLangId),
    'actions' => ''
);

$tbl = new HtmlElement('table', array('width' => '100%', 'class' => 'table'));
$th = $tbl->appendElement('thead')->appendElement('tr', array('class' => ''));
foreach ($arr_flds as $key => $val) {
    $th->appendElement('th', array(), $val);
}
if ($page ==1) {
    $sr_no = 0;
} else {
    $sr_no = ($page-1) * $pageSize;
}
foreach ($productsData as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr', array());

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no, true);
                break;
            case 'image':
                $imgData = '<img class="mr-2" src="'. CommonHelper::generateUrl("Image", "product", array($row['product_id'], "SMALL", 0, 0, 1 )) .'" alt="" width="50">';
                $td->appendElement('plaintext', array(), $imgData, true);
                break;
            case 'actions':
                if (isset($profileData['shipprofile_default']) && $profileData['shipprofile_default'] != 1) {
                    $ul = $td->appendElement("ul", array('class' => 'actions'), '', true);
                    $li = $ul->appendElement("li");
                    $li->appendElement('a', array('href' => 'javascript:void(0)', 'onClick' => 'removeProductFromProfile('. $row['selprod_id'] .')', 'title' => Labels::getLabel('LBL_Remove_Product_from_profile', $siteLangId), true), '<i class="fa fa-trash"></i>', true);
                }
            break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}
if (empty($productsData)) {
    //echo $tbl->getHtml();
    $message = Labels::getLabel('LBL_No_Records_Found', $siteLangId);
    $this->includeTemplate('_partial/no-record-found.php', array('siteLangId'=>$siteLangId,'message'=>$message));
} else {
    $frm = new Form('frmProductListing', array('id' => 'frmProductListing'));
    $frm->setFormTagAttribute('class', 'web_form last_td_nowrap');
    $frm->setFormTagAttribute('onsubmit', 'formAction(this, reloadListProduct); return(false);');
    echo $frm->getFormTag();
    echo $tbl->getHtml(); ?>
</form> <?php $postedData['page'] = $page;
    echo FatUtility::createHiddenFormFromData($postedData, array('name' => 'frmProductSearchPaging'));
    $pagingArr = array('pageCount' => $pageCount, 'page' => $page, 'recordCount' => $recordCount, 'siteLangId' => $siteLangId);
    $this->includeTemplate('_partial/pagination.php', $pagingArr, false);
}
