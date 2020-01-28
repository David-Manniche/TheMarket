<?php  defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="row justify-content-between align-items-center mb-4">
    <div class="col-auto">
        <h5><?php echo Labels::getLabel('LBL_Products_That_I_Love', $siteLangId);?></h5>
    </div>
    <?php $this->includeTemplate('account/wishListItemsActions.php', array('isWishList' => false, 'siteLangId' => $siteLangId, 'wishListRow' => $wishListRow)); ?>
</div>
<form method="post" name="favtlistForm" id="favtlistForm">
    <div id="favListItems" class="row"></div>
</form>

<div id="loadMoreBtnDiv"></div>

<script type="text/javascript">
    $("document").ready(function() {
        searchFavouriteListItems();
    });
</script>