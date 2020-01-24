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
            productAttributeAndSpecificationsFrm(t.productId);
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
    
    displayProdInitialTab = function(){
        $(".tabs_panel").hide();
        $(".tabs_nav  > li > a").removeClass('active');
        $("#tabs_001").show();                    
        $("a[rel='tabs_001']").addClass('active');
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

