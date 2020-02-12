<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$productFrm->setFormTagAttribute('class', 'web_form mt-5');
$productFrm->setFormTagAttribute('onsubmit', 'setUpProductShipping(this); return(false);');
$productFrm->developerTags['colClassPrefix'] = 'col-md-';
$productFrm->developerTags['fld_default_col'] = 12;

$diomesionFld = $productFrm->getField('product_dimension_unit');
$diomesionFld->developerTags['col'] = 6;

$lenFld = $productFrm->getField('product_length');
$lenFld->developerTags['col'] = 6;

$widthFld = $productFrm->getField('product_width');
$widthFld->developerTags['col'] = 6;

$heightFld = $productFrm->getField('product_height');
$heightFld->developerTags['col'] = 6;

$weightUnitFld = $productFrm->getField('product_weight_unit');
$weightUnitFld->developerTags['col'] = 6;

$weightFld = $productFrm->getField('product_weight');
$weightFld->developerTags['col'] = 6;

$btnbackFld = $productFrm->getField('btn_back');
$btnbackFld->developerTags['col'] = 6;

$btnSubmitFld = $productFrm->getField('btn_submit');
$btnSubmitFld->developerTags['col'] = 6;
$btnSubmitFld->addWrapperAttribute('class', 'text-right');

?>
<div class="row justify-content-center">
     <div class="col-md-12">
         <?php echo $productFrm->getFormHtml(); ?>
     </div>
</div>
 
<script type="text/javascript">
$(document).ready(function(){
    $('input[name=\'shipping_country\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: fcom.makeUrl('products', 'countries_autocomplete'),
                data: {keyword: request['term'],fIsAjax:1},
                dataType: 'json',
                type: 'post',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['name'],
                            value: item['name'],
                            id: item['id']
                            };
                    }));
                },
            });
        },
        select: function(item) {
            $('input[name=\'ps_from_country_id\']').val(ui.item.id);
        }
    });

    $('input[name=\'shipping_country\']').keyup(function(){
        $('input[name=\'ps_from_country_id\']').val('');
    });
});
</script>