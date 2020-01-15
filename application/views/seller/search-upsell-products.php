<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
    // 'select_all'=>Labels::getLabel('LBL_Select_all', $siteLangId),
    'product_name' => Labels::getLabel('LBL_Product_Name', $siteLangId),
    'upsell_products' => Labels::getLabel('LBL_Buy_Together_Products', $siteLangId)
);

$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table--hovered volDiscountList-js'));
$thead = $tbl->appendElement('thead');
$th = $thead->appendElement('tr', array('class' => ''));

foreach ($arr_flds as $key => $val) {
    if ('product_name' == $key) {
        $th->appendElement('th', array('width' => '25%'), $val);
    } else {
        $th->appendElement('th', array('width' => '75%'), $val);
    }
}
foreach ($arrListing as $selProdId => $upsellProds) {
    $tr = $tbl->appendElement('tr', array());
    foreach ($arr_flds as $key => $val) {
        $tr->setAttribute('id', 'row-'.$selProdId);
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'select_all':
                $td->appendElement('plaintext', array(), '<label class="checkbox"><input class="selectItem--js" type="checkbox" name="selprod_ids['.$selProdId.']" value='.$selProdId.'><i class="input-helper"></i></label>', true);
                break;
            case 'product_name':
                // last Param of getProductDisplayTitle function used to get title in html form.
                $productName = SellerProduct::getProductDisplayTitle($selProdId, $siteLangId, true);
                $td->appendElement('plaintext', array(), $productName, true);
                break;
            case 'upsell_products':
                $ul = $td->appendElement("ul", array("class"=>"list-tags"));
                foreach ($upsellProds as $upsellProd) {
                    $li = $ul->appendElement("li");
                    $li->appendElement('plaintext', array(), '<span>'.$upsellProd['selprod_title'].' <i class="remove_buyTogether remove_param fal fa-times" onClick="deleteSelprodUpsellProduct('.$selProdId.', '.$upsellProd['selprod_id'].')"></i></span>', true);
                    $li->appendElement('plaintext', array(), '<input type="hidden" name="product_upsell[]" value="'.$upsellProd['selprod_id'].'">', true);
                }
                break;
            default:
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

$frm = new Form('frmVolDiscountListing', array('id'=>'frmVolDiscountListing'));
$frm->setFormTagAttribute('class', 'form');

echo $frm->getFormTag();
echo $tbl->getHtml(); ?>
</form>
<?php
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array ('name' => 'frmSearchVolumeDiscountPaging'));

$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'callBackJsFunc' => 'goToSearchPage','adminLangId'=>$siteLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
