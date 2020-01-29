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
                <input class="tag_name" type="text" name="tag_name_<?php echo $siteDefaultLangId; ?>" id="<?php echo $siteDefaultLangId; ?>" value='[{"id":"789789789", "value":"color"}]'>                
                <?php 
                 if(!empty($otherLanguages)){ 
                    foreach($otherLanguages as $langId=>$data) { 
                 ?>
                 <div class="accordians_container accordians_container-categories mt-5">
                     <div class="accordian_panel">
                         <span class="accordian_title accordianhead">
                         <?php echo $data." "; echo Labels::getLabel('LBL_Tags', $adminLangId); ?>
                         </span>
                         <div class="accordian_body accordiancontent" style="display:none;">
                            <input class="tag_name" type="text" name="tag_name_<?php echo $langId; ?>" id="<?php echo $langId; ?>" >
                         </div>
                     </div>
                 </div>
                 <?php } 
                 }
                 ?>         
            </div>
            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
        </div>              
    </div>
</div>

<script>
var product_id = '<?php echo $productId; ?>';

intitalizeTagifyForTags = function(tagInput){
    tagify = new Tagify(tagInput, {
       whitelist : getTagsList(),
       delimiters : "#",
       editTags : false
    }).on('add', AddTag).on('remove', RemoveTag);
}

getTagsList = function(){
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
    return list; 
}

AddTag = function(e){    
    var tag_id = e.detail.tag.id; 
    var tag_name = e.detail.tag.title;   
    if(tag_id == ''){
        var tagifyId = e.detail.tag.__tagifyId;
        var langId = $('[__tagifyid='+tagifyId+']').parent().siblings('.tag_name').attr('id');
        var data = 'tag_id=0&tag_identifier='+tag_name
        fcom.updateWithAjax(fcom.makeUrl('Tags', 'setup'), data, function(t) {
			var dataLang = 'tag_id='+t.tagId+'&tag_name='+tag_name+'&lang_id='+langId
            fcom.updateWithAjax(fcom.makeUrl('Tags', 'langSetup'), dataLang, function(t2) { 
                fcom.updateWithAjax(fcom.makeUrl('Products', 'updateProductTag'), 'product_id='+product_id+'&tag_id='+t.tagId, function(t3) { 
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

intitalizeTagifyForTags(document.querySelector('input[name=tag_name_<?php echo $siteDefaultLangId; ?>]'));

<?php foreach($otherLanguages as $langId=>$data) {  ?>
intitalizeTagifyForTags(document.querySelector('input[name=tag_name_<?php echo $langId; ?>]'));
<?php }  ?>


getOptionsList = function(){
    var list = [];
    fcom.ajax(fcom.makeUrl('Options', 'autoComplete'), '', function(t) {           
        var ans = $.parseJSON(t);
        for (i = 0; i < ans.length; i++) {            
            list.push({
                "id" : ans[i].id,
                "value" : ans[i].name+'('+ans[i].option_identifier+')',
            });
        }           
    }); 
    return list; 
}

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

tagify = new Tagify(document.querySelector('input[name=option_groups]'), {
       //enforceWhitelist : true,
       whitelist : getOptionsList(),
       delimiters : "#",
       editTags : false, 
    }).on('add', AddOption).on('remove', RemoveOption);  


upcListing = function (){
    fcom.ajax(fcom.makeUrl('products', 'upcListing', [product_id]), '', function(t) {
        $("#upc-listing").html(t);
    });
};

upcListing(); 

updateUpc = function(optionValueId){
    var code = $("input[name='code"+optionValueId+"']").val();
    var data = {'code':code,'optionValueId':optionValueId};
    fcom.updateWithAjax(fcom.makeUrl('products', 'updateUpc',[product_id]), data, function(t) {
    });
};



 
</script>