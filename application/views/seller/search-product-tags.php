<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
    'listserial'=>'#',
    'product_identifier' => Labels::getLabel('LBL_Product', $siteLangId),
    'tags' => ''
);

$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table--orders'));
$th = $tbl->appendElement('thead')->appendElement('tr', array('class' => ''));
foreach ($arr_flds as $val) {
    $e = $th->appendElement('th', array(), $val);
}
$productsArr = array();
$sr_no = ($page == 1) ? 0 : ($pageSize*($page-1));
foreach ($arr_listing as $sn => $row) {
    $productsArr[] = $row['product_id'];
    $sr_no++;
    $tr = $tbl->appendElement('tr', array('class' => ''));

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no, true);
                break;
            case 'product_identifier':
                $td->appendElement(
                    'a',
                    array('href'=>'javascript:void(0)', 'class'=>'',
                    'title'=>'Links',"onclick"=>"editTagsLangForm(".$row['product_id'].")"),
                    $row['product_name'],
                    true
                );
                break;
            case 'tags':
                $productTags = Product::getProductTags($row['product_id']);
                $tagData = array();
                foreach ($productTags as $key => $data) {
                    $tagData[$key]['id'] = $data['tag_id'];
                    $tagData[$key]['value'] = $data['tag_identifier'];
                }
                $encodedData = json_encode($tagData);
                $td->appendElement('plaintext', array(), "<div class='product-tag' id='product".$row['product_id']."'><input class='tag_name' type='text' name='tag_name".$row['product_id']."' id='get-tags' value='".$encodedData."' data-product_id='".$row['product_id']."'></div>", true);
                break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}
if (count($arr_listing) == 0) {
    $tbl->appendElement('tr', array('class' => 'noResult--js'))->appendElement(
        'td',
        array('colspan'=>count($arr_flds)),
        Labels::getLabel('LBL_No_Record_Found', $siteLangId)
    );
}

echo $tbl->getHtml();
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array('name' => 'frmCatalogProductSearchPaging'));

$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'callBackJsFunc' => 'goToCatalogProductSearchPage');
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
?>
<?php if (count($arr_listing) > 0) { ?>
<script>
var productsArr = [<?php echo '"'.implode('","', $productsArr).'"' ?>];
$("document").ready(function() {
    getTagsAutoComplete = function(){
        var list = [];
        fcom.ajax(fcom.makeUrl('Seller', 'tagsAutoComplete'), '', function(t) {
            var ans = $.parseJSON(t);
            for (i = 0; i < ans.length; i++) {
                list.push({
                    "id" : ans[i].id,
                    "value" : ans[i].tag_identifier,
                });
            }
        });
        return list;
    }
    $.each(productsArr, function( index, value ) {
        tagify = new Tagify(document.querySelector('input[name=tag_name'+value+']'), {
               whitelist : getTagsAutoComplete(),
               delimiters : "#",
               editTags : false,
            }).on('add', addTagData).on('remove', removeTagData);
    });

});
</script>
<?php }?>
