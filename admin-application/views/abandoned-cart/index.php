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
                                <h5><?php echo Labels::getLabel('LBL_Abandoned_Cart',$adminLangId); ?> </h5>
                                <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                            </div>
						</div>
					</div>
					<section class="section searchform_filter">
						<div class="sectionhead">
							<h4> <?php echo Labels::getLabel('LBL_Search...',$adminLangId); ?></h4>
						</div>
						<div class="sectionbody space togglewrap" style="display:none;">
							<?php 
								$frmSearch->setFormTagAttribute ( 'onsubmit', 'searchAbandonedCart(this,1); return(false);');
								$frmSearch->setFormTagAttribute ( 'class', 'web_form' );					
								$frmSearch->developerTags['colClassPrefix'] = 'col-md-';							
								$frmSearch->developerTags['fld_default_col'] = 12;

								$buyerFld = $frmSearch->getField('user_name');
								$buyerFld->developerTags['col'] = 4;
                                
                                $sellerProductFld = $frmSearch->getField('seller_product');
								$sellerProductFld->developerTags['col'] = 4;
                                
                                //$actionFld = $frmSearch->getField('abandonedcart_action');
								//$actionFld->developerTags['col'] = 4;
                                
                                $dateFromFld = $frmSearch->getField('date_from');
								$dateFromFld->setFieldTagAttribute('class','field--calender');
								$dateFromFld->developerTags['col'] = 4;

								$dateToFld = $frmSearch->getField('date_to');
								$dateToFld->setFieldTagAttribute('class','field--calender');
								$dateToFld->developerTags['col'] = 4;
   
								$submitBtnFld = $frmSearch->getField('btn_submit');
								$submitBtnFld->setFieldTagAttribute('class','btn--block');
								$submitBtnFld->developerTags['col'] = 4;

								$btn_clear = $frmSearch->getField('btn_clear');
								$btn_clear->addFieldTagAttribute('onclick', 'clearAbandonedCartSearch()');
								
                                echo  $frmSearch->getFormHtml();
							?>
						</div>
					</section>
                   
                    <section class="section">
						<div class="sectionhead">
							<h4><?php echo Labels::getLabel('LBL_Abandoned_Cart_List',$adminLangId); ?> </h4>
                            <?php
                                $ul = new HtmlElement("ul", array("class"=>"actions actions--centered"));
                                $li = $ul->appendElement("li", array('class'=>'droplink'));

                                $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                                $innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
                                $innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));

                                $innerLi=$innerUl->appendElement('li');
                                $innerLi->appendElement('a', array('href'=> commonHelper::generateUrl('AbandonedCart', 'products') ,'class'=>'button small green redirect--js','title'=>Labels::getLabel('LBL_View_By_Product', $adminLangId)), Labels::getLabel('LBL_View_By_Product', $adminLangId), true);
                           
                                echo $ul->getHtml();
                            ?>
						</div>
						<div class="sectionbody">
							<div class="tablewrap"> 
                                <div class="tabs_nav_container responsive flat">
                                    <ul class="tabs_nav tabs_nav-js">
                                        <li>
                                            <a href="javascript:void(0);" onclick="submitForm(1);">
                                                <?php echo Labels::getLabel('LBL_Abandoned_Cart', $adminLangId); ?>                            
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="submitForm(2);">
                                                <?php echo Labels::getLabel('LBL_Removed_From_Cart', $adminLangId); ?>                               
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="submitForm(3);">
                                                <?php echo Labels::getLabel('LBL_Cart_Recoverd', $adminLangId); ?>                                
                                            </a>
                                        </li>
                                    </ul>
                                </div>                                    
								<div id="abandonedCartListing">
									<?php echo Labels::getLabel('LBL_Processing...',$adminLangId); ?>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>