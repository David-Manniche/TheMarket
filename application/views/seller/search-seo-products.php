<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
    // 'select_all'=>Labels::getLabel('LBL_Select_all', $siteLangId),
    'product_name' => Labels::getLabel('LBL_Product_Name', $siteLangId),
    'meta_title' => Labels::getLabel('LBL_Meta_Title', $siteLangId),
    'action' => Labels::getLabel('LBL_Action', $siteLangId),
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

foreach ($arrListing as $sn => $row) {
    $tr = $tbl->appendElement('tr', array());
    $metaId = $row['meta_id'];
    $selProdId = $row['selprod_id'];
    foreach ($arr_flds as $key => $val) {
        $tr->setAttribute('id', 'row-'.$metaId);
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'select_all':
                $td->appendElement('plaintext', array(), '<label class="checkbox"><input class="selectItem--js" type="checkbox" name="selprod_ids['.$metaId.']" value='.$selProdId.'><i class="input-helper"></i></label>', true);
                break;
            case 'product_name':
                // last Param of getProductDisplayTitle function used to get title in html form.
                $productName = SellerProduct::getProductDisplayTitle($selProdId, $siteLangId, true);
                $td->appendElement('plaintext', array(), $productName, true);
                break;
            case 'action':
                $ul = $td->appendElement("ul", array("class"=>"actions actions--centered"), '', true);

                $li = $ul->appendElement('li');
                $li->appendElement(
                    'a',
                    array('href'=>'javascript:void(0)', 'class'=>'',
                    'title'=>Labels::getLabel('LBL_Delete', $siteLangId),"onclick"=>"deleteSellerProductMetaData(".$metaId.")"),
                    '<i class="fa fa-trash"></i>',
                    true
                );
                $li->appendElement(
                    'a',
                    array('href'=>'javascript:void(0)', 'class'=>'',
                    'title'=>Labels::getLabel('LBL_Edit', $siteLangId),"onclick"=>"getProductSeoGeneralForm(".$selProdId.")"),
                    '<i class="fa fa-edit"></i>',
                    true
                );
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
echo FatUtility::createHiddenFormFromData($postedData, array ('name' => 'frmSearchSeoProductsPaging'));

$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'callBackJsFunc' => 'goToSearchPage','adminLangId'=>$siteLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
