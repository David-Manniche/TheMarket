<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
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
                <!--<div class="col-sm-12">-->
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
                                                <label class="field_label">Language: <span class="spn_must_field">*</span></label>
                                            </div>
                                            <div class="field-wraper">
                                                <div class="field_cover"><select>
                                                        <option>English</option>
                                                        <option>Arabic</option>
                                                    </select></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

                <div class="row">
                    <div class="col-md-9">
                        <section class="section">
                            <div class="sectionhead">
                                <h4>Categories</h4>
                            </div>
                            <div class="sectionbody">


                                <div class="tablewrap">
                                    <table class="table table--hovered   table-category-accordion">
                                        <thead>
                                            <tr>
                                                <th width="5%"><label><span class="checkbox"><input value="1" name="1" type="checkbox"><i class="input-helper"></i></span></label></th>
                                                <th width="10%">Pos</th>
                                                <th width="45%">Name</th>
                                                <th width="10%">Products</th>
                                                <th width="10%"></th>
                                                <th width="10%">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><label><span class="checkbox"><input value="1" name="1" type="checkbox"><i class="input-helper"></i></span></label></td>
                                                <td>
                                                    <input class="form-control form-control-sm form-control-position" type="text" placeholder="20" value="20" name="" id="">
                                                </td>
                                                <td><a href="#">Computers <i class="ion-chevron-right"></i></a></td>
                                                <td>
                                                    <a href="#" class="badge badge-secondary badge-pill">0</a></td>
                                                <td>
                                                    <div class="hidden-tools">
                                                        <div class="btn-group">
                                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="ion ion-ios-settings"></i> </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Add Product</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Delete</a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Active</button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#">Active</a>
                                                            <a class="dropdown-item" href="#">Disabled</a>
                                                            <a class="dropdown-item" href="#">Hidden</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label><span class="checkbox"><input value="1" name="1" type="checkbox"><i class="input-helper"></i></span></label></td>
                                                <td>
                                                    <input class="form-control form-control-sm form-control-position" type="text" placeholder="20" value="20" name="" id="">
                                                </td>
                                                <td><a href="#">Computers <i class="ion-chevron-right"></i></a></td>
                                                <td>
                                                    <a href="#" class="badge badge-secondary badge-pill">0</a></td>
                                                <td>
                                                    <div class="hidden-tools">
                                                        <div class="btn-group">
                                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="ion ion-ios-settings"></i> </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Add Product</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Delete</a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Active</button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#">Active</a>
                                                            <a class="dropdown-item" href="#">Disabled</a>
                                                            <a class="dropdown-item" href="#">Hidden</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label><span class="checkbox"><input value="1" name="1" type="checkbox"><i class="input-helper"></i></span></label></td>
                                                <td>
                                                    <input class="form-control form-control-sm form-control-position" type="text" placeholder="20" value="20" name="" id="">
                                                </td>
                                                <td><a href="#">Computers <i class="ion-chevron-right"></i></a></td>
                                                <td>
                                                    <a href="#" class="badge badge-secondary badge-pill">0</a></td>
                                                <td>
                                                    <div class="hidden-tools">
                                                        <div class="btn-group">
                                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="ion ion-ios-settings"></i> </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#">Add Product</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#">Edit</a>
                                                                <a class="dropdown-item" href="#">Delete</a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Active</button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#">Active</a>
                                                            <a class="dropdown-item" href="#">Disabled</a>
                                                            <a class="dropdown-item" href="#">Hidden</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-3">
                        <section class="section">
                            <div class="sectionhead">
                                <h4>Total</h4>
                            </div>
                            <div class="sectionbody">
                                <ul class="list list--vertical theme--txtcolor theme--hovercolor">
                                    <li>Categories <span>23</span></li>
                                    <li>Products <span>4139</span></li>
                                    <li>Active categories <span>23</span></li>
                                    <li>Hidden categories <span>05</span></li>
                                    <li>Disabled categories <span>0</span></li>
                                </ul>
                            </div>
                        </section>

                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <!-- <section class="section">
                            <div class="sectionhead">
                                <h4>Product Options/Variants</h4>
                            </div>
                            <div class="sectionbody">
                                <div class="tablewrap">
                                    <table width="100%" class="table table-responsive table--hovered" id="options">
                                        <thead>
                                            <tr>
                                                <th><label class="checkbox"><input title="Select All" type="checkbox" onclick="selectAll( $(this) )" class="selectAll-js"><i class="input-helper"></i></label></th>
                                                <th>Option Name </th>
                                                <th>Added By</th>
                                                <th>Action Buttons</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><label class="checkbox"><input class="selectItem--js" type="checkbox" name="option_ids[]" value="52"><i class="input-helper"></i></label></td>

                                                <td>Key<br>(Antivirus Serial Key)</td>
                                                <td>Admin</td>
                                                <td>
                                                    <ul class="actions actions--centered">
                                                        <li class="droplink"><a href="javascript:void(0)" class="button small green" title="Edit"><i class="ion-android-more-horizontal icon"></i></a>
                                                            <div class="dropwrap">
                                                                <ul class="linksvertical">
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Edit" onclick="addOptionFormNew(52)">Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Delete" onclick="deleteOptionRecord(52)">Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label class="checkbox"><input class="selectItem--js" type="checkbox" name="option_ids[]" value="52"><i class="input-helper"></i></label></td>

                                                <td>Key<br>(Antivirus Serial Key)</td>
                                                <td>Admin</td>
                                                <td>
                                                    <ul class="actions actions--centered">
                                                        <li class="droplink"><a href="javascript:void(0)" class="button small green" title="Edit"><i class="ion-android-more-horizontal icon"></i></a>
                                                            <div class="dropwrap">
                                                                <ul class="linksvertical">
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Edit" onclick="addOptionFormNew(52)">Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Delete" onclick="deleteOptionRecord(52)">Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label class="checkbox"><input class="selectItem--js" type="checkbox" name="option_ids[]" value="52"><i class="input-helper"></i></label></td>

                                                <td>Key<br>(Antivirus Serial Key)</td>
                                                <td>Admin</td>
                                                <td>
                                                    <ul class="actions actions--centered">
                                                        <li class="droplink"><a href="javascript:void(0)" class="button small green" title="Edit"><i class="ion-android-more-horizontal icon"></i></a>
                                                            <div class="dropwrap">
                                                                <ul class="linksvertical">
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Edit" onclick="addOptionFormNew(52)">Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Delete" onclick="deleteOptionRecord(52)">Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label class="checkbox"><input class="selectItem--js" type="checkbox" name="option_ids[]" value="52"><i class="input-helper"></i></label></td>

                                                <td>Key<br>(Antivirus Serial Key)</td>
                                                <td>Admin</td>
                                                <td>
                                                    <ul class="actions actions--centered">
                                                        <li class="droplink"><a href="javascript:void(0)" class="button small green" title="Edit"><i class="ion-android-more-horizontal icon"></i></a>
                                                            <div class="dropwrap">
                                                                <ul class="linksvertical">
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Edit" onclick="addOptionFormNew(52)">Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Delete" onclick="deleteOptionRecord(52)">Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label class="checkbox"><input class="selectItem--js" type="checkbox" name="option_ids[]" value="52"><i class="input-helper"></i></label></td>

                                                <td>Key<br>(Antivirus Serial Key)</td>
                                                <td>Admin</td>
                                                <td>
                                                    <ul class="actions actions--centered">
                                                        <li class="droplink"><a href="javascript:void(0)" class="button small green" title="Edit"><i class="ion-android-more-horizontal icon"></i></a>
                                                            <div class="dropwrap">
                                                                <ul class="linksvertical">
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Edit" onclick="addOptionFormNew(52)">Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Delete" onclick="deleteOptionRecord(52)">Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label class="checkbox"><input class="selectItem--js" type="checkbox" name="option_ids[]" value="52"><i class="input-helper"></i></label></td>

                                                <td>Key<br>(Antivirus Serial Key)</td>
                                                <td>Admin</td>
                                                <td>
                                                    <ul class="actions actions--centered">
                                                        <li class="droplink"><a href="javascript:void(0)" class="button small green" title="Edit"><i class="ion-android-more-horizontal icon"></i></a>
                                                            <div class="dropwrap">
                                                                <ul class="linksvertical">
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Edit" onclick="addOptionFormNew(52)">Edit</a></li>
                                                                    <li><a href="javascript:void(0)" class="button small green" title="Delete" onclick="deleteOptionRecord(52)">Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section> -->

                        <section class="section">
                            <div class="sectionhead">
                                <h4>Add Product Option/Variant</h4>
                            </div>
                            <div class="sectionbody space">
                                <form id="frmOptions" name="frmOptions" method="post" class="web_form" onsubmit="submitOptionForm(this); return(false);">
                                    <div class="accordians_container accordians_container-categories">
                                        <div class="accordian_panel">
                                            <span class="accordian_title accordianhead accordian_title">Product Option/Variant Group</span>
                                            <div class="accordian_body accordiancontent" style="display: none;">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Option Identifier<span class="spn_must_field">*</span></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><input data-field-caption="Option Identifier" data-fatreq="{&quot;required&quot;:true}" type="text" name="option_identifier" value="colour"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="layout--ltr col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Option Name in {English}<span class="spn_must_field">*</span></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><input data-field-caption="Option Name  English" data-fatreq="{&quot;required&quot;:true}" type="text" name="option_name1" value="colour"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Option Name in {Arabic}<span class="spn_must_field">*</span></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><input data-field-caption="Option Name  Arabic" data-fatreq="{&quot;required&quot;:true}" type="text" name="option_name2" value="colour"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Is This Option Group Is A Color Option?<span class="spn_must_field">*</span></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><select data-field-caption="Option Have Separate Image" data-fatreq="{&quot;required&quot;:true}" name="option_is_separate_images">
                                                                        <option value="1" selected="selected">Yes</option>
                                                                        <option value="0">No</option>
                                                                    </select></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Will This Option Group Have Separate Images?<span class="spn_must_field">*</span></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><select data-field-caption="Option Is Color" data-fatreq="{&quot;required&quot;:true}" name="option_is_color">
                                                                        <option value="1" selected="selected">Yes</option>
                                                                        <option value="0">No</option>
                                                                    </select></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Should This Option Group be Displayed In Filters?<span class="spn_must_field">*</span></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><select data-field-caption="Option Display In Filters" data-fatreq="{&quot;required&quot;:true}" name="option_display_in_filter">
                                                                        <option value="1" selected="selected">Yes</option>
                                                                        <option value="0">No</option>
                                                                    </select></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label"></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><input data-field-caption="" data-fatreq="{&quot;required&quot;:false}" type="submit" name="btn_submit" value="Save"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="accordian_panel">
                                            <span class="accordian_title accordianhead accordian_title">Product Option/Variant Group Values</span>
                                            <div class="accordian_body accordiancontent" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Option Value Identifier<span class="spn_must_field">*</span></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><input data-field-caption="Option Value Identifier" data-fatreq="{&quot;required&quot;:true}" type="text" name="optionvalue_identifier" value=""></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Option Value Name In {English}<span class="spn_must_field">*</span></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><input data-field-caption="Option Value Name English" data-fatreq="{&quot;required&quot;:true}" type="text" name="optionvalue_name1" value=""></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Option Value Name In {Arabic}<span class="spn_must_field">*</span></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><input data-field-caption="Option Value Name Arabic" data-fatreq="{&quot;required&quot;:true}" type="text" name="optionvalue_name2" value=""></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label">Select/Enter Option Value Color Code</label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><input class="jscolor" data-field-caption="Option Value Color" data-fatreq="{&quot;required&quot;:false}" type="text" name="optionvalue_color_code" value="" autocomplete="off" style="background-image: none; background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="field-set">
                                                            <div class="caption-wraper"><label class="field_label"></label></div>
                                                            <div class="field-wraper">
                                                                <div class="field_cover"><input data-field-caption="" data-fatreq="{&quot;required&quot;:false}" type="submit" name="btn_submit" value="Save"><input onclick="optionForm(53);" data-field-caption="" data-fatreq="{&quot;required&quot;:false}" type="button" name="btn_clear" value="Cancel"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-6">
                        <section class="section">
                            <div class="sectionhead">
                                <h4>{Option Group Name} Values</h4>
                            </div>
                            <div class="sectionbody">
                                <table width="100%" class="table table-responsive table--hovered" id="optionvalues">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Option Group Values</th>
                                            <th>Action Buttons</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="183">
                                            <td class="dragHandle"><i class="ion-arrow-move icon"></i></td>
                                            <td>red<br>(red)</td>
                                            <td>
                                                <ul class="actions actions--centered">
                                                    <li class="droplink"><a href="javascript:void(0)" class="button small green" title="Edit"><i class="ion-android-more-horizontal icon"></i></a>
                                                        <div class="dropwrap">
                                                            <ul class="linksvertical">
                                                                <li><a href="javascript:void(0)" class="button small green" title="Edit" onclick="optionValueForm(53,183)">Edit</a></li>
                                                                <li><a href="javascript:void(0)" class="button small green" title="Delete" onclick="deleteOptionValue(53,183)">Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="184">
                                            <td class="dragHandle"><i class="ion-arrow-move icon"></i></td>
                                            <td>Blue<br>(Blue)</td>
                                            <td>
                                                <ul class="actions actions--centered">
                                                    <li class="droplink"><a href="javascript:void(0)" class="button small green" title="Edit"><i class="ion-android-more-horizontal icon"></i></a>
                                                        <div class="dropwrap">
                                                            <ul class="linksvertical">
                                                                <li><a href="javascript:void(0)" class="button small green" title="Edit" onclick="optionValueForm(53,184)">Edit</a></li>
                                                                <li><a href="javascript:void(0)" class="button small green" title="Delete" onclick="deleteOptionValue(53,184)">Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>
                        </section>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="tabs_nav_container vertical wizard-tabs-vertical">
                    <ul class="tabs_nav">
                        <li><a class="active" rel="tabs_001" href="javascript:void(0)">
                                <i class="tabs-icon fa fa-globe"></i>
                                <div class="tabs-head">
                                    <div class="tabs-title">Initial Setup<span>Setup Basic Product Details</span></div>
                                </div>

                            </a></li>
                        <li><a rel="tabs_002" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                <div class="tabs-head">
                                    <div class="tabs-title">Product Options & Specifications<span>Add Product Specification & Option details</span></div>

                                </div>
                            </a></li>
                        <li><a rel="tabs_003" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                <div class="tabs-head">
                                    <div class="tabs-title">Product Attribute<span>Add Product Related Specifications</span></div>
                                </div>
                            </a></li>

                        <li><a rel="tabs_004" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                <div class="tabs-head">
                                    <div class="tabs-title">Shipping Information<span>Setup Product Dimentions & Shipping Information</span></div>
                                </div>
                            </a></li>
                        <li><a rel="tabs_005" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                <div class="tabs-head">
                                    <div class="tabs-title"> Product Media<span>Add Option Based Product Media</span></div>
                                </div>
                            </a></li>

                    </ul>

                    <div class="tabs_panel_wrap">
                        <div id="tabs_001" class="tabs_panel" style="display: block;">
                            <div class="row justify-content-center">
                                <div class="col-md-9">
                                    <form class="web_form mt-5">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Product Identifier<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input title="Product Identifier" data-fatreq="{&quot;required&quot;:true}" type="text" name="product_identifier" value=""> <span class="form-text text-muted">It May Be Same As Of Product Name </span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Product Type</label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><select name="product_type">
                                                                <option value="1" selected="selected">Physical</option>
                                                                <option value="2">Digital</option>
                                                            </select></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Brand<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input title="Model" data-fatreq="{&quot;required&quot;:true}" type="text" name="product_model" value=""></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Category<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <input type="text" name="tags" class="js-tagify" value='tag1, tag2'>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-4 mb-4 bg-gray rounded">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">Language</label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover"><select>
                                                                    <option>English</option>
                                                                    <option>Arabic</option>
                                                                </select></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">Name</label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover"><input type="text"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set mb-0">
                                                        <div class="caption-wraper"><label class="field_label">Description</label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover"> <textarea name="" id="" cols="30" rows="10"></textarea></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Tax Category<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><select title="Tax Category" data-fatreq="{&quot;required&quot;:true}" name="ptt_taxcat_id">
                                                                <option value="">Select</option>
                                                                <option value="4">Electronics</option>
                                                                <option value="5">Clothing</option>
                                                                <option value="6">Footwears</option>
                                                                <option value="8">Baby and kids</option>
                                                            </select></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Minimum Selling Price [$]<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input title="Minimum Selling Price [$]" data-fatreq="{&quot;required&quot;:true,&quot;floating&quot;:true,&quot;range&quot;:{&quot;minval&quot;:0,&quot;maxval&quot;:9999999999,&quot;numeric&quot;:true}}" type="text" name="product_min_selling_price" value=""></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Approval Status</label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><select title="Approval Status" data-fatreq="{&quot;required&quot;:false}" name="product_approved">
                                                                <option value="0">Un-approved</option>
                                                                <option value="1" selected="selected">Approved</option>
                                                            </select></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Product Status</label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><select title="Product Status" data-fatreq="{&quot;required&quot;:false}" name="product_active">
                                                                <option value="1">Active</option>
                                                                <option value="0" selected="selected">In-active</option>
                                                            </select></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label"></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input title="" data-fatreq="{&quot;required&quot;:false}" type="submit" name="btn_submit" value="Save Changes"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div id="tabs_002" class="tabs_panel">
                            <div class="row justify-content-center">
                                <div class="col-md-9">
                                    <form class="web_form mt-5">
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
                                                            <th width="80%">Variants</th>
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

                                        <h3 class="form__heading mt-5">Specifications</h3>
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <h4>English</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label">Specification Label Text<span class="mandatory">*</span></label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <input data-field-caption="Specification Label Text" data-fatreq="{&quot;required&quot;:true}" type="text" name="prod_spec_name[1]" value=""> </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label">Specification Value<span class="mandatory">*</span></label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <input data-field-caption="Specification Value" data-fatreq="{&quot;required&quot;:true}" type="text" name="prod_spec_value[1]" value=""> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <h4>Arabic</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label">Specification Label Text<span class="mandatory">*</span></label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <input data-field-caption="Specification Label Text" data-fatreq="{&quot;required&quot;:true}" type="text" name="prod_spec_name[1]" value=""> </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label">Specification Value<span class="mandatory">*</span></label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <input data-field-caption="Specification Value" data-fatreq="{&quot;required&quot;:true}" type="text" name="prod_spec_value[1]" value=""> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="tablewrap">
                                                    <table width="100%" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th width="80%">Specification</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Battery: Lithium Ion</td>
                                                                <td><a title="Edit details" class="btn btn-sm btn-clean btn-icon btn-icon-md"> <i class="fa fa-edit"></i> </a>
                                                                    <a title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md"> <i class="fa fa-trash"></i> </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Test lable text: test specification</td>
                                                                <td> <a title="Edit details" class="btn btn-sm btn-clean btn-icon btn-icon-md"> <i class="fa fa-edit"></i> </a>
                                                                    <a title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md"> <i class="fa fa-trash"></i> </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="tabs_003" class="tabs_panel">
                            <div class="row justify-content-center">
                                <div class="col-md-9">


                                    <form action="" class="web_form mt-5">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">User</label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input type="text">
                                                            <span>Please Leave Empty If You Want To Add Product In System Catalog </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Model</label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input type="text" value=""> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="field-set">
                                                    <div class="caption-wraper">&nbsp;</div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><label><span class="checkbox"><input data-field-caption="Free Shipping" data-fatreq="{&quot;required&quot;:false}" type="checkbox" name="ps_free" value="1"><i class="input-helper"></i></span>Do You Want To Mark This Product As Featured? </label></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Product Tags</label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input type="text" name="tags" class="js-tagify" value="tagify"> </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Product Collection</label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input type="text" name="tags" class="js-tagify" value="tagify"> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="field-set">
                                                    <div class="caption-wraper">Is This Product Eligible For Free Shipping?</div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <ul class="list-inline">
                                                                <li>
                                                                    <label>
                                                                        <span class="radio"><input value="1" name="1" type="radio"><i class="input-helper"></i></span>Yes
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label>
                                                                        <span class="radio"><input name="1" type="radio"><i class="input-helper"></i></span>No
                                                                    </label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Is This Product Available for Cash on Delivery (COD)?</label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <ul class="list-inline">
                                                                <li>
                                                                    <label>
                                                                        <span class="radio"><input value="1" name="1" type="radio"><i class="input-helper"></i></span>Yes
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label>
                                                                        <span class="radio"><input name="1" type="radio"><i class="input-helper"></i></span>No
                                                                    </label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="tabs_004" class="tabs_panel">
                            <div class="row justify-content-center">
                                <div class="col-md-9">
                                    <form action="" class="web_form mt-5">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Dimensions Unit<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><select data-field-caption="Dimensions Unit" data-fatreq="{&quot;required&quot;:true}" name="product_dimension_unit">
                                                                <option value="">Select</option>
                                                                <option value="1">Centimeter</option>
                                                                <option value="2">Meter</option>
                                                                <option value="3">Inch</option>
                                                            </select></div>
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Length<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input type="text" name="product_length" value="0.00"><small>Note: Used For Shipping Calculation.</small></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                       

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Width<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input type="text" name="product_width" value="0.00"><small>Note: Used For Shipping Calculation.</small></div>
                                                    </div>
                                                </div>
                                            </div><div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Height<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input type="text" name="product_height" value="0.00"><small>Note: Used For Shipping Calculation.</small></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
 

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Weight Unit<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><select data-field-caption="Weight Unit" data-fatreq="{&quot;required&quot;:true}" name="product_weight_unit">
                                                                <option value="">Select</option>
                                                                <option value="1">Gram</option>
                                                                <option value="2">Kilogram</option>
                                                                <option value="3">Pound</option>
                                                            </select></div>
                                                    </div>
                                                </div>
                                            </div>  <div class="col-md-6">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Weight<span class="spn_must_field">*</span></label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input data-field-caption="Weight" data-fatreq="{&quot;required&quot;:true,&quot;floating&quot;:true,&quot;range&quot;:{&quot;minval&quot;:0.01,&quot;maxval&quot;:9999999999,&quot;numeric&quot;:true}}" type="text" name="product_weight" value="0.00"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="field-set">
                                                    <div class="caption-wraper"><label class="field_label">Country the Product is being shipped from</label></div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover"><input data-field-caption="Country the Product is being shipped from" data-fatreq="{&quot;required&quot;:false}" type="text" name="shipping_country" value="" autocomplete="off">
                                                            <ul class="dropdown-menu"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <table id="tab_shipping" width="100%" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td colspan="2" class="nopadding">
                                                        <table id="shipping">
                                                            <thead>
                                                                <tr>
                                                                    <th width="17%">Ships To</th>
                                                                    <th width="17%">Shipping Company</th>
                                                                    <th width="17%">Processing Time</th>
                                                                    <th width="18%">Cost [$]</th>
                                                                    <th width="27%">Each Additional Item [$] </th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr id="shipping-row0">
                                                                    <td ><input type="text" name="product_shipping[0][country_name]" value="" placeholder="Ships To" autocomplete="off">
                                                                        <ul class="dropdown-menu" style="width: 91px; top: 60px; left: 20px; display: none;">
                                                                            <li data-value="-1"><a href="#">Everywhere Else</a></li>
                                                                        </ul><input type="hidden" name="product_shipping[0][country_id]" value="-1">
                                                                    </td>
                                                                    <td ><input type="text" name="product_shipping[0][company_name]" value="" placeholder="Shipping Company" autocomplete="off">
                                                                        <ul class="dropdown-menu" style="width: 95px; top: 60px; left: 20px; display: none;">
                                                                            <li data-value="3"><a href="#">DHL</a></li>
                                                                            <li data-value="1"><a href="#">Fedex</a></li>
                                                                            <li data-value="2"><a href="#">UPS</a></li>
                                                                        </ul><input type="hidden" name="product_shipping[0][company_id]" value="3">
                                                                    </td>
                                                                    <td ><input type="text" name="product_shipping[0][processing_time]" value="" placeholder="Processing Time" autocomplete="off">
                                                                        <ul class="dropdown-menu" style="width: 97px; top: 60px; left: 20px; display: none;">
                                                                            <li data-value="1"><a href="#">Gold Shipping[1 to 6 Business Days]</a></li>
                                                                            <li data-value="2"><a href="#">Silver Shipping[1 to 9 Business Days]</a></li>
                                                                        </ul><input type="hidden" name="product_shipping[0][processing_time_id]" value="2">
                                                                    </td>
                                                                    <td><input type="text" name="product_shipping[0][cost]" value="" placeholder="Cost"></td>
                                                                    <td><input type="text" name="product_shipping[0][additional_cost]" value="" placeholder="Each Additional Item"></td>
                                                                    <td><button type="button" class="btn btn--secondary ripplelink" title="Remove" onclick="removeShippingRow(0)"><i class="ion-minus-round"></i></button></td>
                                                                </tr>
                                                                <tr id="shipping-row1">
                                                                    <td ><input type="text" name="product_shipping[1][country_name]" value="" placeholder="Ships To" autocomplete="off">
                                                                        <ul class="dropdown-menu" style="width: 91px; top: 60px; left: 20px; display: none;">
                                                                            <li data-value="244"><a href="#">Aaland Islands</a></li>
                                                                            <li data-value="1"><a href="#">Afghanistan</a></li>
                                                                            <li data-value="2"><a href="#">Albania</a></li>
                                                                            <li data-value="3"><a href="#">Algeria</a></li>
                                                                            <li data-value="4"><a href="#">American Samoa</a></li>
                                                                            <li data-value="7"><a href="#">Anguilla</a></li>
                                                                            <li data-value="8"><a href="#">Antarctica</a></li>
                                                                            <li data-value="9"><a href="#">Antigua and Barbuda</a></li>
                                                                            <li data-value="10"><a href="#">Argentina</a></li>
                                                                            <li data-value="11"><a href="#">Armenia</a></li>
                                                                            <li data-value="-1"><a href="#">Everywhere Else</a></li>
                                                                        </ul><input type="hidden" name="product_shipping[1][country_id]" value="2">
                                                                    </td>
                                                                    <td ><input type="text" name="product_shipping[1][company_name]" value="" placeholder="Shipping Company" autocomplete="off">
                                                                        <ul class="dropdown-menu" style="width: 95px; top: 60px; left: 20px; display: none;">
                                                                            <li data-value="3"><a href="#">DHL</a></li>
                                                                            <li data-value="1"><a href="#">Fedex</a></li>
                                                                            <li data-value="2"><a href="#">UPS</a></li>
                                                                        </ul><input type="hidden" name="product_shipping[1][company_id]" value="1">
                                                                    </td>
                                                                    <td ><input type="text" name="product_shipping[1][processing_time]" value="" placeholder="Processing Time" autocomplete="off">
                                                                        <ul class="dropdown-menu" style="width: 97px; top: 60px; left: 20px; display: none;">
                                                                            <li data-value="1"><a href="#">Gold Shipping[1 to 6 Business Days]</a></li>
                                                                            <li data-value="2"><a href="#">Silver Shipping[1 to 9 Business Days]</a></li>
                                                                        </ul><input type="hidden" name="product_shipping[1][processing_time_id]" value="1">
                                                                    </td>
                                                                    <td><input type="text" name="product_shipping[1][cost]" value="" placeholder="Cost"></td>
                                                                    <td><input type="text" name="product_shipping[1][additional_cost]" value="" placeholder="Each Additional Item"></td>
                                                                    <td><button type="button" class="btn btn--secondary ripplelink" title="Remove" onclick="removeShippingRow(1)"><i class="ion-minus-round"></i></button></td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="5"></td>
                                                                    <td><button type="button" class="btn btn--secondary ripplelink" title="Shipping" onclick="addShipping();"><i class="ion-plus-round"></i></button></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div id="tabs_005" class="tabs_panel">
                           <div class="row justify-content-center">
                               <div class="col-md-9"> <form id="imageFrm" name="imageFrm" method="post" enctype="multipart/form-data" class="web_form mt-5">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="field-set">
                                            <div class="caption-wraper"><label class="field_label">Image File Type</label></div>
                                            <div class="field-wraper">
                                                <div class="field_cover"><select class="option-js" data-field-caption="Image File Type" data-fatreq="{&quot;required&quot;:false}" name="option_id">
                                                        <option value="0" selected="selected">For All Options</option>
                                                        <option value="133">7</option>
                                                        <option value="134">8</option>
                                                        <option value="135">9</option>
                                                    </select></div>
                                            </div>
                                        </div>
                                    </div> <div class="col-md-6">
                                        <div class="field-set">
                                            <div class="caption-wraper"><label class="field_label">Language</label></div>
                                            <div class="field-wraper">
                                                <div class="field_cover"><select class="language-js" data-field-caption="Language" data-fatreq="{&quot;required&quot;:false}" name="lang_id">
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
                                            <div class="caption-wraper"><label class="field_label">Photo(s):</label></div>
                                            <div class="field-wraper">
                                                <div class="field_cover"><span class="filename"></span><input id="prod_image" multiple="multiple" onchange="submitImageUploadForm(); return false;" data-field-caption="Photo(s):" data-fatreq="{&quot;required&quot;:false}" type="file" name="prod_image" value=""><br><small>Please Keep Image Dimensions Greater Than 500 X 500. You Can Upload Multiple Photos From Here.</small></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </form>
                            <div id="imageupload_div" class="">
                                <ul class="grids--onefifth ui-sortable" id="sortable">
                                    <li id="2361" class="ui-sortable-handle">
                                        <div class="logoWrap">
                                            <div class="logothumb"> <img src="/admin/image/product/73/THUMB/2361" title="0.png" alt="0.png"> <small><strong>Default Image</strong></small> <a class="deleteLink white" href="javascript:void(0);" title="Delete 0.png" onclick="deleteImage(73, 2361);"><i class="ion-close-round"></i></a>
                                            </div>
                                            <small class=""><strong>Type: </strong> For All Options</small><br> <small class=""><strong> Language:</strong> All</small>
                                        </div>
                                    </li>
                                </ul>
                            </div></div>
                           </div>
                           

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<script>
    // jQuery
    $('[name=tags]').tagify();
</script>