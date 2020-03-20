<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$productFrm->setFormTagAttribute('class', 'form form--horizontal');
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

$btnBackFld = $productFrm->getField('btn_back');
$btnBackFld->developerTags['col'] = 6;
$btnBackFld->setFieldTagAttribute('onClick','productOptionsAndTag('.$preqId.');');
$btnBackFld->value = Labels::getLabel('LBL_Back', $siteLangId);

$btnSubmitFld = $productFrm->getField('btn_submit');
$btnSubmitFld->developerTags['col'] = 6;
$btnSubmitFld->setWrapperAttribute('class','text-right');
?>
<div class="row justify-content-center">
     <div class="col-md-12">
         <?php echo $productFrm->getFormHtml(); ?>
     </div>
</div>

<script type="text/javascript">
/* $(document).ready(function(){
    $('input[name=\'shipping_country\']').autocomplete({
        'classes': {
            "ui-autocomplete": "custom-ui-autocomplete"
        },
        'source': function(request, response) {
            $.ajax({
                url: fcom.makeUrl('Seller', 'countries_autocomplete'),
                data: {keyword: request['term'],fIsAjax:1},
                dataType: 'json',
                type: 'post',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['name'] ,
                            value: item['name'],
                            id: item['id']
                            };
                    }));
                },
            });
        },
        'select': function(event, ui) {
                $('input[name=\'ps_from_country_id\']').val(ui.item.id);
        }

    });

    $('input[name=\'shipping_country\']').keyup(function(){
        $('input[name=\'ps_from_country_id\']').val('');
    });
}); */
</script>
