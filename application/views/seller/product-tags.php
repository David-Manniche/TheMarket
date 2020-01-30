<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frmSearch->setFormTagAttribute('class', 'form');
$frmSearch->setFormTagAttribute('onsubmit', 'searchCatalogProducts(this); return(false);');
$frmSearch->developerTags['colClassPrefix'] = 'col-md-';
$frmSearch->developerTags['fld_default_col'] = 4;

$keywordFld = $frmSearch->getField('keyword');
$keywordFld->setWrapperAttribute('class', 'col-lg-4');
$keywordFld->addFieldTagAttribute('placeholder', Labels::getLabel('LBL_Search_by_keyword', $siteLangId));
$keywordFld->developerTags['col'] = 4;
$keywordFld->developerTags['noCaptionTag'] = true;
?>
<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header  row justify-content-between mb-3">
            <div class="col-md-auto">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Manage_Tags', $siteLangId); ?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="cards">
                        <div class="cards-content p-4">
                            <div>
                                <?php echo $frmSearch->getFormTag(); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="field-wraper">
                                                <div class="field_cover">
                                                    <?php echo $frmSearch->getFieldHTML('keyword');?>
                                                    <div class='dvFocus-js'></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <?php echo $frmSearch->getExternalJS();?>
                            </div>
                            <div id="listing">
                                <?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="cards">
                        <div class="cards-content p-4">
                            <div id="dvForm"></div>
                            <div id="dvAlert">
                                <div class="cards-message" role="alert">
                                    <div class="cards-message-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                    <div class="cards-message-text"><?php echo Labels::getLabel('Select_a_product_to_add_/_edit_Tags', $siteLangId); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
$("document").ready(function() {
    var product_id = '<?php echo $productId; ?>';
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
});
</script>
