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
                                    <?php 
                                    $optionData = array();
                                    foreach($productOptions as $key=>$data){
                                        $optionData[$key]['id'] = $data['option_id'];
                                        $optionData[$key]['value'] = $data['option_name'] .'('.$data['option_identifier'].')';
                                    }
                                    ?>
                                    <input type="text" name="option_groups" value='<?php echo json_encode($optionData); ?>'>
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
            <?php 
                $tagData = array();
                foreach($productTags as $key=>$data){
                    $tagData[$key]['id'] = $data['tag_id'];
                    $tagData[$key]['value'] = $data['tag_identifier'];
                }
            ?>
            <div class="col-md-4"> 
                <h3 class="form__heading"><?php echo Labels::getLabel('LBL_Tags', $adminLangId); ?></h3>                              
                <div class="row">
                     <div class="col-md-12">
                         <div class="field-set">
                             <div class="caption-wraper"><label class="field_label"><?php echo Labels::getLabel('LBL_Product_Tags', $adminLangId); ?></label></div>
                             <div class="field-wraper">
                                 <div class="field_cover">
                                    <input class="tag_name" type="text" name="tag_name" id="get-tags"  value='<?php echo json_encode($tagData); ?>'> 
                                 </div> 
                             </div>
                         </div>
                     </div>
                 </div> 
            </div> 
        </div>  
        <div class="row">
             <div class="col-md-6">
                 <div class="field-set">
                     <div class="caption-wraper"><label class="field_label"></label></div>
                     <div class="field-wraper">
                         <div class="field_cover">
                            <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                            <button class="btn btn-primary" onClick= "productShipping(<?php echo $productId; ?>)"><?php echo Labels::getLabel('LBL_Next', $adminLangId); ?></button>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
    </div>
</div>

<script type="text/javascript">

$("document").ready(function() {   
    var product_id = '<?php echo $productId; ?>';
    
    upcListing(product_id); 
    
    addTagData = function(e){
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

    removeTagData = function(e){ 
        var tag_id = e.detail.tag.id;
        fcom.updateWithAjax(fcom.makeUrl('Products', 'removeProductTag'), 'product_id='+product_id+'&tag_id='+tag_id, function(t) {
        });
    }
    
    getTagsAutoComplete = function(){
        var list = [];
        fcom.ajax(fcom.makeUrl('Tags', 'autoComplete'), '', function(t) {          
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
    
    tagify = new Tagify(document.querySelector('input[name=tag_name]'), {
           whitelist : getTagsAutoComplete(),
           delimiters : "#",
           editTags : false,
        }).on('add', addTagData).on('remove', removeTagData); 

        
            
    addOption = function(e){ 
        var option_id = e.detail.tag.id; 
        if(option_id == ''){
            var tagifyId = e.detail.tag.__tagifyId;
             $('[__tagifyid='+tagifyId+']').remove();
        }else{
            fcom.updateWithAjax(fcom.makeUrl('Products', 'updateProductOption'), 'product_id='+product_id+'&option_id='+option_id, function(t) {
                upcListing(product_id);
            });
        }        
    }

    removeOption = function(e){ 
        var option_id = e.detail.tag.id; 
        fcom.updateWithAjax(fcom.makeUrl('Products', 'removeProductOption'), 'product_id='+product_id+'&option_id='+option_id, function(t) {
            upcListing(product_id);
        });
    }
    
    getOptionsAutoComplete = function(){
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
        return listOptions;
    };
     
    
    tagifyOption = new Tagify(document.querySelector('input[name=option_groups]'), {
          // enforceWhitelist : true,
           whitelist : getOptionsAutoComplete(),
           delimiters : "#",
           editTags : false, 
        }).on('add', addOption).on('remove', removeOption);         

});
</script>