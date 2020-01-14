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
                                                     <label class="field_label">Category Name: <span class="spn_must_field">*</span></label>
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
                                             <div class="field-set d-flex align-items-center">
                                                 <div class="caption-wraper w-auto pr-4">
                                                     <label class="field_label">Status: <span class="spn_must_field">*</span></label>
                                                 </div>
                                                 <div class="field-wraper w-auto">
                                                     <div class="field_cover">

                                                         <ul class="list-inline-checkboxes">
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
                                                 <label><span class="checkbox"><input value="1" name="1" type="checkbox"><i class="input-helper"></i> Translate For Other Languages</span></label>
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

                                 </form>
                             </div>
                         </div>
                         <div class="section" id="accordion-language">
                             <div class="sectionhead" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                 <h4 class="accordion-head">Language Data For {Arabic}</h4>
                             </div>
                             <div class="sectionbody space collapse show" id="collapseOne" aria-labelledby="headingOne" data-parent="#accordion-language">
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
                                                             <div class="field_cover"><input class="catFile-Js btn btn--primary btn--sm" id="shop_logo" data-file_type="11" data-frm="frmCategoryIcon" data-field-caption="Icon" data-fatreq="{&quot;required&quot;:false}" type="button" name="cat_icon" value="Upload"><small class="form-text text-muted">This Will Be Displayed In 60*60 On Your Store</small>
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
                                 <div class="section__toolbar">                                   
                                     <a href="#" class="btn btn-clean btn-sm btn-icon btn-secondary"><i class="fas fa-plus"></i></a>
                                     
                                 </div>
                                 
                             </div>
                             <div class="sectionbody space">
                                 <div class="accordion-categories">

                                     <ul id="sorting-categories" class="sorting-categories">
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm active">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         
                                                         <a href="#" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i></a>
                                                         
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="clickable">
                                                 <div class="sorting-bar">
                                                     <div class="sorting-title">Computers <span class="badge badge-secondary badge-pill">0</span></div>
                                                     <div class="sorting-actions"><label class="statustab statustab-sm">
                                                             <span class="switch-labels"></span>
                                                             <span class="switch-handles"></span>
                                                         </label>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button>
                                                         <button type="button" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash"></i>
                                                         </button></div>



                                                 </div>
                                             </div>
                                         </li>
                                     </ul>
                                 </div>
                                 <div class="tablewrap">
                                     <table class="table table--hovered   table-category-accordion">
                                         <thead>
                                             <tr>
                                                 <th width="5%"><label><span class="checkbox"><input value="1" name="1" type="checkbox"><i class="input-helper"></i></span></label></th>
                                                 <th width="10%">Pos</th>
                                                 <th width="35%">Name</th>
                                                 <th width="15%">Products</th>
                                                 <th width="15%">Status</th>
                                                 <th width="10%"></th>
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
                                                     <div class="btn-group">
                                                         <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Active</button>
                                                         <div class="dropdown-menu">
                                                             <a class="dropdown-item" href="#">Active</a>
                                                             <a class="dropdown-item" href="#">Disabled</a>
                                                         </div>
                                                     </div>

                                                 </td>
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
                                                     <div class="btn-group">
                                                         <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Active</button>
                                                         <div class="dropdown-menu">
                                                             <a class="dropdown-item" href="#">Active</a>
                                                             <a class="dropdown-item" href="#">Disabled</a>
                                                         </div>
                                                     </div>

                                                 </td>
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
                                                     <div class="btn-group">
                                                         <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Active</button>
                                                         <div class="dropdown-menu">
                                                             <a class="dropdown-item" href="#">Active</a>
                                                             <a class="dropdown-item" href="#">Disabled</a>
                                                         </div>
                                                     </div>

                                                 </td>
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
                             <div class="sectionbody ">
                                 <ul class=" list-group list-group-flush">
                                     <li class="list-group-item d-flex justify-content-between align-items-center">Categories <span class="badge badge-secondary badge-pill">23</span></li>
                                     <li class="list-group-item d-flex justify-content-between align-items-center">Products <span class="badge badge-secondary badge-pill">4139</span></li>
                                     <li class="list-group-item d-flex justify-content-between align-items-center">Active categories <span class="badge badge-secondary badge-pill">23</span></li>
                                     <li class="list-group-item d-flex justify-content-between align-items-center">Disabled categories <span class="badge badge-secondary badge-pill">0</span></li>
                                 </ul>
                             </div>
                         </section>

                         <section class="section">
                             <div class="sectionhead">
                                 <h4>Message title</h4>
                             </div>
                             <div class="sectionbody space ">
                                 <div class="note">
                                     <div class="note-icon">
                                         <i class="fas fa-info-circle"></i>
                                     </div>
                                     <div class="note-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuri</div>
                                 </div>


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
                                                             <ul class="list-inline-checkboxes">
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
                                             </div>
                                             <div class="col-md-6">
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
                                             </div>
                                             <div class="col-md-6">
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
                                                                     <td><input type="text" name="product_shipping[0][country_name]" value="" placeholder="Ships To" autocomplete="off">
                                                                         <ul class="dropdown-menu" style="width: 91px; top: 60px; left: 20px; display: none;">
                                                                             <li data-value="-1"><a href="#">Everywhere Else</a></li>
                                                                         </ul><input type="hidden" name="product_shipping[0][country_id]" value="-1">
                                                                     </td>
                                                                     <td><input type="text" name="product_shipping[0][company_name]" value="" placeholder="Shipping Company" autocomplete="off">
                                                                         <ul class="dropdown-menu" style="width: 95px; top: 60px; left: 20px; display: none;">
                                                                             <li data-value="3"><a href="#">DHL</a></li>
                                                                             <li data-value="1"><a href="#">Fedex</a></li>
                                                                             <li data-value="2"><a href="#">UPS</a></li>
                                                                         </ul><input type="hidden" name="product_shipping[0][company_id]" value="3">
                                                                     </td>
                                                                     <td><input type="text" name="product_shipping[0][processing_time]" value="" placeholder="Processing Time" autocomplete="off">
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
                                                                     <td><input type="text" name="product_shipping[1][country_name]" value="" placeholder="Ships To" autocomplete="off">
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
                                                                     <td><input type="text" name="product_shipping[1][company_name]" value="" placeholder="Shipping Company" autocomplete="off">
                                                                         <ul class="dropdown-menu" style="width: 95px; top: 60px; left: 20px; display: none;">
                                                                             <li data-value="3"><a href="#">DHL</a></li>
                                                                             <li data-value="1"><a href="#">Fedex</a></li>
                                                                             <li data-value="2"><a href="#">UPS</a></li>
                                                                         </ul><input type="hidden" name="product_shipping[1][company_id]" value="1">
                                                                     </td>
                                                                     <td><input type="text" name="product_shipping[1][processing_time]" value="" placeholder="Processing Time" autocomplete="off">
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
                                 <div class="col-md-9">
                                     <form id="imageFrm" name="imageFrm" method="post" enctype="multipart/form-data" class="web_form mt-5">
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
                                             </div>
                                             <div class="col-md-6">
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
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <div class="row justify-content-center">
                     <div class="col-md-12">
                         <div class="section">
                             <div class="sectionhead">
                                 <h4>Inventory Setup</h4>
                             </div>
                             <div class="sectionbody space">
                                 <div class="row justify-content-center">
                                     <div class="col-md-10">

                                         <form name="frmSellerProduct" method="post" id="frm_fat_id_frmSellerProduct" onsubmit="setUpSellerProduct(this); return(false);" class="web_form">

                                             <div class="row">

                                                 <div class="col-md-6">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label">Title<span class="spn_must_field">*</span></label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><input type="text" value="" placeholder="Apple Iphone "></div>
                                                         </div>
                                                     </div>
                                                 </div>


                                                 <div class="col-md-6">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label">Seller Name</label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><input readonly type="text" value="" placeholder="Micheal Shop"></div>
                                                         </div>
                                                     </div>
                                                 </div>

                                             </div>
                                             <div class="row">
                                                 <div class="col-md-6">
                                                     <div class="field-set d-flex align-items-center">
                                                         <div class="caption-wraper">
                                                             <div class="field_label">Do You Want to Maintain Stock Levels?</div>
                                                         </div>
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
                                                 <div class="col-md-6">
                                                     <div class="field-set d-flex align-items-center">
                                                         <div class="caption-wraper">Do You Want to Track Product Inventory?</div>
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
                                                 <div class="selprod_threshold_stock_level_fld col-md-6">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label">Quantity At Which Stock Level Alerts Are Sent:</label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><input data-field-caption="Quantity At Which Stock Level Alerts Are Sent:" data-fatreq="{&quot;required&quot;:false,&quot;integer&quot;:true}" type="text" name="selprod_threshold_stock_level" value="0" disabled="disabled"><small class="form-text text-muted">An email notification will be sent out when 'Available Quantity' is below or equal to the 'Quantity At Which Stock Level Alerts Are Sent' quantity. 'Track Product Inventory' should be set to 'Track'.</small></div>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label">Minimum Purchase Quantity<span class="spn_must_field">*</span></label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><input data-field-caption="Minimum Purchase Quantity" data-fatreq="{&quot;required&quot;:true,&quot;integer&quot;:true,&quot;range&quot;:{&quot;minval&quot;:0,&quot;maxval&quot;:9999999999,&quot;numeric&quot;:true}}" type="text" name="selprod_min_order_qty" value="1"></div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>



                                             <div class="row">
                                                 <div class="col-md-6">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label">Product Condition<span class="spn_must_field">*</span></label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><select data-field-caption="Product Condition" data-fatreq="{&quot;required&quot;:true}" name="selprod_condition">
                                                                     <option value="">Select Condition</option>
                                                                     <option value="1" selected="selected">New</option>
                                                                     <option value="2">Used</option>
                                                                     <option value="3">Refurbished</option>
                                                                 </select></div>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div class="col-md-6">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label">Date Available from<span class="spn_must_field">*</span></label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><input readonly="readonly" data-field-caption="Date Available from" id="selprod_available_from_1577173973_47" data-fatdateformat="yy-mm-dd" data-fatreq="{&quot;required&quot;:true}" type="text" name="selprod_available_from" value="2019-12-24" class="fld-date hasDatepicker">

                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>

                                             <div class="row">
                                                 <div class="col-md-6">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label">Status</label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><select data-field-caption="Status" data-fatreq="{&quot;required&quot;:false}" name="selprod_active">
                                                                     <option value="1" selected="selected">Active</option>
                                                                     <option value="0">In-active</option>
                                                                 </select></div>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div class="selprod_cod_enabled_fld col-md-6">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label">Available for Cash on Delivery (COD)</label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><select data-field-caption="Available for Cash on Delivery (COD)" data-fatreq="{&quot;required&quot;:false}" name="selprod_cod_enabled">
                                                                     <option value="1">Yes</option>
                                                                     <option value="0" selected="selected">No</option>
                                                                 </select></div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>


                                             <div class="row">
                                                 <div class="col-md-6">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label">Additional Comments For The Buyer</label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><textarea data-field-caption="Additional Comments For The Buyer" data-fatreq="{&quot;required&quot;:false}" name="selprod_comments"></textarea></div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="row">
                                                 <div class="col-md-12">
                                                     <div class="field-set">
                                                         <div class="caption-wraper"><label class="field_label"></label></div>
                                                         <div class="field-wraper">
                                                             <div class="field_cover"><input data-field-caption="" data-fatreq="{&quot;required&quot;:false}" type="submit" name="btn_submit" value="Save Changes"></div>
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

                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>

                 <div class="row equal-height">
                     <div class="col-md-6">
                         <section class="section">
                             <div class="sectionhead">
                                 <h4>Sms Templates</h4>

                             </div>
                             <div class="sectionbody">
                                 <table class="table">
                                     <thead>
                                         <tr>
                                             <th>#</th>
                                             <th>Template name</th>
                                             <th>Active</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <tr data-id="2">
                                             <td scope="row">1</td>
                                             <td><a title="Edit">Make Payment</a></td>
                                             <td><label class="statustab statustab-sm active">
                                                     <span class="switch-labels"></span>
                                                     <span class="switch-handles"></span>
                                                 </label></td>
                                         </tr>
                                         <tr data-id="1">
                                             <td scope="row">2</td>
                                             <td><a title="Edit">Forgot Password</a></td>
                                             <td><label class="statustab statustab-sm active">
                                                     <span class="switch-labels"></span>
                                                     <span class="switch-handles"></span>
                                                 </label></td>
                                         </tr>
                                     </tbody>
                                 </table>
                                 <div class="section footinfo">
                                     <aside class="grid_1">
                                         <ul class="pagination">
                                             <li class="selected"><a href="javascript:void(0);">1</a></li>
                                         </ul>
                                     </aside>
                                     <aside class="grid_2">Showing 8 Entries</aside>
                                 </div>




                             </div>
                         </section>
                     </div>
                     <div class="col-md-6">
                         <section class="section">
                             <div class="sectionhead">
                                 <h4>Make Payment - Edit</h4>

                                 <ul class="actions actions--centered">
                                     <li class="droplink"><a href="javascript:void(0)" class="button small green" title="Edit"><span class="ink animate"></span><i class="ion-android-more-horizontal icon"></i></a>
                                         <div class="dropwrap">
                                             <ul class="linksvertical">
                                                 <li><a href="javascript:void(0)" class="button small green" title="" onclick="">Discard</a></li>

                                             </ul>
                                         </div>
                                     </li>
                                 </ul>


                             </div>
                             <div class="sectionbody space">
                                 <form class="web_form" action="">
                                     <div class="row">
                                         <div class="col-md-12">
                                             <div class="field-set">
                                                 <div class="caption-wraper"><label class="field_label">Name</label></div>
                                                 <div class="field-wraper">
                                                     <div class="field_cover"><input type="text" title="Make Payment" placeholder="Make Payment" value=""> </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>

                                     <div class="row">
                                         <div class="col-md-12">
                                             <div class="field-set">
                                                 <div class="caption-wraper d-flex justify-content-between"><label class="field_label">Body </label> <a href="javascript:;" class="ml-2"><i class="fa fa-undo"></i></a></div>
                                                 <div class="field-wraper">
                                                     <div class="field_cover">
                                                         <textarea name="" id="" cols="30" rows="10"></textarea> </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>

                                     <div class="row">
                                         <div class="col-md-12">
                                             <div class="field-set">
                                                 <div class="caption-wraper"><label class="field_label">Replacements Variables (Click to Copy)</label></div>
                                                 <div class="field-wraper">
                                                     <div class="field_cover">


                                                         <ul class="list-group">
                                                             <li class="list-group-item"><span>Name of User</span>

                                                                 <span class="badge badge-secondary" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="Copied to clipboard">name</span>
                                                             </li>
                                                             <li class="list-group-item"><span>OTP</span>


                                                                 <span class="badge badge-secondary" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="Copied to clipboard">otp</span></li>


                                                         </ul>


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
                                                     <div class="field_cover"><input title="" type="submit" name="btn_submit" value="Save "></div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>



                                 </form>

                             </div>
                         </section>
                     </div>
                 </div>

                 <br>
                 <br><br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br><br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br><br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>
                 <br>


             </div>
         </div>
     </div>
 </div>
 <script>
     // jQuery
     $('[name=tags]').tagify();

     $(function() {
         $('[data-toggle="tooltip"]').tooltip()
     })
 </script>

 <script type="text/javascript">
     $(function() {
         var options = {
             placeholderCss: {
                 'background-color': '#ff8'
             },
             hintCss: {
                 'background-color': '#646464'
             },
             onChange: function(cEl) {
                 console.log('onChange');
             },
             complete: function(cEl) {
                 console.log('complete');
             },
             isAllowed: function(cEl, hint, target) {
                 // Be carefull if you test some ul/ol elements here.
                 // Sometimes ul/ols are dynamically generated and so they have not some attributes as natural ul/ols.
                 // Be careful also if the hint is not visible. It has only display none so it is at the previouse place where it was before(excluding first moves before showing).
                 if (target.data('module') === 'c' && cEl.data('module') !== 'c') {
                     hint.css('background-color', '#ff9999');
                     return false;
                 } else {
                     hint.css('background-color', '#99ff99');
                     return true;
                 }
             },
             opener: {
                 active: true,
                 as: 'html', // if as is not set plugin uses background image
                 close: '<i class="fa fa-minus c3"></i>', // or 'fa-minus c3',  // or './imgs/Remove2.png',
                 open: '<i class="fa fa-plus"></i>', // or 'fa-plus',  // or'./imgs/Add2.png',
                 openerCss: {
                     'display': 'inline-block',
                     //'width': '18px', 'height': '18px',
                     'float': 'left',
                     'margin-left': '-35px',
                     'margin-right': '5px',
                     //'background-position': 'center center', 'background-repeat': 'no-repeat',
                     'font-size': '1.1em'
                 }
             },
             ignoreClass: 'clickable'
         };
         var optionsPlus = {
             insertZonePlus: true,
             placeholderCss: {
                 'background-color': '#e5f5ff'
             },
             hintCss: {
                 'background-color': '#6dc5ff'
             },
             opener: {
                 active: true,
                 as: 'html', // if as is not set plugin uses background image
                 close: '<i class="fa fa-minus c3"></i>',
                 open: '<i class="fa fa-plus"></i>',
                 openerCss: {
                     'display': 'inline-block',
                     //'float': 'left',
                     //'margin-left': '-35px',
                     'margin-right': '0',
                     'position': 'absolute',
                     'left': '10px',
                     'top': '15px',
                     'font-size': '12px'
                 }
             }
         };

         $('#sorting-categories').sortableLists(optionsPlus);

         $('#toArrBtn').on('click', function() {
             console.log($('#sTree2').sortableListsToArray());
         });
         $('#toHierBtn').on('click', function() {
             console.log($('#sTree2').sortableListsToHierarchy());
         });
         $('#toStrBtn').on('click', function() {
             console.log($('#sTree2').sortableListsToString());
         });
         $('.descPicture').on('click', function(e) {
             $(this).toggleClass('descPictureClose');
         });

         $('.clickable').on('click', function(e) {
             alert('Click works fine! IgnoreClass stopped onDragStart event.');
         });

         /* Scrolling anchors */
         $('#toPictureAnch').on('mousedown', function(e) {
             scrollToAnch('pictureAnch');
             return false;
         });
         $('#toBaseElementAnch').on('mousedown', function(e) {
             scrollToAnch('baseElementAnch');
             return false;
         });
         $('#toBaseElementAnch2').on('mousedown', function(e) {
             scrollToAnch('baseElementAnch');
             return false;
         });
         $('#toCssPatternAnch').on('mousedown', function(e) {
             scrollToAnch('cssPatternAnch');
             return false;
         });



     });
 </script>