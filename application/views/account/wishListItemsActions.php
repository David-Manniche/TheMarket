<?php  defined('SYSTEM_INIT') or die('Invalid Usage.'); 

if (true == $isWishList) {
    $function = 'removeSelectedFromWishlist(' . $wishListRow['uwlist_id'] . ', event)';
} else {
    $function = 'removeSelectedFromFavtlist(event)';
}
?>
<div class="col text-right">
    <div class="action action--favs btn-group-scroll">
        <label class="checkbox checkbox-inline">
            <input type="checkbox" class='selectAll-js' onclick="selectAll($(this));"><i
                class="input-helper"></i>Select all
        </label>
        <?php if (true == $isWishList) { ?>
            <a title='<?php echo Labels::getLabel('LBL_Move_to_other_wishlist', $siteLangId); ?>'
                class="btn btn--primary btn--sm formActionBtn-js formActions-css"
                onclick="viewWishList(0,this,event, <?php echo !empty($wishListRow['uwlist_id']) ? $wishListRow['uwlist_id'] : 0; ?>);"
                href="javascript:void(0)">
                <i class="fa fa-heart"></i>&nbsp;&nbsp;<?php echo Labels::getLabel('LBL_Move', $siteLangId); ?>
            </a>
        <?php } ?>
        <a title='<?php echo Labels::getLabel('LBL_Move_to_cart', $siteLangId); ?>'
            class="btn btn--primary btn--sm formActionBtn-js formActions-css" onClick="addSelectedToCart(event, <?php echo ($isWishList ? 1 : 0); ?>);"
            href="javascript:void(0)">
            <i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;<?php echo Labels::getLabel('LBL_Cart', $siteLangId); ?>
        </a>
        <a title='<?php echo Labels::getLabel('LBL_Move_to_trash', $siteLangId); ?>'
            class="btn btn--primary btn--sm formActionBtn-js formActions-css"
            onClick="<?php echo $function; ?>"
            href="javascript:void(0)">
            <i class="fa fa-trash"></i>&nbsp;&nbsp;<?php echo Labels::getLabel('LBL_Delete', $siteLangId); ?>
        </a>
        <?php if (true == $isWishList) { ?>
            <a class="btn btn--primary btn--sm" onClick="searchWishList();" href="javascript:void(0)">
                <?php echo Labels::getLabel('LBL_Back', $siteLangId); ?>
            </a>
        <?php } ?>
    </div>
</div>