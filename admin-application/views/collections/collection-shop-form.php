<?php
defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frm->setFormTagAttribute('class', 'web_form form_horizontal');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$fld = $frm->getField('shops');
$fld->setWrapperAttribute('class', 'ui-front');
?>
<section class="section">
	<div class="sectionhead">
		<h1><?php echo Labels::getLabel('LBL_Collection_Shop_Setup',$adminLangId);?></h1>
	</div>
	<div class="sectionbody space">
		<div class="row">	
			<div class="col-sm-12">
				<div class="tabs_nav_container responsive flat">
					<div class="tabs_panel_wrap" style="min-height: 500px;">
						<div class="tabs_panel">
							<?php echo $frm->getFormHtml(); ?>
							<div id="shops_list" class="col-xs-10"></div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
$("document").ready(function(){
	$('input[name=\'shops\']').autocomplete({
        'classes': {
            "ui-autocomplete": "custom-ui-autocomplete"
        },
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Shops', 'autocomplete'),
				data: {keyword: request['term'],fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['name'], value: item['name'], id: item['id'] };
					}));
				},
			});
		},
		select: function( event, ul ) {
			updateCollectionShops(<?php echo $collection_id; ?>, ul.item.id );
            $('input[name=\'shops\']').val('');
            return false;
		}
	});
});
</script>