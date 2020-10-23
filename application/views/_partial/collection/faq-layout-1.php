<?php if (isset($collection['faqs']) && count($collection['faqs']) > 0) { 
    $faqCategories = array();
    foreach ($collection['faqs'] as $faq) {
        $faqCategories[$faq['faqcat_id']]['faqcat_name'] = $faq['faqcat_name'];
        $faqCategories[$faq['faqcat_id']]['faqs'][$faq['faq_id']] = $faq;
    }  ?>
    <section class="section" role="faqs">
        <div class="container">
            <div class="section-head  section--head--center">
                <div class="section__heading">
                    <h2><?php echo Labels::getLabel('LBL_Frequently_Asked_Questions', $siteLangId); ?></h2>
                </div>
            </div>
            <div id="" class="tabs faqTabs--flat-js tabs-faqs justify-content-md-center">
                <ul>
                    <?php $count = 0;
                    foreach ($faqCategories as $faqCatId => $faqCat) { ?>
                        <li class="<?php echo ($count == 0) ? 'is-active' : '' ;?>">
                            <a href="#tb-<?php echo $faqCatId; ?>"><?php echo $faqCat['faqcat_name']; ?></a>
                        </li>
                    <?php $count++;
                    } ?>
                    <?php if (count($faqCategories) > Collections::LIMIT_FAQ_LAYOUT1) { ?>
                        <li class=""><a href="<?php echo UrlHelper::generateUrl('custom', 'faq'); ?>"><?php echo Labels::getLabel('LBL_View_All', $siteLangId); ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <?php foreach ($faqCategories as $faqCatId => $faqCat) { ?>
            <div id="tb-<?php echo $faqCatId; ?>" class="tabs-content tabs-content-home--js">
                <div class="list-faqs" data-contentloaded="0">
                    <ul>
                        <?php foreach ($faqCat['faqs'] as $faqId => $faq) { ?>
                        <li>
                            <h5><?php echo $faq['faq_title']; ?></h5>
                            <p><span class="lessText"><?php echo CommonHelper::truncateCharacters($faq['faq_content'], 85, '', '', true); ?></span> <?php if (strlen($faq['faq_content']) > 85) {
                            ?> <span class="moreText hidden"><?php echo FatUtility::decodeHtmlEntities($faq['faq_content']); ?></span> 
                            <a class="readMore link--arrow btn-link" href="javascript:void(0);"> <?php echo Labels::getLabel('Lbl_SHOW_MORE', $siteLangId) ; ?> </a></p> <?php
                            } ?>
                            <p></p>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
<?php } ?>
<script>
    var $linkMoreText = '<?php echo Labels::getLabel('Lbl_SHOW_MORE', $siteLangId); ?>';
    var $linkLessText = '<?php echo Labels::getLabel('Lbl_SHOW_LESS', $siteLangId); ?>';
</script>