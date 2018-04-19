<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if(isset($listCategories) && is_array($listCategories) ){ 
$catTab = 1; ?>



<?php foreach($listCategories as $faqCat){ ?>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="browsed-box">
				  <div class="category-q"><?php echo $faqCat['faqcat_name']; ?></div>
				  <a id="<?php echo $faqCat['faqcat_id']; ?>" class="btn--link selectedCat"><i class="svg-icn"><svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="13.711px" height="15.996px" viewBox="0 0 13.711 15.996" enable-background="new 0 0 13.711 15.996" xml:space="preserve">
				  <path fill="none" d="M13.104,4.249L9.462,0.607c-0.089-0.089-0.196-0.169-0.321-0.25V4.57h4.213
	C13.273,4.445,13.193,4.338,13.104,4.249z"/>
				  <path fill="none" d="M8.855,5.713c-0.474,0-0.857-0.384-0.857-0.857V0H0.857C0.384,0,0,0.384,0,0.857v14.282
	c0,0.474,0.384,0.857,0.857,0.857h11.997c0.474,0,0.857-0.384,0.857-0.857V5.713H8.855z"/>
				  <path fill="none" d="M10.283,12.283c0,0.16-0.125,0.285-0.285,0.285H3.713c-0.161,0-0.286-0.125-0.286-0.285v-0.571
	c0-0.161,0.125-0.286,0.286-0.286h6.285c0.16,0,0.285,0.125,0.285,0.286V12.283z"/>
				  <path fill="none" d="M10.283,9.997c0,0.161-0.125,0.286-0.285,0.286H3.713c-0.161,0-0.286-0.125-0.286-0.286V9.426
	c0-0.16,0.125-0.285,0.286-0.285h6.285c0.16,0,0.285,0.125,0.285,0.285V9.997z"/>
				  <path fill="none" d="M10.283,7.712c0,0.161-0.125,0.286-0.285,0.286H3.713c-0.161,0-0.286-0.125-0.286-0.286V7.141
	c0-0.161,0.125-0.286,0.286-0.286h6.285c0.16,0,0.285,0.125,0.285,0.286V7.712z"/>
				  </svg> </i> <?php echo $faqCat['faq_count']; ?> <?php echo Labels::getLabel( 'LBL_Questions', $siteLangId)?></a> <br class="clear">
				  <a id="<?php echo $faqCat['faqcat_id']; ?>" class="btn btn--white btn--block ripplelink selectedCat"><?php echo Labels::getLabel( 'LBL_View_All_Questions', $siteLangId)?></a> </div>
	</div>
<?php } } ?>