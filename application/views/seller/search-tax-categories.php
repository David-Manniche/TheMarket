<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
    'listserial' => 'Sr.',
    'taxcat_name' => Labels::getLabel('LBL_Tax_Category', $siteLangId)
);
if ($activatedTaxServiceId) {
    $arr_flds['taxcat_code'] = Labels::getLabel('LBL_Tax_Code', $siteLangId);
} else {
    $arr_flds['tax_rates'] = Labels::getLabel('LBL_Tax_Rates', $siteLangId);
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
            case 'tax_rates':
                $ul = $td->appendElement("ul", array("class"=>"actions"), '', true);
                $li = $ul->appendElement("li");
                $li->appendElement(
                    'a',
                    array('href'=>UrlHelper::generateUrl('Seller','taxRules', array($row['taxcat_id'])), 'class'=>'', 'title'=>Labels::getLabel('LBL_Tax_Rates', $siteLangId)),
                    '<i class="fa fa-eye"></i>',
                    true
                );
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
