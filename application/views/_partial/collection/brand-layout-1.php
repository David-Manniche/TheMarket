<?php if (isset($collection['brands']) && count($collection['brands']) > 0) { ?>
    <section class="section bg-light">
        <div class="container">
            <div class="section-head section--head--center">
                <?php echo ($collection['collection_name'] != '') ? ' <div class="section__heading"><h2>' . $collection['collection_name'] . '</h2></div>' : ''; ?>

                <?php if ($collection['totBrands'] > Collections::LIMIT_BRAND_LAYOUT1) { ?>
                    <div class="section__action"> <a href="<?php echo UrlHelper::generateUrl('Collections', 'View', array($collection['collection_id'])); ?>" class="link"><?php echo Labels::getLabel('LBL_View_More', $siteLangId); ?></a> </div>
                <?php } ?>
            </div>
            <div class="top-brand-list">
                <ul>
                <?php $i = 0;
                foreach ($collection['brands'] as $brand) { ?>
                    <li> <a href="<?php echo UrlHelper::generateUrl('brands', 'View', array($brand['brand_id'])); ?>">
                <!--<div class="brands-img">
                    <img src="<?php echo UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('Image', 'brandImage', array($brand['brand_id'], $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg'); ?>" data-ratio="1:1 (600x600)" alt="<?php echo $brand['brand_name']; ?>" title="<?php echo $brand['brand_name']; ?>">
                </div>-->
                <div class="brands-logo">
                    <?php
                    $fileData = AttachedFile::getAttachment(AttachedFile::FILETYPE_BRAND_LOGO, $brand['brand_id'], 0, 0, false);
                    $aspectRatioArr = AttachedFile::getRatioTypeArray($siteLangId);
                    ?>
                    <img <?php if ($fileData['afile_aspect_ratio'] > 0) { ?> data-ratio= "<?php echo $aspectRatioArr[$fileData['afile_aspect_ratio']]; ?>" <?php } ?> src="<?php echo UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'brand', array($brand['brand_id'], $siteLangId, 'COLLECTION_PAGE')), CONF_IMG_CACHE_TIME, '.jpg'); ?>" alt="<?php echo (!empty($fileData['afile_attribute_alt'])) ? $fileData['afile_attribute_alt'] : $brand['brand_name'];?>" title="<?php echo (!empty($fileData['afile_attribute_alt'])) ? $fileData['afile_attribute_alt'] : $brand['brand_name'];?>">
                </div> </a> 
                    </li>
                    <?php $i++;
                    /* if($i==Collections::COLLECTION_LAYOUT5_LIMIT) break; */
                } ?>
                </ul>
            </div>
        </div>
    </section>
<?php } ?>