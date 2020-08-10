<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class='page'>
    <div class='container container-fluid'>
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="page__title">
                    <div class="row">
                        <div class="col--first col-lg-6">
                            <span class="page__icon">
                                <i class="ion-android-star"></i></span>
                            <h5><?php echo Labels::getLabel('LBL_Manage_Collections', $adminLangId); ?> </h5> <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                        </div>
                    </div>
                </div>
                <div class="tabs_nav_container vertical">
                    <ul class="tabs_nav outerul"> <?php
                    foreach ($layouts as $type => $tabName) { ?>
                        <li>
                            <a href="javascript:void(0)"><?php echo $tabName; ?></a>
                            <a href="javascript:void(0)" onclick="addCollectionForm(0)" title="<?php echo Labels::getLabel('LBL_Add_Collection', $adminLangId); ?>" class="btn-clean btn-sm btn-icon btn-secondary "><i class="fas fa-plus"></i></a>
                            <a href="javascript:void(0)" onclick="collectionLayouts()" title="Banner Layouts Instructions" class="btn-clean btn-sm btn-icon btn-secondary "><i class="fas fa-file-image"></i></a>
                        </li> 
                    <?php }?>
                    </ul>
                    <div id="frmBlock" class="tabs_panel_wrap">
                        <section class="section searchform_filter">
                            <div class="sectionhead">
                                <h4> <?php echo Labels::getLabel('LBL_Search...', $adminLangId); ?></h4>
                            </div>
                            <div class="sectionbody space togglewrap" style="display:none;">
                                <?php
                                    $search->setFormTagAttribute('onsubmit', 'searchCollection(this); return(false);');
                                    $search->setFormTagAttribute('class', 'web_form');
                                    $search->setFormTagAttribute('id', 'frmSearch');
                                    $search->developerTags['colClassPrefix'] = 'col-md-';
                                    $search->developerTags['fld_default_col'] = 6;
                                    $frmId = $search->getFormTagAttribute('id');
                                    $fld = $search->getField('collection_type');
                                    $fld->addFieldTagAttribute('onChange', 'getCollectionTypeLayout("'.$frmId.'",this.value,1); ');
                                    $search->getField('keyword')->addFieldtagAttribute('class', 'search-input');
                                    $search->getField('btn_clear')->addFieldtagAttribute('onclick', 'clearSearch();');

                                    echo  $search->getFormHtml();
                                ?>
                            </div>
                        </section>
                        <section class="section">
                            <div class="sectionhead">
                                <h4><?php echo Labels::getLabel('Lbl_Collection_Listing', $adminLangId);?></h4>
                                <?php
                                $data = [
                                    'statusButtons' => $canEdit,
                                    'deleteButton' => $canEdit,
                                    'adminLangId' => $adminLangId
                                ];
                                
                                if ($canEdit) {
                                    $data['otherButtons'][] = [
                                        'attr' => [
                                            'href' => 'javascript:void(0)',
                                            'onclick' => 'addCollectionForm(0)',
                                            'title' => Labels::getLabel('Lbl_Add_Collection', $adminLangId)
                                        ],
                                        'label' => '<i class="fas fa-plus"></i>'
                                    ];
                                }

                                $data['otherButtons'][] = [
                                    'attr' => [
                                        'href' => 'javascript:void(0)',
                                        'onclick' => 'collectionLayouts()',
                                        'title' => Labels::getLabel('Lbl_Banner_Layouts_Instructions', $adminLangId)
                                    ],
                                    'label' => '<i class="fas fa-file-image"></i>'
                                ];
            
                                $this->includeTemplate('_partial/action-buttons.php', $data, false);
                                ?>
                            </div>
                            <div class="sectionbody">
                                <div class="tablewrap">
                                    <div id="listing"> <?php echo Labels::getLabel('Lbl_Processing', $adminLangId);?>....</div>
                                </div>
                            </div>
                        </section>
                        <div class="tabs_panel"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
