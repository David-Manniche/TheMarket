<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$prodCatFrm->setFormTagAttribute('id', 'prodCate');
$prodCatFrm->developerTags['colClassPrefix'] = 'col-md-';
$prodCatFrm->developerTags['fld_default_col'] = 12;
$prodCatFrm->setFormTagAttribute('class', 'web_form');
$prodCatFrm->setFormTagAttribute('onsubmit', 'setupCategory(this); return(false);');
$identiFierFld = $prodCatFrm->getField('prodcat_identifier');
$identiFierFld->setFieldTagAttribute('onkeyup', "Slugify(this.value,'urlrewrite_custom','prodcat_id');getSlugUrl($(\"#urlrewrite_custom\"),$(\"#urlrewrite_custom\").val(),'" . $parentUrl . "','pre',true)");
$IDFld = $prodCatFrm->getField('parentCatId');
$IDFld->setFieldTagAttribute('id', "prodcat_id");
$urlFld = $prodCatFrm->getField('urlrewrite_custom');
$urlFld->setFieldTagAttribute('id', "urlrewrite_custom");
$urlFld->htmlAfterField = "<small class='text--small'>" . CommonHelper::generateFullUrl('Category', 'View', array($categoryId), CONF_WEBROOT_FRONT_URL) . '</small>';
$urlFld->setFieldTagAttribute('onkeyup', "getSlugUrl(this,this.value)");
?>
<div class='page'>
    <div class='container container-fluid'>
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="page__title">
                    <div class="row justify-content-between">
                        <div class="col--first col-lg-6">
                            <span class="page__icon"><i class="ion-android-star"></i></span>
                            <h5><?php echo Labels::getLabel('LBL_New_category', $adminLangId); ?> </h5>
                            <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                        </div>
                        <div class="col-auto">
                            <a href="javascript:void(0)" class="themebtn btn-primary" type="button">Create</a>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="section">
                            <div class="sectionhead">
                                <h4>General</h4>
                            </div>
                            <div class="sectionbody space">
                                <form class="web_form " action="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">Category Identifier: <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover"><input type="text"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">Parent Category: <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                        <select name="category_data[parent_id]" id="elm_category_parent_id" class="user-success">
                                                            <option value="0">- Root level -</option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">Status: <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">

                                                        <ul class="list-inline">
                                                            <li>
                                                                <label>
                                                                    <span class="radio"><input value="1" name="1" type="radio"><i class="input-helper"></i></span>Active
                                                                </label>
                                                            </li>

                                                            <li>
                                                                <label>
                                                                    <span class="radio"><input name="1" type="radio"><i class="input-helper"></i></span>Disabled
                                                                </label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">SEO friendly URL: <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover"><input type="text"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>


                        <div class="section">
                            <div class="sectionhead">
                                <h4>Language Data</h4>
                            </div>
                            <div class="sectionbody space">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="caption-wraper">
                                                <label class="field_label">Name: <span class="spn_must_field">*</span></label>
                                            </div>
                                            <div class="field-wraper">
                                                <div class="field_cover"><input type="text"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="caption-wraper">
                                                <label class="field_label">Description: <span class="spn_must_field">*</span></label>
                                            </div>
                                            <div class="field-wraper">
                                                <div class="field_cover">
                                                    <img src="https://documentation.thoughtfarmer.com/imagethumb/223316470000/13627/0x0/False/RTE.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="section">
                            <div class="sectionhead">
                                <h4>Media</h4>
                            </div>
                            <div class="sectionbody space">
                                <form class="web_form " action="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3 class="mb-4">Icon</h3>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">Language</label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover"><select class="icon-language-js" data-field-caption="Language" data-fatreq="{&quot;required&quot;:false}" name="lang_id">
                                                                    <option value="0">All Languages</option>
                                                                    <option value="1">English</option>
                                                                    <option value="2">Arabic</option>
                                                                </select></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">Icon</label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover"><input class="catFile-Js btn btn--primary btn--sm" id="shop_logo" data-file_type="11" data-frm="frmCategoryIcon" data-field-caption="Icon" data-fatreq="{&quot;required&quot;:false}" type="button" name="cat_icon" value="Upload"><small class="text--small">This Will Be Displayed In 60*60 On Your Store</small>
                                                                <div id="icon-image-listing">
                                                                    <ul class="grids--onethird" id="sortable">
                                                                        <li id="1850">
                                                                            <div class="logoWrap">
                                                                                <div class="logothumb"> <img src="/category/icon/112/0/THUMB/0" title="men_age_group_51600.png" alt="men_age_group_51600.png"> <a class="deleteLink white" href="javascript:void(0);" title="Delete men_age_group_51600.png" onclick="deleteImage(1850, 112, 'icon', 0, 0 );"><i class="ion-close-round"></i></a>
                                                                                </div>
                                                                                <small class=""><strong> Language:</strong> All</small>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                        <div class="col-md-6">
                                            <h3 class="mb-4">Banner</h3>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">Language</label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover"><select class="banner-language-js" data-field-caption="Language" data-fatreq="{&quot;required&quot;:false}" name="lang_id">
                                                                    <option value="0">All Languages</option>
                                                                    <option value="1">English</option>
                                                                    <option value="2">Arabic</option>
                                                                </select></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">Display For</label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover"><select class="prefDimensions-js" data-field-caption="Display For" data-fatreq="{&quot;required&quot;:false}" name="slide_screen">
                                                                    <option value="1">Desktop</option>
                                                                    <option value="2">Ipad</option>
                                                                    <option value="3">Mobile</option>
                                                                </select></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">Banner</label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover"><input class="catFile-Js btn btn--primary btn--sm" id="shop_logo" data-file_type="12" data-frm="frmCategoryBanner" data-field-caption="Banner" data-fatreq="{&quot;required&quot;:false}" type="button" name="cat_banner" value="Upload">
                                                                <div style="margin-top:15px;" class="preferredDimensions-js">Preferred Dimensions 2000 x 500</div>
                                                                <div id="banner-image-listing">
                                                                    <ul class="grids--onethird" id="sortable">
                                                                        <li id="2266">
                                                                            <div class="logoWrap">
                                                                                <div class="logothumb"> <img src="/category/banner/112/0/THUMB/1" title="men banner.jpg" alt="men banner.jpg"> <a class="deleteLink white" href="javascript:void(0);" title="Delete men banner.jpg" onclick="deleteImage(2266, 112, 'banner', 0, 1 );"><i class="ion-close-round"></i></a>
                                                                                </div>
                                                                                <small class=""><strong> Language:</strong> All</small>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>