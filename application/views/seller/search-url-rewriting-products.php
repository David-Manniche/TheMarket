<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
    'listserial' => '#',
    'product_name' => Labels::getLabel('LBL_Product', $siteLangId),
    'original' => Labels::getLabel('LBL_Original', $siteLangId),
    'custom' => Labels::getLabel('LBL_Custom', $siteLangId),
);

$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table--hovered volDiscountList-js'));
$thead = $tbl->appendElement('thead');
$th = $thead->appendElement('tr', array('class' => ''));

foreach ($arr_flds as $key => $val) {
    if ('select_all' == $key) {
        $th->appendElement('th')->appendElement('plaintext', array(), '<label class="checkbox"><input title="'.$val.'" type="checkbox" onclick="selectAll($(this))" class="selectAll-js"><i class="input-helper"></i></label>', true);
    } else {
        $th->appendElement('th', array(), $val);
    }
}
if ($page ==1) {
    $sr_no = 0;
} else {
    $sr_no = ($page-1) * $pageSize;
}
foreach ($arrListing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr', array());
    $selProdId = $row['selprod_id'];
    foreach ($arr_flds as $key => $val) {
        $tr->setAttribute('id', 'row-'.$selProdId);
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'select_all':
                $td->appendElement('plaintext', array(), '<label class="checkbox"><input class="selectItem--js" type="checkbox" name="selprod_ids['.$selProdId.']" value='.$selProdId.'><i class="input-helper"></i></label>', true);
                break;
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no, true);
                break;
            case 'product_name':
                // last Param of getProductDisplayTitle function used to get title in html form.
                $productName = SellerProduct::getProductDisplayTitle($selProdId, $siteLangId, false);
                $td->appendElement('plaintext', array(), $productName, true);
                break;
            case 'original':
                $td->appendElement('plaintext', array(), "<input type='text' disabled name='original_url' value='products/view/".$selProdId."' data-selprod_id='".$selProdId."'>", true);
                break;
            case 'custom':
                $post = "post";
                $td->appendElement('plaintext', array(), '<input type="text" name="custom_url" onkeyup = "getSlugUrl(this,this.value, '.$selProdId.', \''.$post.'\')" value="'.$row['urlrewrite_custom'].'" data-selprod_id="'.$selProdId.'" data-url_rewriting_id="'.$row['urlrewrite_id'].'"><small class="text--small">'.CommonHelper::generateFullUrl('Products', 'View', array($selProdId), '/').'</small>', true);
                break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}
if (count($arrListing) == 0) {
    $tbl->appendElement('tr', array('class' => 'noResult--js'))->appendElement(
        'td',
        array('colspan'=>count($arr_flds)),
        Labels::getLabel('LBL_No_Record_Found', $siteLangId)
    );
}

$frm = new Form('frmSeoListing', array('id'=>'frmSeoListing'));
$frm->setFormTagAttribute('class', 'form');

echo $frm->getFormTag();
echo $tbl->getHtml(); ?>
</form>
<?php
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array('name' => 'frmsearchUrlRewritingProductsPaging'));

$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'callBackJsFunc' => 'goToSearchPage','adminLangId'=>$siteLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
