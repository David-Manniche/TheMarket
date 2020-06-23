<?php  defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="row justify-content-between align-items-center mb-4">
    <div class="col mb-3 mb-md-0">
        <h6 class="cards-title m-0">
            <?php echo (isset($wishListRow['uwlist_default']) && $wishListRow['uwlist_default'] == 1) ? Labels::getLabel('LBL_Default_list', $siteLangId) : $wishListRow['uwlist_title']; ?>
            <input type="hidden" name="uwlist_id" value="<?php echo $wishListRow['uwlist_id']; ?>" />
        </h6>
    </div>
    <?php $this->includeTemplate('account/wishListItemsActions.php', array('isWishList' => true, 'siteLangId' => $siteLangId, 'wishListRow' => $wishListRow)); ?>
</div>
<form method="post" name="wishlistForm" id="wishlistForm">
    <input type="hidden" name="uwlist_id" value="<?php echo $wishListRow['uwlist_id']; ?>" />
    <div id="favListItems" class="row"></div>
</form>

<div id="loadMoreBtnDiv"></div>
<!--<a href="javascript:void(0)" onClick="goToWishListItemSearchPage(2);" class="loadmore loadmore--gray text--uppercase">Load More</a>-->

<script type="text/javascript">
    $("document").ready(function() {
        searchWishListItems(<?php echo $wishListRow['uwlist_id']; ?>);
    });
</script>