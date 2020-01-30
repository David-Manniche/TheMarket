<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box--scroller">
    <ul class="columlist links--vertical" id="collection-blog">
        <?php
        if ($collectionBlogs) {
            $lis = '';
            foreach ($collectionBlogs as $blog) {
                $lis .= '<li id="collection-blogs' . $blog['post_id'] . '"><span class="left"><a href="javascript:void(0)" title="Remove" onClick="removeCollectionBlog(' .  $collectionId  . ',' . $blog['post_id'] . ');"><i class="icon ion-close" data-blog-id="' . $blog['post_id'] . '"></i></a></span>';
                $lis .= '<span>' . $blog['post_title'] . '<input type="hidden" value="' . $blog['post_id'] . '"  name="collection_blogs[]"></span></li>';
            }
            echo $lis;
        } ?>
    </ul>
</div>