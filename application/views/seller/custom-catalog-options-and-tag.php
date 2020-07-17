<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<div class="tabs_data">
    <div class="tabs_body">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-set">
                            <div class="caption-wraper"><label class="field_label"><?php echo Labels::getLabel('LBL_Add_Associated_Product_Option_Groups', $siteLangId); ?></label></div>
                            <div class="field-wraper">
                                <div class="field_cover">
                                    <?php
                                    $optionData = array();
                                    foreach ($productOptions as $key => $data) {
                                        $optionData[$key]['id'] = $data['option_id'];
                                        $optionData[$key]['value'] = $data['option_name'] . '(' . $data['option_identifier'] . ')';
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
            foreach ($productTags as $key => $data) {
                $tagData[$key]['id'] = $data['tag_id'];
                $tagData[$key]['value'] = $data['tag_identifier'];
            }
            ?>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-set">
                            <div class="caption-wraper"><label class="field_label"><?php echo Labels::getLabel('LBL_Product_Tags', $siteLangId); ?></label></div>
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
    </div>
    <div class="row tabs_footer">
        <div class="col-6">
            <div class="field-set">
                <div class="caption-wraper"><label class="field_label"></label></div>
                <div class="field-wraper">
                    <div class="field_cover">
                        <input type="button" class="btn btn-outline-primary" onClick= "productAttributeAndSpecificationsFrm(<?php echo $preqId; ?>)" value="<?php echo Labels::getLabel('LBL_Back', $siteLangId); ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 text-right">
            <div class="field-set">
                <div class="caption-wraper"><label class="field_label"></label></div>
                <div class="field-wraper">
                    <div class="field_cover">
                        <input type="hidden" name="preq_id" value="<?php echo $preqId; ?>">
                        <input type="button" class="btn btn-primary" onClick= "<?php if ($productType == Product::PRODUCT_TYPE_DIGITAL) { ?> productMedia(<?php echo $productId; ?>) <?php } else { ?> productShipping(<?php echo $preqId; ?>) <?php } ?>" value="<?php echo Labels::getLabel('LBL_Save_And_Next', $siteLangId); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<script type="text/javascript">

    $("document").ready(function () {
        var preq_id = '<?php echo $preqId; ?>';

       upcListing(preq_id);

        addTagData = function (e) {
            var tag_id = e.detail.tag.id;
            var tag_name = e.detail.tag.title;
            if (tag_id == '') {
                var data = 'tag_id=0&tag_identifier=' + tag_name
                fcom.updateWithAjax(fcom.makeUrl('Seller', 'setupTag'), data, function (t) {
                    var dataLang = 'tag_id=' + t.tagId + '&tag_name=' + tag_name + '&lang_id=0';
                    fcom.updateWithAjax(fcom.makeUrl('Seller', 'tagLangSetup'), dataLang, function (t2) {
                        fcom.updateWithAjax(fcom.makeUrl('Seller', 'updateCustomCatalogTag'), 'preq_id=' + preq_id + '&tag_id=' + t.tagId, function (t3) {
                            var tagifyId = e.detail.tag.__tagifyId;
                            $('[__tagifyid=' + tagifyId + ']').attr('id', t.tagId);
                        });
                    });
                });
            } else {
                fcom.updateWithAjax(fcom.makeUrl('Seller', 'updateCustomCatalogTag'), 'preq_id=' + preq_id + '&tag_id=' + tag_id, function (t) { });
            }
        }

        removeTagData = function (e) {
            var tag_id = e.detail.tag.id;
            removeProductTag(preq_id, tag_id);
        }

        getTagsAutoComplete = function () {
            var list = [];
            fcom.ajax(fcom.makeUrl('Seller', 'tagsAutoComplete'), '', function (t) {
                var ans = $.parseJSON(t);
                for (i = 0; i < ans.length; i++) {
                    list.push({
                        "id": ans[i].id,
                        "value": ans[i].tag_identifier,
                    });
                }
            });
            return list;
        }

        tagify = new Tagify(document.querySelector('input[name=tag_name]'), {
            whitelist: getTagsAutoComplete(),
            delimiters: "#",
            editTags: false,
        }).on('add', addTagData).on('remove', removeTagData);



        addOption = function (e) {
            var option_id = e.detail.tag.id;
            if (option_id == '') {
                var tagifyId = e.detail.tag.__tagifyId;
                $('[__tagifyid=' + tagifyId + ']').remove();
            } else {
                updateProductOption(preq_id, option_id, e);
            }
        }

        removeOption = function (e) {
            var option_id = e.detail.tag.id;
            removeProductOption(preq_id, option_id);
        }

        getOptionsAutoComplete = function () {
            var listOptions = [];
            fcom.ajax(fcom.makeUrl('Seller', 'autoCompleteOptions'), '', function (t) {
                var ans = $.parseJSON(t);
                for (i = 0; i < ans.length; i++) {
                    listOptions.push({
                        "id": ans[i].id,
                        "value": ans[i].name + '(' + ans[i].option_identifier + ')',
                    });
                }
            });
            return listOptions;
        };


        tagifyOption = new Tagify(document.querySelector('input[name=option_groups]'), {
            whitelist: getOptionsAutoComplete(),
            delimiters: "#",
            editTags: false,
        }).on('add', addOption).on('remove', removeOption);

    });
</script>
