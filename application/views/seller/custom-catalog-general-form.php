	<?php require_once(CONF_THEME_PATH.'_partial/seller/customCatalogProductNavigationLinks.php'); ?>  
	<div class="box__body">		
			<div class="tabs tabs--small tabs--offset tabs--scroll clearfix">
				<?php require_once(CONF_THEME_PATH.'seller/seller-custom-catalog-product-top.php');?>
			</div>
			<div class="tabs__content form">		
				<div class="form__content">
					<div class="col-md-12">
						<div class="container container--fluid">
							<div class="tabs--inline tabs--scroll clearfix">
								<ul>
									<li class="is-active"><a onClick="customCatalogProductForm(<?php echo $preqId;?>,<?php echo $preqCatId;?>)" href="javascript:void(0);"><?php echo Labels::getLabel('LBL_Basic', $siteLangId );?></a></li>
									<li class="<?php echo (!$preqId) ? 'fat-inactive' : ''; ?>"><a  <?php echo ($preqId) ? "onclick='customCatalogSellerProductForm( ".$preqId." );'" : ""; ?> href="javascript:void(0);"><?php echo Labels::getLabel('LBL_Inventory/Info', $siteLangId );?></a></li>
									<li class="<?php echo (!$preqId) ? 'fat-inactive' : ''; ?>"><a  <?php echo ($preqId) ? "onclick='customCatalogSpecifications( ".$preqId." );'" : ""; ?> href="javascript:void(0);"><?php echo Labels::getLabel('LBL_Specifications', $siteLangId );?></a></li>
									<?php foreach($languages as $langId=>$langName){?>
									<li class="<?php echo (!$preqId) ? 'fat-inactive' : ''; ?>"><a href="javascript:void(0);" <?php echo ($preqId) ? "onclick='customCatalogProductLangForm( ".$preqId.",".$langId." );'" : ""; ?>><?php echo $langName;?></a></li>				
									<?php } ?>
									<?php if(!empty($productOptions)){?>
									<li class="<?php echo (!$preqId) ? 'fat-inactive' : ''; ?>"><a  <?php echo ($preqId) ? "onclick='customEanUpcForm( ".$preqId." );'" : ""; ?> href="javascript:void(0);"><?php echo Labels::getLabel('LBL_EAN/UPC_setup', $siteLangId );?></a></li>
									<?php } ?>
									<li class="<?php echo (!$preqId) ? 'fat-inactive' : ''; ?>"><a href="javascript:void(0);" <?php echo ($preqId) ? "onclick='customCatalogProductImages( ".$preqId." );'" : ""; ?>><?php echo Labels::getLabel('Lbl_Product_Images',$siteLangId);?></a></li>
								</ul>	
							</div>
						</div>
						<div class="form__subcontent">
						<?php 
						$customProductFrm->setFormTagAttribute('class', 'form form--horizontal');
						$customProductFrm->setFormTagAttribute('onSubmit', 'setupCustomProduct(this); return(false);');
						
						$shippingCountryFld = $customProductFrm->getField('shipping_country');	
						$shippingCountryFld->setWrapperAttribute( 'class' , 'not-digital-js');
						
						$shipFreeFld = $customProductFrm->getField('ps_free');	
						$shipFreeFld->setWrapperAttribute( 'class' , 'not-digital-js');
						
						if (FatApp::getConfig("CONF_PRODUCT_DIMENSIONS_ENABLE", FatUtility::VAR_INT, 1 ))
						{
							$lengthFld = $customProductFrm->getField('product_length');	
							$lengthFld->setWrapperAttribute( 'class' , 'product_length_fld');	
							//$lengthFld->htmlAfterField = Labels::getLabel('LBL_Note:_Used_for_Shipping_Calculation.',$adminLangId);
								
							$widthFld = $customProductFrm->getField('product_width');	
							$widthFld->setWrapperAttribute( 'class' , 'product_width_fld');
							//$widthFld->htmlAfterField = Labels::getLabel('LBL_Note:_Used_for_Shipping_Calculation.',$adminLangId) ;
								
							$heightFld = $customProductFrm->getField('product_height');	
							$heightFld->setWrapperAttribute( 'class' , 'product_height_fld');
							//$heightFld->htmlAfterField = Labels::getLabel('LBL_Note:_Used_for_Shipping_Calculation.',$adminLangId);

							$dimensionUnitFld = $customProductFrm->getField('product_dimension_unit');	
							$dimensionUnitFld->setWrapperAttribute( 'class' , 'product_dimension_unit_fld');

							$weightFld = $customProductFrm->getField('product_weight');	
							$weightFld->setWrapperAttribute( 'class' , 'product_weight_fld');

							$weightUnitFld = $customProductFrm->getField('product_weight_unit');	
							$weightUnitFld->setWrapperAttribute( 'class' , 'product_weight_unit_fld');
						}
						$productCodEnabledFld = $customProductFrm->getField('product_cod_enabled');
						$productCodEnabledFld->setWrapperAttribute( 'class' , 'product_cod_enabled_fld');
						
						$productShippedByMeFld = $customProductFrm->getField('product_shipped_by_me');
						$productShippedByMeFld->setWrapperAttribute( 'class' , 'product_shipped_by_me_fld');
						
						/* $productEanUpcFld = $customProductFrm->getField('product_upc');
						$productEanUpcFld->addFieldTagAttribute( 'onBlur', 'validateEanUpcCode(this.value)'); */
						/* $lengthFld = $customProductFrm->getField('product_length')->fieldWrapper = array('<div class="s">','</div>');
						$widthFld = $customProductFrm->getField('product_width')->fieldWrapper = array('<div class="f">','</div>');
						$heightFld = $customProductFrm->getField('product_height')->fieldWrapper = array('<div class="a">','</div>');
						
						$customProductFrm->getField('product_weight')->fieldWrapper = array('<div class="c">','</div>');
						$customProductFrm->getField('product_weight_unit')->fieldWrapper = array('<div class="g">','</div>'); */
						
						//$customProductFrm->getField('option_name')->setFieldTagAttribute('class','mini');
						echo $customProductFrm->getFormHtml();
						?>  
					</div>
				</div>
			</div>
		</div>
</div>

<script  type="text/javascript">
	var productOptions =[];
	var productId=<?php echo $preqId;?>;
	var prodTypeDigital = <?php echo Product::PRODUCT_TYPE_DIGITAL;?>;
	var dv =$("#listing");
	
	var PRODUCT_TYPE_PHYSICAL = <?php echo Product::PRODUCT_TYPE_PHYSICAL; ?>;
	var PRODUCT_TYPE_DIGITAL = <?php echo Product::PRODUCT_TYPE_DIGITAL; ?>;
	$(document).ready(function(){
		addShippingTab(productId);
		$("select[name='product_type']").change(function(){
			if( $(this).val() == PRODUCT_TYPE_PHYSICAL ){
				$(".product_length_fld").show();
				$(".product_width_fld").show();
				$(".product_height_fld").show();
				$(".product_dimension_unit_fld").show();
				$(".product_weight_fld").show();
				$(".product_weight_unit_fld").show();
				$(".product_cod_enabled_fld").show();
				$(".product_shipped_by_me_fld").show();
				$('.not-digital-js').show();
				$('#tab_shipping').show();
				addShippingTab(productId);
			}
			
			if( $(this).val() == PRODUCT_TYPE_DIGITAL ){
				$(".product_length_fld").hide();
				$(".product_width_fld").hide();
				$(".product_height_fld").hide();
				$(".product_dimension_unit_fld").hide();
				$(".product_weight_fld").hide();
				$(".product_weight_unit_fld").hide();
				$(".product_cod_enabled_fld").hide();
				$(".product_shipped_by_me_fld").hide();
				$('.not-digital-js').hide();
				$('#tab_shipping').hide();
			}
			
		});
		$("select[name='product_type']").trigger('change');
	
		$("select[name='product_shipped_by_me']").change(function(){
			if( $(this).val() == 1 && $("select[name='product_type']").val() == PRODUCT_TYPE_PHYSICAL){
				$('.not-digital-js').show();
				$('#tab_shipping').show();
			}else{
				if( $(this).val() == 0 ){
				$('.not-digital-js').hide();
				$('#tab_shipping').hide();
				}
			}
		});
		$("select[name='product_shipped_by_me']").trigger('change');
		
		$('input[name=\'brand_name\']').autocomplete({
			'source': function(request, response) {
				/* fcom.ajax(fcom.makeUrl('brands', 'autoComplete'), {keyword:encodeURIComponent(request)}, function(json) {
					response($.map(json, function(item) {
							return { label: item['name'],	value: item['id']	};
						}));
				}); */
				$.ajax({
					url: fcom.makeUrl('brands', 'autoComplete'),
					data: {keyword: request,fIsAjax:1},
					dataType: 'json',
					type: 'post',
					success: function(json) {
						response($.map(json, function(item) {
							return { label: item['name'],	value: item['id']	};
						}));
					},
				});
			},
			'select': function(item) {
				$('input[name=\'brand_name\']').val(item['label']);
				$('input[name=\'product_brand_id\']').val(item['value']);
			}
		});
	
		$('input[name=\'brand_name\']').keyup(function(){
			$('input[name=\'product_brand_id\']').val('');
		});
	
	$('input[name=\'option_name\']').autocomplete({
		'source': function(request, response) {
		
			$.ajax({
				url: fcom.makeUrl('seller', 'autoCompleteOptions'),
				data: {keyword: request,fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
					
						return { 
							label: item['name'] + ' (' + item['option_identifier'] + ')',
							value: item['id']
							};
					}));
				},
			});
		},
		'select': function(item) {	
			$('input[name=\'option_name\']').val('');
			$('#product-option' + item['value']).remove();
			$('#product-option-js').append('<li id="product-option' + item['value'] + '"><i class="remove_option-js remove_param fa fa-trash"></i> ' +item['label'] + '<input type="hidden" name="product_option[]" value="' + item['value'] + '"  /></li>');			
		}
	});
	$('#product-option-js').delegate('.remove_option-js', 'click', function() {		
		$(this).parent().remove();
	});
		
	var options = new Array();
	<?php  if(!empty($productReqRow['product_option'])){
	foreach($productReqRow['product_option'] as $key => $val){ ?>
		options.push('<?php echo $val; ?>');
	<?php } } ?>
	var data = {'options':options};
	fcom.ajax(fcom.makeUrl('Seller', 'loadCustomProductOptionss'), data, function(t) {
		$('#product-option-js').html(t);
	});	
	
	$('input[name=\'tag_name\']').autocomplete({
			'source': function(request, response) {
			
				$.ajax({
					url: fcom.makeUrl('seller', 'tagsAutoComplete'),
					data: {keyword: request,fIsAjax:1},
					dataType: 'json',
					type: 'post',
					success: function(json) {
						response($.map(json, function(item) {
						
							return { 
								label: item['name'] + ' (' + item['tag_identifier'] + ')',
								value: item['id']
								};
						}));
					},
				});
			},
			'select': function(item) {					
				$('input[name=\'tag_name\']').val('');
				$('#product-tag' + item['value']).remove();
				$('#product-tag-js').append('<li id="product-tag' + item['value'] + '"><i class="remove_tag-js remove_param fa fa-trash"></i> ' +item['label'] + '<input type="hidden" name="product_tags[]" value="' + item['value'] + '" /></li>');					
			}
		});
			
		$('#product-tag-js').delegate('.remove_tag-js', 'click', function() {		
			$(this).parent().remove();
		});
		
		var tags = new Array();
		<?php if(!empty($productReqRow['product_tags'])){
		foreach($productReqRow['product_tags'] as $key => $val){ ?>
			tags.push('<?php echo $val; ?>');
		<?php } } ?>
		var data = {'tags':tags};
		fcom.ajax(fcom.makeUrl('Seller', 'loadCustomProductTags'), data, function(t) {
			$('#product-tag-js').html(t);
		});	
	
	
		/* Shipping Information */
		$('input[name=\'shipping_country\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: fcom.makeUrl('seller', 'countries_autocomplete'),
					data: {keyword: request,fIsAjax:1},
					dataType: 'json',
					type: 'post',
					success: function(json) {
						response($.map(json, function(item) {
							return { 
								label: item['name'] ,
								value: item['id']
							};
						}));
					},
				});
			},
			'select': function(item) {
				$('input[name=\'shipping_country\']').val(item.label);
				$('input[name=\'ps_from_country_id\']').val(item.value);
			}
		});

		$('input[name=\'shipping_country\']').keyup(function(){
			$('input[name=\'ps_from_country_id\']').val('');
		});
	});
</script>
	</div>