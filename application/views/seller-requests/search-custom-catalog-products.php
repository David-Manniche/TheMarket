<?php 
$variables= array('siteLangId'=>$siteLangId, 'action'=>$action);
$this->includeTemplate('seller-requests/_partial/requests-navigation.php', $variables, false); ?>
<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
    'listserial'=>'Sr.',
    'product_identifier' => Labels::getLabel('LBL_Product', $siteLangId),
    'preq_added_on' => Labels::getLabel('LBL_Added_on', $siteLangId),
    'preq_requested_on' => Labels::getLabel('LBL_Requested_on', $siteLangId),
    'preq_status' => Labels::getLabel('LBL_Status', $siteLangId),
);
if ($canEdit) {
    $arr_flds['action'] = '';
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
            case 'product_identifier':
                $td->appendElement('plaintext', array(), $row['product_name'] . '<br>', true);
                $td->appendElement('plaintext', array(), '('.$row[$key].')', true);
                break;
            case 'preq_status':
                $td->appendElement('span', array('class' => 'label label-inline '. $statusClassArr[$row[$key]]), $statusArr[$row[$key]] . '<br>', true);
                $td->appendElement('br', array());
                $td->appendElement('plaintext', array(), ($row['preq_status_updated_on'] != '0000-00-00 00:00:00') ? FatDate::Format($row['preq_status_updated_on']) : '', true);
                break;
            case 'preq_added_on':
                $td->appendElement('plaintext', array(), FatDate::Format($row[$key]), true);
                break;
            case 'preq_requested_on':
                $td->appendElement('plaintext', array(), ($row[$key] != '0000-00-00 00:00:00') ? FatDate::Format($row[$key]) : Labels::getLabel('LBL_NA', $siteLangId), true);
                break;
            case 'action':
                $ul = $td->appendElement("ul", array('class'=>'actions'), '', true);
                $li = $ul->appendElement("li");
                if ($row['preq_status'] == ProductRequest::STATUS_PENDING) {
                    $li->appendElement(
                        'a',
                        array('href'=>UrlHelper::generateUrl('Seller', 'customCatalogProductForm', array($row['preq_id'])), 'class'=>'','title'=>Labels::getLabel('LBL_Edit', $siteLangId)),
                        '<i class="fa fa-edit"></i>',
                        true
                    );

                    $li = $ul->appendElement("li");
                    $li->appendElement(
                        'a',
                        array('href' => 'javascript:void(0)', 'onclick' => 'customCatalogInfo(' . $row['preq_id'] . ')', 'class' => '', 'title' => Labels::getLabel('LBL_product_Info', $siteLangId), true),
                        '<i class="fa fa-eye"></i>',
                        true
                    );

                    /* $li = $ul->appendElement("li");
                    $li->appendElement("a", array('title' => Labels::getLabel('LBL_Product_Images', $siteLangId), 'onclick' => 'customCatalogProductImages('.$row['preq_id'].')', 'href'=>'javascript:void(0)'), '<i class="fas fa-images"></i>', true); */
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
    $message = Labels::getLabel('LBL_No_Records_Found', $siteLangId);
    $this->includeTemplate('_partial/no-record-found.php', array('siteLangId'=>$siteLangId,'message'=>$message));
}
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array('name' => 'frmCatalogProductSearchPaging'));

$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'callBackJsFunc' => 'goToCustomCatalogProductSearchPage');
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
