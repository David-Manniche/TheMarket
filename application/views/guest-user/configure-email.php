<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php /* $this->includeTemplate('_partial/dashboardNavigation.php'); */ ?>
<div id="body" class="body" role="main">
   <section class="bg-second pt-3 pb-3">
       <div class="container">
           <div class="section-head section--white--head section--head--center mb-0">
               <div class="section__heading">
                    <h2 class="mb-0 pageTitle-js"><?php echo Labels::getLabel('LBL_CONFIGURE_YOUR_DETAILS', $siteLangId);?></h2>
               </div>
           </div>
       </div>
   </section>
   <section class="section">
       <div class="container">
           <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo Labels::getLabel('LBL_UPDATE_EMAIL', $siteLangId);?>
                        </div>
                        <div id="changeEmailFrmBlock"><?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?></div>
                    </div>
                </div>
                <?php if (true === $canSendSms) { ?>
                    <div class="col-md-2">
                        <?php echo Labels::getLabel('LBL_-_OR_-', $siteLangId); ?>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo Labels::getLabel('LBL_UPDATE_PHONE_NUMBER', $siteLangId);?>
                            </div>
                            <div id="changePhoneFrmBlock"><?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?></div>
                        </div>
                    </div>
                <?php } ?>
           </div>
       </div>
   </section>
</div>
