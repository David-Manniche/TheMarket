(function() {    
    productInitialSetUpFrm = function(productId){
		var data = '';
		fcom.ajax(fcom.makeUrl('Products','productInitialSetUpFrm',[productId]),data,function(res){
			$("#tabs_001").html(res);
		});
	};
    
    setUpProduct = function(frm) {
        if (!$(frm).validate()) return;    
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('Products', 'setUpProduct'), data, function(t) {
            productAttributeAndSpecificationsFrm(t.productId);
        });
    };
    
    productAttributeAndSpecificationsFrm = function(productId){ 
        var data = '';
		fcom.ajax(fcom.makeUrl('Products','productAttributeAndSpecificationsFrm', [productId]),data,function(res){
			$("#tabs_001").html('');
            $("#tabs_002").html(res);
            $(".tabs_panel").hide();
            $(".tabs_nav  > li > a").removeClass('active');
            $("#tabs_002").show();                    
            $("a[rel='tabs_002']").addClass('active');
		});        
    }
    
    setUpProductAttributes = function(frm) {
        if (!$(frm).validate()) return;    
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('Products', 'setUpProductAttributes'), data, function(t) {
            productOptionsAndTag(t.productId);
        });
    };
    
    prodSpecificationSection = function(langId, prodSpecId = 0){
        var productId = $("input[name='product_id']").val();
        var data = "langId="+langId+"&prodSpecId="+prodSpecId;
        fcom.ajax(fcom.makeUrl('Products', 'prodSpecificationSection', [productId]), data, function(res) {
            $(".specifications-form-"+langId).html(res);
        });
    }
    
    prodSpecificationsByLangId = function(langId){
        var productId = $("input[name='product_id']").val();
        var data = 'product_id='+productId+'&langId='+langId;
        fcom.ajax(fcom.makeUrl('Products', 'prodSpecificationsByLangId'), data, function(res) {
            $(".specifications-list-"+langId).html(res);
        });
    }
    
    saveSpecification = function(langId, prodSpecId){
        var productId = $("input[name='product_id']").val();        
        var prodspec_name = $("input[name='prodspec_name["+langId+"]']").val();
        var prodspec_value = $("input[name='prodspec_value["+langId+"]']").val();
        if(prodspec_name == '' || prodspec_value == ''){
            return false;
        }        
        var data = 'product_id='+productId+'&langId='+langId+'&prodSpecId='+prodSpecId+'&prodspec_name='+prodspec_name+'&prodspec_value='+prodspec_value;
        fcom.updateWithAjax(fcom.makeUrl('Products', 'setUpProductSpecifications'), data, function(t) {
            prodSpecificationsByLangId(langId);
            prodSpecificationSection(langId);
        });
    }
    
    deleteProdSpec = function(prodSpecId, langId){
        var agree = confirm("Do you want to delete record?");
        if( !agree ){ return false; }
        var data = 'prodSpecId='+prodSpecId;
        fcom.updateWithAjax(fcom.makeUrl('Products', 'deleteProdSpec'), data, function(t) {
            prodSpecificationsByLangId(langId);            
        });
    }
    
    displayOtherLangProdSpec = function(obj, langId){
        if($(obj).hasClass('active')){
            return false;
        }
        prodSpecificationSection(langId);
        prodSpecificationsByLangId(langId);
    }
    
    productOptionsAndTag = function(productId){
        var data = '';
		fcom.ajax(fcom.makeUrl('Products','productOptionsAndTag', [productId]),data,function(res){
			$("#tabs_002").html('');
            $("#tabs_003").html(res);
            $(".tabs_panel").hide();
            $(".tabs_nav  > li > a").removeClass('active');
            $("#tabs_003").show();                    
            $("a[rel='tabs_003']").addClass('active');
		});
    }
    
    displayProdInitialTab = function(){
        $(".tabs_panel").hide();
        $(".tabs_nav  > li > a").removeClass('active');
        $("#tabs_001").show();                    
        $("a[rel='tabs_001']").addClass('active');
    }
    
    
    upcListing = function (product_id){
        fcom.ajax(fcom.makeUrl('products', 'upcListing', [product_id]), '', function(t) {
            $("#upc-listing").html(t);
        });
    };
    
    updateUpc = function(productId, optionValueId){
        var code = $("input[name='code"+optionValueId+"']").val();
        var data = {'code':code,'optionValueId':optionValueId};
        fcom.updateWithAjax(fcom.makeUrl('products', 'updateUpc',[productId]), data, function(t) {
        });
    };
    
    productShipping = function(productId){
        var data = '';
		//fcom.ajax(fcom.makeUrl('Products','productShipping', [productId]),data,function(res){
			$("#tabs_003").html('');
            //$("#tabs_004").html(res);
            $(".tabs_panel").hide();
            $(".tabs_nav  > li > a").removeClass('active');
            $("#tabs_004").show();                    
            $("a[rel='tabs_004']").addClass('active');
		//});
    }

})();

$(document).on('click', '.tabs_001', function(){
    var productId = $("input[name='product_id']").val();
    productInitialSetUpFrm(productId);
});

$(document).on('click', '.tabs_002', function(){
    var productId = $("input[name='product_id']").val();
    if(productId > 0){
        productAttributeAndSpecificationsFrm(productId);
    }else{
        displayProdInitialTab();
    }        
});

$(document).on('click', '.tabs_003', function(){
    var productId = $("input[name='product_id']").val();
    if(productId > 0){
        productOptionsAndTag(productId);
    }else{
        displayProdInitialTab();
    }        
}); 

$(document).on('click', '.tabs_004', function(){
    var productId = $("input[name='product_id']").val();
    if(productId > 0){
        productShipping(productId);
    }else{
        displayProdInitialTab();
    }        
});


