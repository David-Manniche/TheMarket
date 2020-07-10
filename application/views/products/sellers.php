<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div id="body" class="body">
    <div class="bg-second pt-3 pb-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="prod-info">
                        <div class="prod-info__left">
                            <div class="product-avtar"><a title="<?php echo $product['selprod_title'];?>" href="<?php echo UrlHelper::generateUrl('products', 'view', array($product['selprod_id']));?>"><img alt="" src="<?php echo UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($product['product_id'], "SMALL", $product['selprod_id'], 0, $siteLangId), CONF_WEBROOT_URL), CONF_IMG_CACHE_TIME, '.jpg'); ?>"></a>
                            </div>
                        </div>
                        <div class="prod-info__right">
                            <div class="avtar__info">
                                <h5><a title="<?php echo $product['selprod_title'];?>" href="<?php echo UrlHelper::generateUrl('products', 'view', array($product['selprod_id']));?>"><?php echo $product['selprod_title'];?></a></h5>
                                <?php if (round($product['prod_rating'])>0  && FatApp::getConfig("CONF_ALLOW_REVIEWS", FatUtility::VAR_INT, 0)) {
                                    ?> <div class="products__rating"><i class="icn"><svg class="svg">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#star-yellow" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#star-yellow"></use>
                            </svg> </i><span class="rate"><?php echo round($product['prod_rating'], 1); ?></span> </div> <?php
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
				<div class="col-md-4"></div>
        </div>
    </div>
	</div>

    <section class="section">
        <div class="container">
          <div class="section-head mb-0">
				<div class="section__heading">
					<h2 class="mb-0"><?php echo Labels::getLabel('LBL_All_Sellers', $siteLangId);?></h2>
				</div>
			</div>
			 

            <div class=""> <?php
            $arr_flds = array(
                'shop_name'    =>    Labels::getLabel('LBL_Seller', $siteLangId),
                'theprice'    =>    Labels::getLabel('LBL_Price', $siteLangId),
                'COD'    =>    Labels::getLabel('LBL_COD_AVAILABLE', $siteLangId),
                'viewDetails'    =>    '',
                'Action'    =>    '',

            );
            $tbl = new HtmlElement('table', array('class'=>'table table--justify'));
            $th = $tbl->appendElement('thead')->appendElement('tr', array('class' => ''));
            foreach ($arr_flds as $val) {
                $e = $th->appendElement('th', array(), $val);
            }

            $sr_no = 0;
            foreach ($product['moreSellersArr'] as $sn => $moresellers) {
                $sr_no++;

                $tr = $tbl->appendElement('tr', array('class' =>'' ));

                foreach ($arr_flds as $key => $val) {
                    $td = $tr->appendElement('td');
                    switch ($key) {
                        case 'shop_name':
                        $txt = '<div class=""><h6 class="ftshops_name"><a title="'.$moresellers[$key].'" href="'.UrlHelper::generateUrl('shops', 'view', array($moresellers['shop_id'])).'">';
                        $txt .= $moresellers[$key];
                        $txt .= '</a></h6><a href="'.UrlHelper::generateUrl('shops', 'view', array($moresellers['shop_id'])).'"><div class="">'.$moresellers['shop_state_name'].",".$moresellers['shop_country_name'].'</div></a></div>';
                        if (isset($product['rating'][$moresellers['selprod_user_id']]) && $product['rating'][$moresellers['selprod_user_id']]>0) {
                            $txt.='<div class="products-reviews"><span class="rate"><i class="icn"><svg class="svg"> <use xlink:href="'.CONF_WEBROOT_URL.'images/retina/sprite.svg#star-yellow" href="'.CONF_WEBROOT_URL.'images/retina/sprite.svg#star-yellow"></use></svg> </i>'.round($product['rating'][$moresellers['selprod_user_id']], 1).'</span> </div>';
                            }
                        $td->appendElement('plaintext', array(), $txt, true);
                            break;

                        case 'theprice':
                         $txt = ' <div class="products item-yk"><div class="products__price">'.CommonHelper::displayMoneyFormat($moresellers['theprice']);
                              if ($moresellers['special_price_found']) {
                                  $txt.='  <span class="products__price_old">'.CommonHelper::displayMoneyFormat($moresellers['selprod_price']).'</span>
                              <div class="product_off">'.CommonHelper::showProductDiscountedText($moresellers, $siteLangId).'</div>';
                              }
                              $txt .='</div></div>';
                              $td->appendElement('plaintext', array(), $txt, true);
                            break;
                        case 'COD':
                            $codAvailableTxt = Labels::getLabel('LBL_N/A', $siteLangId);;
                                if (!empty($product['cod'][$moresellers['selprod_user_id']]) && $product['cod'][$moresellers['selprod_user_id']]) {
                                    $codAvailableTxt = Labels::getLabel('LBL_Cash_on_delivery_available', $siteLangId);
                                }
                              $td->appendElement('plaintext', array(), $codAvailableTxt, true);
                            break;
                        case 'viewDetails':
                                $td->appendElement(
                                    'a',
                                    array('href'=>UrlHelper::generateUrl('products', 'view', array($moresellers['selprod_id'])), 'class'=>'link--arrow','title'=>Labels::getLabel('LBL_View_Details', $siteLangId),true),
                                    Labels::getLabel('LBL_View_Details', $siteLangId),
                                    true
                                );
                            break;

                        case 'Action':
                            if (date('Y-m-d', strtotime($moresellers['selprod_available_from'])) <=  FatDate::nowInTimezone(FatApp::getConfig('CONF_TIMEZONE'), 'Y-m-d')) {
                                $txt ='<div> <a data-id="'.$moresellers['selprod_id'].'" data-min-qty="'.$moresellers['selprod_min_order_qty'].'"  href="javascript:void(0)" class="btn btn--primary btn--sm ripplelink block-on-mobile btnProductBuy--js">  '.Labels::getLabel('LBL_Buy_Now', $siteLangId).'</a> <a data-id="'.$moresellers['selprod_id'].'" data-min-qty="'.$moresellers['selprod_min_order_qty'].'"  href="javascript:void(0)" class="btn btn-outline-primary btn--sm ripplelink block-on-mobile btnAddToCart--js">  '.Labels::getLabel('LBL_Add_To_Cart', $siteLangId).'</a> </div>';
                            } else {
                                $txt = str_replace('{available-date}', FatDate::Format($moresellers['selprod_available_from']), Labels::getLabel('LBL_This_item_will_be_available_from_{available-date}', $siteLangId));
                            }
                            $td->appendElement('plaintext', array(), $txt, true);

                            break;

                        default:
                            $td->appendElement('plaintext', array(), $moresellers[$key], true);
                            break;
                    }
                }
            }
            echo $tbl->getHtml(); ?> </div>
        </div>
    </section>
</div>
