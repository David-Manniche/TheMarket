<?php
if (isset($collections) && count($collections)) {
    /* blog listing design [ */
    foreach ($collections as $collection_id => $row) {
        if (isset($row['blogs']) && count($row['blogs'])) { ?>
            <section class="section ">
                <div class="container">
                    <div class="section-head">
                        <?php echo (isset($row['collection_name']) && $row['collection_name'] != '') ? ' <div class="section__heading"><h2>' . $row['collection_name'] . '</h2></div>' : ''; ?>

                        <?php if (isset($row['totBlogs']) && $row['totBlogs'] > Collections::LIMIT_BLOG_LAYOUT1) { ?>
                            <div class="section__action"> 
                                <a href="<?php echo CommonHelper::generateUrl('blog'); ?>" class="link">
                                    <?php echo Labels::getLabel('LBL_View_More', $siteLangId); ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>    
                    <div class="row">
                        <?php foreach ($row['blogs'] as $blog) { ?>
                            <div class="col-md-4 mb-4 mb-md-0">
                                <div class="post">
                                    <div class="post_media">
                                        <a href="<?php echo CommonHelper::generateUrl('Blog', 'postDetail', array($blog['post_id'])); ?>" class="animate-scale">
                                            <picture>
                                                <img data-ratio="16:9"
                                                src="<?php echo CommonHelper::generateFullUrl('Image', 'blogPostFront', array($blog['post_id'], $siteLangId, 'FEATURED')); ?>"
                                                alt="<?php echo $blog['post_title']; ?>"
                                                title="<?php echo $blog['post_title']; ?>">
                                            </picture>
                                        </a>
                                    </div>
                                    <div class="article-inner">
                                        <div class="blog_author">
                                            <span class="article__author"><?php echo $blog['post_author_name']; ?></span>
                                            <span class="article__date"><?php echo $blog['post_updated_on']; ?></span>
                                        </div>
                                        <h3 class="article-title">
                                            <a href="<?php echo CommonHelper::generateUrl('Blog', 'postDetail', array($blog['post_id'])); ?>">
                                                <span>
                                                <?php 
                                                    $title = !empty($blog['post_title']) ? $blog['post_title'] : $blog['post_identifier'];
                                                    echo mb_strimwidth($title, 0, applicationConstants::BLOG_TITLE_CHARACTER_LENGTH, '...'); 
                                                ?>
                                                </span>
                                            </a>
                                        </h3>
                                        <div class="article-des">
                                            <?php /* echo FatUtility::decodeHtmlEntities($blog['post_description']); */ ?>
                                        </div>                                     
                                    </div>
                                    <a class="readmore-button link" href="<?php echo CommonHelper::generateUrl('Blog', 'postDetail', array($blog['post_id'])); ?>"><?php echo Labels::getLabel('LBL_READ_MORE', $siteLangId); ?></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        <?php }
    }
}
