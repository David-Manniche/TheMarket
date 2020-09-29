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
                        <p> <?php echo $testimonial['testimonial_text']; ?> </p>
                        <div class="from">
                            <img class="user-pic" alt="<?php echo $testimonial['testimonial_user_name']; ?>" src="<?php echo UrlHelper::generateUrl('Image', 'testimonial', array($testimonial['testimonial_id'], $siteLangId, 'THUMB')).'?t='.time(); ?>">
                            <p><?php echo $testimonial['testimonial_user_name']; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <script>
    $(".js-slider-testimonials").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        arrows: true,
        dots: true,
        autoplaySpeed: 2000,
        responsive: [{
            breakpoint: 600,
            settings: {
                arrows: false,
            }
        }]
    });
    </script>
<?php } ?>
