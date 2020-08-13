<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<script>
    events.viewContent();
</script>

<div id="body" class="body" role="main">
  <!--slider[-->
<?php if (isset($slides) && count($slides)) {
    $this->includeTemplate('_partial/homePageSlides.php', array( 'slides' =>$slides, 'siteLangId' => $siteLangId ), false);
} ?>
  <!--]-->
<?php
/* Product Layout1[ */
if (count($sponsoredProds)>0) {
    $this->includeTemplate('_partial/collection/sponsored-products.php', array( 'products' => $sponsoredProds, 'siteLangId' => $siteLangId ), false);
}

echo FatUtility::decodeHtmlEntities($productLayout1);
echo FatUtility::decodeHtmlEntities($homePageFirstLayout);

/* Top Banner Layout[ */
if (isset($banners['Home_Page_Top_Banner'])) {
    $this->includeTemplate('_partial/banners/home-banner-first-layout.php', array( 'bannerLayout1' => $banners['Home_Page_Top_Banner'], 'siteLangId' => $siteLangId ), false);
}
/* ] */

/* Product Layout2[ */
echo FatUtility::decodeHtmlEntities($homePageProdLayout2);

/* Bottom Banner Layout[ */
if (isset($banners['Home_Page_Bottom_Banner'])) {
    $this->includeTemplate('_partial/banners/home-banner-second-layout.php', array( 'bannerLayout1' => $banners['Home_Page_Bottom_Banner'], 'siteLangId' => $siteLangId ), false);
}
/* ] */

/* Shop Layout1[ */
echo FatUtility::decodeHtmlEntities($homePageShopLayout1);

if (count($sponsoredShops) > 0) {
    $this->includeTemplate('_partial/collection/sponsored-shops.php', array( 'sponsoredShops' => $sponsoredShops, 'siteLangId' => $siteLangId ,'action'=>$action), false);
}
/* ] */

echo FatUtility::decodeHtmlEntities($homePageFooterLayout);

?>
</div>
