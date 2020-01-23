$(document).ready(function(){
    productInitialSetUpFrm();
});

(function() {
    
    productInitialSetUpFrm = function(){
		var data = '';
		fcom.ajax(fcom.makeUrl('Products','productInitialSetUpFrm'),data,function(res){
			$("#tabs_001").html(res);
		});
	};
    
    setUpProduct = function(frm) {
        if (!$(frm).validate()) return;    
        var productId = $("input[name='product_id']").val();
        var data = fcom.frmData(frm)+'&product_id='+productId;
        fcom.updateWithAjax(fcom.makeUrl('Products', 'setUpProduct'), data, function(t) {
            $("input[name='product_id']").val(t.productId);
            productAttributeAndSpecificationsFrm();
        });
    };
    
    productAttributeAndSpecificationsFrm = function(){ 
        var productId = $("input[name='product_id']").val();
        var data = 'product_id='+productId;
		fcom.ajax(fcom.makeUrl('Products','productAttributeAndSpecificationsFrm'),data,function(res){
			$("#tabs_002").html(res);
            $(".tabs_panel").hide();
            $("#tabs_002").show();        
            $(".tabs_nav  > li > a").removeClass('active');
            $("a[rel='tabs_002']").addClass('active');
		});        
    }
    
    addSpecification = function(langId){
        var productId = $("input[name='product_id']").val();
        var prodspec_name = $("input[name='prodspec_name["+langId+"]']").val();
        var prodspec_value = $("input[name='prodspec_value["+langId+"]']").val();
        if(prodspec_name == '' || prodspec_value == ''){
            return false;
        }        
        var data = 'productId='+productId+'&langId='+langId+'&prodspec_name='+prodspec_name+'&prodspec_value='+prodspec_value;
        fcom.updateWithAjax(fcom.makeUrl('Products', 'setUpProductSpecifications'), data, function(t) {
            
        });
    }
 
})();

