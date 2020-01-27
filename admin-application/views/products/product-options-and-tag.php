<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-8"> 
                <h3 class="form__heading">Option Groups</h3>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="field-set">
                             <div class="caption-wraper"><label class="field_label">Add Associated Product Option Groups</label></div>
                             <div class="field-wraper">
                                 <div class="field_cover"><input type="text" name="tags" class="js-tagify" value="tagify"></div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-12 mb-4">
                         <table width="100%" class="table table-bordered">
                             <thead>
                                 <tr>
                                     <th width="70%">Variants</th>
                                     <th>EAN/UPC CODE</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <tr>
                                     <td>Blue / Style1</td>
                                     <td><input type="text" value="EAN/UPC CODE"></td>
                                 </tr>

                             </tbody>
                         </table>
                     </div>
                 </div>
            </div>
            <div class="col-md-4">
                <h3 class="form__heading"><?php echo Labels::getLabel('LBL_Tags', $adminLangId); ?></h3>              
                <input class="tag_name" type="text" name="tag_name_<?php echo $siteDefaultLangId; ?>" id="<?php echo $siteDefaultLangId; ?>" >                
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
        </div>              
    </div>
</div>

<script>
var product_id = '<?php echo $productId; ?>';

intitalizeTagify = function(tagInput){
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
                "value" : ans[i].tag_identifier,
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

RemoveTag = function(e){ console.log(e.detail);
    var tag_id = e.detail.tag.id;
    fcom.updateWithAjax(fcom.makeUrl('Products', 'removeProductTag'), 'product_id='+product_id+'&tag_id='+tag_id, function(t) {
    });
}


tagInputDefault = document.querySelector('input[name=tag_name_<?php echo $siteDefaultLangId; ?>]'),
intitalizeTagify(tagInputDefault);
<?php foreach($otherLanguages as $langId=>$data) {  ?>
tag_name_<?php echo $langId; ?> = document.querySelector('input[name=tag_name_<?php echo $langId; ?>]'),
intitalizeTagify(tag_name_<?php echo $langId; ?>);
<?php } ?>


</script>