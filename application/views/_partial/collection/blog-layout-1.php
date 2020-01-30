<?php
if (isset($collections) && count($collections)) {
    /* blog listing design [ */
    foreach ($collections as $collection_id => $row) {
        if (isset($row['blogs']) && count($row['blogs'])) { ?>
            <section class="section bg-gray">
                <div class="container">
                    <div class="section-head">
                        <?php echo ($row['collection_name'] != '') ? ' <div class="section__heading"><h2>' . $row['collection_name'] . '</h2></div>' : ''; ?>

                        <?php if ($row['totBlogs'] > Collections::LIMIT_BLOG_LAYOUT1) { ?>
                            <div class="section__action"> 
                                <a href="<?php echo CommonHelper::generateUrl('Collections', 'View', array($row['collection_id'])); ?>" class="link">
                                    <?php echo Labels::getLabel('LBL_View_More', $siteLangId); ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <?php foreach ($row['blogs'] as $blog) { ?>
                            <div class="col-xl-3 col-lg-6 col-sm-6 column">
                                <div class="blogs top-categories">
                                    <div class="blog-img">
                                        <img data-ratio="16:9"
                                            src="<?php echo CommonHelper::generateFullUrl('Image', 'blogPostFront', array($blog['post_id'], $siteLangId, '')); ?>"
                                            alt="<?php echo $blog['post_title']; ?>"
                                            title="<?php echo $blog['post_title']; ?>">
                                    </div>
                                    <div class="cat-tittle">
                                        <a href="<?php echo CommonHelper::generateUrl('Blog', 'postDetail', array($blog['post_id'])); ?>">
                                            <?php echo $blog['post_title']; ?>
                                        </a>
                                    </div>
                                    <div class="cat-list">
                                        <?php echo $blog['post_short_description']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
    <?php }
    }
}
