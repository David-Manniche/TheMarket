<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-8"> 
                <h3 class="form__heading"><?php echo Labels::getLabel('LBL_Option_Groups', $adminLangId); ?></h3>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="field-set">
                             <div class="caption-wraper"><label class="field_label"><?php echo Labels::getLabel('LBL_Add_Associated_Product_Option_Groups', $adminLangId); ?></label></div>
                             <div class="field-wraper">
                                 <div class="field_cover">
                                    <input type="text" name="option_groups" value='[{"id":"45", "value":"Color(Batman)"}]'>
                                 </div> 
                             </div>
                         </div>
                     </div>
                 </div> 
                 <div class="row">
                     <div class="col-md-12 mb-4" id="upc-listing">
                         
                     </div>
                 </div>
            </div>
            
            <div class="col-md-4">
                <h3 class="form__heading"><?php echo Labels::getLabel('LBL_Tags', $adminLangId); ?></h3>              
                <input class="tag_name" type="text" name="tag_name" id="get-tags"  value=''>                        
            </div> 
            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
        </div>              
    </div>
</div>

<script type="text/javascript">

$("document").ready(function() {   
    var product_id = '<?php echo $productId; ?>';
    
    upcListing(product_id); 
    
    AddTag = function(e){    
        var tag_id = e.detail.tag.id; 
        var tag_name = e.detail.tag.title;   
        if(tag_id == ''){
            var data = 'tag_id=0&tag_identifier='+tag_name
            fcom.updateWithAjax(fcom.makeUrl('Tags', 'setup'), data, function(t) {
                var dataLang = 'tag_id='+t.tagId+'&tag_name='+tag_name+'&lang_id=0';
                fcom.updateWithAjax(fcom.makeUrl('Tags', 'langSetup'), dataLang, function(t2) { 
                    fcom.updateWithAjax(fcom.makeUrl('Products', 'updateProductTag'), 'product_id='+product_id+'&tag_id='+t.tagId, function(t3) { 
                         var tagifyId = e.detail.tag.__tagifyId;
                         $('[__tagifyid='+tagifyId+']').attr('id', t.tagId);
                     });
                });
            });
        }else{
            fcom.updateWithAjax(fcom.makeUrl('Products', 'updateProductTag'), 'product_id='+product_id+'&tag_id='+tag_id, function(t) { });
        }        
    }

    RemoveTag = function(e){ 
        var tag_id = e.detail.tag.id;
        fcom.updateWithAjax(fcom.makeUrl('Products', 'removeProductTag'), 'product_id='+product_id+'&tag_id='+tag_id, function(t) {
        });
    }
    
    var list = [];
    fcom.ajax(fcom.makeUrl('Tags', 'autoComplete'), '', function(t) {          
        var ans = $.parseJSON(t);
        for (i = 0; i < ans.length; i++) {            
            list.push({
                "id" : ans[i].id,
                "value" : ans[i].name+'('+ans[i].tag_identifier+')', 
            });
        }           
    });
    
    fcom.ajax(fcom.makeUrl('Products', 'productTags', [product_id]), '', function(t) {
        var ans = $.parseJSON(t);        
        var tagList = ans.productTags; 
        var tagName = '';
        for (i = 0; i < tagList.length; i++) {              
            //tagName += tagList[i]['tag_identifier']+"#"; 
            $('#get-tags').val(['id']);
           // $('#get-tags').val({'id':'2545', 'value':'testfg'}); 
        }
        //$('#get-tags').val(tagName); 
       
        tagify = new Tagify(document.querySelector('input[name=tag_name]'), {
           whitelist : list,
           delimiters : "#",
           editTags : false,
        }).on('add', AddTag).on('remove', RemoveTag);    
    });
        
    
   
    
    AddOption = function(e){ 
        var option_id = e.detail.tag.id; 
        fcom.updateWithAjax(fcom.makeUrl('Products', 'updateProductOption'), 'product_id='+product_id+'&option_id='+option_id, function(t) {
            upcListing();
        });
    }

    RemoveOption = function(e){ 
        var option_id = e.detail.tag.id; 
        fcom.updateWithAjax(fcom.makeUrl('Products', 'removeProductOption'), 'product_id='+product_id+'&option_id='+option_id, function(t) {
            upcListing();
        });
    }
    
    var listOptions = [];
    fcom.ajax(fcom.makeUrl('Options', 'autoComplete'), '', function(t) {           
        var ans = $.parseJSON(t);
        for (i = 0; i < ans.length; i++) {            
            listOptions.push({
                "id" : ans[i].id,
                "value" : ans[i].name+'('+ans[i].option_identifier+')',
            });
        }           
    }); 
    
    tagifyOption = new Tagify(document.querySelector('input[name=option_groups]'), {
           //enforceWhitelist : true,
           whitelist : listOptions,
           delimiters : "#",
           editTags : false, 
        }).on('add', AddOption).on('remove', RemoveOption); 
        
    updateUpc = function(optionValueId){
        var code = $("input[name='code"+optionValueId+"']").val();
        var data = {'code':code,'optionValueId':optionValueId};
        fcom.updateWithAjax(fcom.makeUrl('products', 'updateUpc',[product_id]), data, function(t) {
        });
    };

});
</script>