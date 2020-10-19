<?php if (isset($collection['testimonials']) && count($collection['testimonials']) > 0) { ?>
<section class="section bg-second" role="testimonials">
    <div class="container">
        <div class="section-head section--white--head section--head--center">
            <div class="section__heading">
                <h2><?php echo Labels::getLabel('LBL_What_Clients_Say', $siteLangId); ?></h2>
            </div>
        </div>
        <!-- Slider -->
        <div class="js-slider-testimonials slider-testimonials">
            <?php foreach ($collection['testimonials'] as $testimonial) { ?>
            <div class="slide-item">
                <div class="slide-item__text">
                    <p> <?php echo $testimonial['testimonial_text']; ?> </p>
                </div>
                <div class="slide-item__from">
                    <img class="user-pic" alt="<?php echo $testimonial['testimonial_user_name']; ?>"
                        src="<?php echo UrlHelper::generateUrl('Image', 'testimonial', array($testimonial['testimonial_id'], $siteLangId, 'THUMB')).'?t='.time(); ?>">
                    <div class="user-detail">
                        <p><span class="name"><?php echo $testimonial['testimonial_user_name']; ?></span>
                            <span class="designation">Senior UI/UX Designer</span>
                        </p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <!-- /Slider -->
        <div class="section-foot text-center">
            <a class="btn btn-outline-white btn-wide btn-wide" href="#">View all</a>
        </div>
    </div>
</section>
<script>
$(".js-slider-testimonials").slick({
    centerMode: true,
    centerPadding: '0',
    slidesToShow: 3,
    variableWidth: false,
    dots: true,
    arrows: true,
    swipe: true,
    //  infinite: true,
    swipeToSlide: true,
    //adaptiveHeight: true,

    responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                dots: true,
                arrows: false,
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                dots: true,
                arrows: false,
            }
        }

    ]
});
</script>
<?php } ?>