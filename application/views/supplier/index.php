<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$btn = $sellerFrm->getField('btn_submit');
$btn->setFieldTagAttribute('class', "btn btn-primary btn-wide");
?>
<div class="after-header"></div>
<div id="body" class="body">
    <?php $haveBgImage =AttachedFile::getAttachment(AttachedFile::FILETYPE_SELLER_PAGE_SLOGAN_BG_IMAGE, $slogan['epage_id'], 0, $siteLangId);
    $bgImageUrl = ($haveBgImage) ? "background-image:url(" . UrlHelper::generateUrl('Image', 'cblockBackgroundImage', array($slogan['epage_id'], $siteLangId, 'DEFAULT', AttachedFile::FILETYPE_SELLER_PAGE_SLOGAN_BG_IMAGE)) . ")" : "background-image:url(".CONF_WEBROOT_URL."images/seller-bg.png);"; ?>
    <div class="banner" style="<?php echo $bgImageUrl; ?>">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-6">
                    <div class="seller-slogan">
                        <div class="seller-slogan-txt"> <?php echo FatUtility::decodeHtmlEntities(nl2br($slogan['epage_content']));?> </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6">
                    <div class="seller-register-form">
						<div class="section-head">
							<div class="section__heading">
								<h2><?php echo Labels::getLabel('L_Register_Today', $siteLangId); ?></h2>
							</div>
						</div>
						<div class="gap"></div>
                        <?php $sellerFrm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
						$sellerFrm->developerTags['fld_default_col'] = 12;
						echo $sellerFrm->getFormHtml(); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class=""><?php echo FatUtility::decodeHtmlEntities($formText['epage_content']);?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($block1)) { ?>
        <div class="section">
            <div class="container"><?php echo FatUtility::decodeHtmlEntities($block1['epage_content']); ?></div>
        </div>
    <?php }
    if (!empty($block2)) { ?>
        <div class="section simple-step">
        <div class="container"> <?php echo FatUtility::decodeHtmlEntities($block2['epage_content']); ?> </div>
        </div>
    <?php }
    if (!empty($block3)) { ?>
        <div class="section simple-price">
            <div class="container"> <?php echo FatUtility::decodeHtmlEntities($block3['epage_content']); ?> </div>
        </div>
    <?php } ?>
	
    <?php if ($faqCount > 0) { ?>
        <div class="section bg-gray">
            <div class="container">
                <div class="row align-items-center justify-content-center">
					<div class="col-md-6">
						<div class="section-head section--white--head section--head--center mb-0">
							<div class="section__heading">
								<h2><?php echo Labels::getLabel('LBL_Frequently_Asked_Questions', $siteLangId);?></h2>
							</div>
						</div>
						<div class="faqsearch">
							<form name="frmSearchFaqs" class="form" action="javascript:void(0);">
								<input placeholder="Search" class="faq-input no-focus" data-field-caption="Enter your question" type="search" name="question" value="">
							</form>
						</div>
					</div>
				</div>
            </div>
        </div>
		<div class="section">
			<div class="container">
				<div class="row justify-content-center">
                    <div class="col-md-8">
                        <?php if ($faqCount > 0) { ?>
                        <div class="faq-filters mb-4" id="categoryPanel"></div>
                        <?php } ?>
                        <ul class="faqlist" id="listing"></ul>
                    </div>
                </div>
			</div>
		</div>
        <div class="divider"></div>
    <?php } ?>

    <div class="gap"></div>
    <div class="container">
        <div class="align--center">
            <div class="heading3"><?php echo Labels::getLabel('LBL_Still_need_help', $siteLangId)?> ?</div>
            <a href="<?php echo UrlHelper::generateUrl('custom', 'contact-us'); ?>" class="btn btn-secondary"><?php echo Labels::getLabel('LBL_Contact_Customer_Care', $siteLangId)?> </a>
        </div>
        <div class="gap"></div>
    </div>
    <div class="gap"></div>
</div>
<!-- End Document
================================================== -->
<script>
    var clics = 0;
    $(document).ready(function() {
        $('.faqanswer').hide();
        $('#faqcloseall').hide();
        $(document).on("click", 'h3', function() {
            $(this).next('.faqanswer').toggle(function() {
                $(this).next('.faqanswer');
            }, function() {
                $(this).next('.faqanswer').fadeIn('fast');
            });
            if ($(this).hasClass('faqclose')) {
                $(this).removeClass('faqclose');
            } else {
                $(this).addClass('faqclose');
            };
            if ($('.faqclose').length >= 3) {
                $('#faqcloseall').fadeIn('fast');
            } else {
                $('#faqcloseall').hide();
                var yolo = $('.faqclose').length
                console.log(yolo);
            }
        }); //Close Function Click
    }); //Close Function Ready
    $(document).on("click", '#faqcloseall', function() {
        $('.faqanswer').fadeOut(200);
        $('h3').removeClass('faqclose');
        $('#faqcloseall').fadeOut('fast');
    });
    //search box
    $(function() {
        $(document).on("keyup", '.faq-input', function() {
            // Get user input from search box
            var filter_text = $(this).val();
            var replaceWith = "<span class='js--highlightText'>"+filter_text+"</span>";
            var re = new RegExp(filter_text, 'g');

            $('.faqlist h3').each(function() {
                if ('' !== filter_text) {
                    if ($(this).text().toLowerCase().indexOf(filter_text) >= 0) {
                        var content = $(this).text();
                        $(this).siblings( ".faqanswer" ).slideDown();
                        $(this).html(content.replace(re, replaceWith));
                    } else {
                        $(this).text($(this).text());
                        $(this).siblings( ".faqanswer" ).slideUp();
                    }
                } else {
                    $(this).text($(this).text());
                    $('.faqlist h3').siblings( ".faqanswer" ).slideUp();
                }
            })
        });
    });
</script>
