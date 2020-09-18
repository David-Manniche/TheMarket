<?php defined('SYSTEM_INIT') or die('Invalid Usage');
	$keywordFld = $headerSrchFrm->getField('keyword');
	$submitFld = $headerSrchFrm->getField('btnSiteSrchSubmit');
	$submitFld->setFieldTagAttribute('class','search--btn submit--js');
	$keywordFld->setFieldTagAttribute('class','search--keyword search--keyword--js no--focus');
	$keywordFld->setFieldTagAttribute('placeholder',Labels::getLabel('LBL_I_am_looking_for...',$siteLangId));
	/* $keywordFld->setFieldTagAttribute('autofocus','autofocus'); */
	$keywordFld->setFieldTagAttribute('id','header_search_keyword');
	$keywordFld->setFieldTagAttribute('onkeyup','animation(this)');
	$selectFld = $headerSrchFrm->getField('category');
	$selectFld->setFieldTagAttribute('id','searched_category');
	/* CommonHelper::printArray($categoriesArr); die; */
	/*
	$selectFld->setFieldTagAttribute('onChange','setSelectedCatValue()'); */
?>

<div class="main-search">
    <a href="javascript:void(0)" class="toggle--search" data-trigger="form--search-popup"> <span class="icn"><svg
                class="svg">
                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#magnifying"
                    href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#magnifying"></use>
            </svg></span></a>
    <div class="form--search form--search-popup" id="form--search-popup"
        data-close-on-click-outside="form--search-popup">
        <?php echo $headerSrchFrm->getFormTag(); ?>
        <div class="dropdown dropdown-select">

            <span class="select__value dropdown-toggle " id="selected__value-js" data-display="static"
                data-toggle="dropdown"> <?php echo Labels::getLabel('LBL_All',$siteLangId); ?></span>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-anim">
                <div class="scroll-y" data-simplebar>
                    <ul class="nav nav-block">
                        <li class="nav__item">
                            <h6 class="dropdown-header expand-heading">
                                <?php echo Labels::getLabel('LBL_Search_Items',$siteLangId); ?></h6>
                        </li>
                        <li class="nav__item"><a class="dropdown-item nav__link" id="category--js-0"
                                href="javascript:void(0);"
                                onclick="setSelectedCatValue(0)"><?php echo Labels::getLabel('LBL_All',$siteLangId); ?></a>
                        </li>
                        <?php foreach($categoriesArr as $catkey => $catval) { ?>
                        <li class="nav__item"><a class="dropdown-item nav__link"
                                id="category--js-<?php echo $catkey; ?>" href="javascript:void(0);"
                                onclick="setSelectedCatValue('<?php echo $catkey; ?>')"><?php echo $catval; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="main-search__field ui-front"><?php echo $headerSrchFrm->getFieldHTML('keyword'); ?>
            <a href="javascript:void(0)" class="close-layer"></a>
            <div class="search-suggestions">
                <ul class="text-suggestions">
                    <li class=""><a class="" href="/uae-en/search?q=smart watches for men"><span class="">smart
                                <b>watch</b>es for men</span></a></li>
                    <li class=""><a class="" href="/uae-en/search?q=casio watches for men"><span class="">casio
                                <b>watch</b>es for men</span></a></li>
                    <li class=""><a class="" href="/uae-en/search?q=smart watches for women"><span class="">smart
                                <b>watch</b>es for women</span></a></li>
                    <li class=""><a class="" href="/uae-en/search?q=casio watches women"><span class="">casio
                                <b>watch</b>es women</span></a></li>
                    <li class=""><a class="" href="/uae-en/search?q=casio g shock watches"><span class="">casio g shock
                                <b>watch</b>es</span></a></li>
                    <li class=""><a class="" href="/uae-en/search?q=naviforce watches men"><span class="">naviforce
                                <b>watch</b>es men</span></a></li>
                    <li class=""><a class="" href="/uae-en/search?q=seiko watches men"><span class="">seiko
                                <b>watch</b>es
                                men</span></a></li>
                    <li class=""><a class="" href="/uae-en/search?q=swatch watches women"><span class="">swatch
                                <b>watch</b>es women</span></a></li>
                </ul>
                <div class="matched">
                    <h6 class="suggestions-title">Matching Brands</h6>
                    <ul class="text-suggestions matched-brands">
                        <li class=""><a class="" href="/uae-en/fitbit"><span class="">fitbit</span></a></li>
                        <li class=""><a class="" href="/uae-en/debeer_watch_bands"><span class="">deBeer <b>Watch</b>
                                    Bands</span></a></li>
                        <li class=""><a class="" href="/uae-en/watch"><span class=""><b>Watch</b></span></a></li>
                        <li class=""><a class="" href="/uae-en/azan_watch"><span class="">Azan <b>Watch</b></span></a>
                        </li>
                        <li class=""><a class="" href="/uae-en/watchover_voodoo"><span class=""><b>Watch</b>over
                                    Voodoo</span></a></li>
                    </ul>
                    <h6 class="suggestions-title">Matching categories</h6>
                    <ul class="text-suggestions matched-category">
                        <li class=""><a class=""
                                href="/uae-en/fashion/women-31229/womens-watches/womens-pocket-watches"><span
                                    class="">Women s Pocket <b>Watch</b>es</span></a></li>
                        <li class=""><a class="" href="/uae-en/fashion/boys-31221/boys-watches"><span class="">Boys
                                    <b>Watch</b>es</span></a></li>
                        <li class=""><a class="" href="/uae-en/fashion/men-31225/mens-watches/wrist-watches-21876"><span
                                    class="">Men s Wrist <b>Watch</b>es</span></a></li>
                        <li class=""><a class="" href="/uae-en/fashion/women-31229/womens-watches"><span class="">Women
                                    s
                                    <b>Watch</b>es</span></a></li>
                        <li class=""><a class="" href="/uae-en/fashion/men-31225/mens-watches"><span class="">Men s
                                    <b>Watch</b>es</span></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <?php echo $headerSrchFrm->getFieldHTML('category'); ?>
        <?php echo $headerSrchFrm->getFieldHTML('btnSiteSrchSubmit'); ?>
        </form>
        <?php echo $headerSrchFrm->getExternalJS(); ?>
    </div>
</div>