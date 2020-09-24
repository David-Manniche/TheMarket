<?php $recordFound = false; ?>
<?php if (!empty($suggestions['tags'])) {
    $recordFound = true; ?>
    <ul class="text-suggestions">
        <?php foreach ($suggestions['tags'] as $tags) { ?>
            <li class=""><a class="" href="javascript:void(0)" onclick="searchTags(this)" data-txt="<?php echo $tags['tag_name']; ?>"><span class=""><?php echo str_ireplace($keyword, "<b>$keyword</b>", $tags['tag_name']); ?></span></a></li>
        <?php } ?>
    </ul>
<?php } else if (!empty($suggestions['products'])) {
    $recordFound = true; ?>
    <ul class="text-suggestions">
        <?php foreach ($suggestions['products'] as $product) { ?>
            <li class=""><a class="" href="<?php echo UrlHelper::generateUrl('Products', 'view', [$product['selprod_id']]); ?>"><span class=""><?php echo str_ireplace($keyword, "<b>$keyword</b>", $product['selprod_title']); ?></span></a></li>
        <?php } ?>
    </ul>
<?php } ?>

<div class="matched">
    <?php if (!empty($suggestions['brands'])) {
        $recordFound = true; ?>
        <h6 class="suggestions-title"><?php echo Labels::getLabel('LBL_Matching_Brands', $siteLangId); ?></h6>
        <ul class="text-suggestions matched-brands">
            <?php
            foreach ($suggestions['brands'] as $brandId => $brandName) { ?>
                <li class=""><a class="" href="<?php echo UrlHelper::generateUrl('Brands', 'view', [$brandId]); ?>"><span class=""><?php echo str_ireplace($keyword, "<b>$keyword</b>", $brandName); ?></span></a></li>
            <?php
            } ?>
        </ul>
    <?php } ?>
    <?php if (!empty($suggestions['categories'])) {
        $recordFound = true; ?>
        <h6 class="suggestions-title"><?php echo Labels::getLabel('LBL_Matching_Categories', $siteLangId); ?></h6>
        <ul class="text-suggestions matched-category">
            <?php foreach ($suggestions['categories'] as $catId => $categoryName) { ?>
                <li class=""><a class="" href="<?php echo UrlHelper::generateUrl('Category', 'view', [$catId]); ?>"><span class=""><?php echo str_ireplace($keyword, "<b>$keyword</b>", $categoryName); ?></span></a></li>
            <?php  } ?>
        </ul>
    <?php } ?>
</div>
<?php if (false == $recordFound) {
    $message = Labels::getLabel('LBL_No_Records_Found', $siteLangId);
    $this->includeTemplate('_partial/no-record-found.php', array('siteLangId' => $siteLangId, 'message' => $message));
} ?>