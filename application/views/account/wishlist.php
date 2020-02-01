<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/dashboardNavigation.php'); 

$label = Labels::getLabel("LBL_FAVORITES", $siteLangId);
if (0 < FatApp::getConfig('CONF_ADD_FAVORITES_TO_WISHLIST', FatUtility::VAR_INT, 1)) {
    $label = Labels::getLabel("LBL_WISHLIST", $siteLangId);
}
if (FatApp::getConfig('CONF_ADD_FAVORITES_TO_WISHLIST', FatUtility::VAR_INT, 1) == applicationConstants::NO) {
    $function = 'viewFavouriteItems()';
} else {
    $function = 'searchWishList()';
}
?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col"> <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title">
                    <?php echo $label;?>
                </h2>
            </div>
        </div>
        <div class="content-body">
            <div class="cards">
                <div class="cards-content p-4">
                    <div class="box__body">
                        <div class="tabs tabs--small tabs--scroll clearfix">
                            <ul>
                                <li class="is-active" id="tab-wishlist">
                                    <a onClick="<?php echo $function; ?>" href="javascript:void(0);">
                                        <?php echo $label; ?>
                                    </a>
                                </li>
                                <li id="tab-fav-shop"><a onClick="searchFavoriteShop();"
                                        href="javascript:void(0);"><?php echo Labels::getLabel('LBL_Shops', $siteLangId); ?></a>
                                </li>
                            </ul>
                        </div>
                        <div id="listingDiv" class="account-fav-listing"></div>
                    </div>
                    <div class="gap"></div>
                    <div id="loadMoreBtnDiv"></div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    <?php echo $function; ?>;
</script>
