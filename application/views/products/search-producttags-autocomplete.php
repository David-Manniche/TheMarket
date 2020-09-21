    <ul class="text-suggestions">
        <?php if (!empty($suggestions['tags'])) {
            foreach ($suggestions['tags'] as $tags) { ?>
                <li class=""><a class="" href="javascript:void(0)" onclick="searchTags(this)" data-txt="<?php echo $tags['tag_name']; ?>"><span class=""><?php echo $tags['tag_name']; ?></span></a></li>
        <?php }
        } ?>
    </ul>
    <div class="matched">
        <?php if (!empty($suggestions['brands'])) { ?>
            <h6 class="suggestions-title"><?php echo Labels::getLabel('LBL_Matching_Brands', $siteLangId); ?></h6>
            <ul class="text-suggestions matched-brands">
                <?php
                foreach ($suggestions['brands'] as $brandId => $brandName) { ?>
                    <li class=""><a class="" href="<?php echo UrlHelper::generateUrl('Brands', 'view', [$brandId]); ?>"><span class=""><?php echo $brandName; ?></span></a></li>
                <?php
                } ?>
            </ul>
        <?php } ?>
        <?php if (!empty($suggestions['categories'])) { ?>
            <h6 class="suggestions-title"><?php echo Labels::getLabel('LBL_Matching_Categories', $siteLangId); ?></h6>
            <ul class="text-suggestions matched-category">
                <?php foreach ($suggestions['categories'] as $catId => $categoryName) { ?>
                    <li class=""><a class="" href="<?php echo UrlHelper::generateUrl('Category', 'view', [$catId]); ?>"><span class=""><?php echo $categoryName; ?></span></a></li>
                <?php  } ?>
            </ul>
        <?php } ?>
   