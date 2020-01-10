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
                    <section class="section">
						<div class="sectionhead">
							<h4><?php echo Labels::getLabel('LBL_Abandoned_Cart_Products',$adminLangId); ?> </h4>
                            <?php
                                $ul = new HtmlElement("ul", array("class"=>"actions actions--centered"));
                                $li = $ul->appendElement("li", array('class'=>'droplink'));

                                $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                                $innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
                                $innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));

                                $innerLi=$innerUl->appendElement('li');
                                $innerLi->appendElement('a', array('href'=> commonHelper::generateUrl('AbandonedCart') ,'class'=>'button small green redirect--js','title'=>Labels::getLabel('LBL_View_By_Product', $adminLangId)), Labels::getLabel('LBL_Abandoned_Cart_List', $adminLangId), true);
                           
                                echo $ul->getHtml();
                            ?>
						</div>
						<div class="sectionbody">
							<div class="tablewrap">
								<div id="abandonedCartProducts">
									<?php echo Labels::getLabel('LBL_Processing...',$adminLangId); ?>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>