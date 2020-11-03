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
                    <ul id="faqCollapseParent">
                        <?php 
                        $i=0;
                        foreach ($faqCat['faqs'] as $faqId => $faq) { ?>
                            <li>
                                <a class="faq_trigger collapsed" href="#faqCollapse<?php echo $faqId; ?>"  data-toggle="collapse" aria-expanded="<?php echo ($i == 0 ? 'true' : 'false'); ?>">
                                    <?php echo $faq['faq_title']; ?>
                                </a>
                                <p class="collapse <?php echo ($i == 0 ? 'show' : ''); ?>" id="faqCollapse<?php echo $faqId; ?>" data-parent="#faqCollapseParent">
                                    <span class="faq_data">
                                    <?php echo FatUtility::decodeHtmlEntities($faq['faq_content']); ?>
                                    </span>
                                </p>
                            </li>
                        <?php $i++; } ?>
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