<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
    'listserial' => 'Sr.',
    'taxcat_name' => Labels::getLabel('LBL_Tax_Category', $siteLangId),
);
if ($activatedTaxServiceId) {
    $arr_flds['taxcat_code'] = Labels::getLabel('LBL_Tax_Code', $siteLangId);
} else {
    $arr_flds['taxrule_rate'] = Labels::getLabel('LBL_Value', $siteLangId);
    if (FatApp::getConfig('CONF_TAX_COLLECTED_BY_SELLER', FatUtility::VAR_INT, 0)) {
        /* $arr_flds['action'] = Labels::getLabel('LBL_Action', $siteLangId); */
    }
}

$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table'));
$th = $tbl->appendElement('thead')->appendElement('tr', array('class' => ''));
foreach ($arr_flds as $val) {
    $e = $th->appendElement('th', array(), $val);
}

$sr_no = ($page == 1) ? 0 : ($pageSize*($page-1));
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr', array('class' => ''));

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no, true);
                break;
            case 'taxcat_name':
                $td->appendElement('plaintext', array(), $row[$key] . '<br>', true);
                break;
            case 'taxrule_rate':
                /* Error Handling[ */
                if (!isset($row['taxrule_rate'])) {
                    $row['taxrule_rate'] = 0;
                }
				
                if (!isset($row['taxval_is_percent'])) {
                    $row['taxval_is_percent'] = 1;
                }
                /* ] */

                $str = '';
                $str = '<span class="item__price--old">'.CommonHelper::displayTaxFormat($row['taxval_is_percent'], $row['taxrule_rate']).'</span> ';
                $td->appendElement('plaintext', array(), $str, true);
                break;
            case 'action':
                $ul = $td->appendElement("ul", array("class"=>"actions"), '', true);
                $li = $ul->appendElement("li");
                if (FatApp::getConfig('CONF_TAX_COLLECTED_BY_SELLER', FatUtility::VAR_INT, 0)) {
                    $li->appendElement(
                        'a',
                        array('href'=>'javascript:void(0)', 'class'=>'', 'title'=>Labels::getLabel('LBL_Edit', $siteLangId),"onclick"=>"changeTaxRates(".$row['taxcat_id'].")"),
                        '<i class="fa fa-edit"></i>',
                        true
                    );

                    /* Error Handling[ */
                    if (!isset($row['taxval_seller_user_id'])) {
                        $row['taxval_seller_user_id'] = 0;
                    }
                    /* ] */

                    if ($row['taxval_seller_user_id'] == $userId) {
                        $li = $ul->appendElement("li");
                        $li->appendElement(
                            'a',
                            array('href'=>'javascript:void(0)', 'class'=>'', 'title'=>Labels::getLabel('LBL_Reset_to_Default', $siteLangId),"onclick"=>"resetCatTaxRates(".$row['taxcat_id'].")"),
                            '<i class="fa fa-undo"></i>',
                            true
                        );
                    }
                }
                break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}

echo $tbl->getHtml();
if (count($arr_listing) == 0) {
    $message = Labels::getLabel('LBL_No_Record_found', $siteLangId);
    $this->includeTemplate('_partial/no-record-found.php', array('siteLangId'=>$siteLangId,'message'=>$message));
}
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array('name' => 'frmSearchTaxCatPaging'));

$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'callBackJsFunc' => 'goToSearchPage');
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
