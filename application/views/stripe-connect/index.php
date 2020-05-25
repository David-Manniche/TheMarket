<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/dashboardNavigation.php');
?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <h2 class="content-header-title"><?php echo $pluginName;?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content">
                            <div class="row">
                                <div class="col-md-9">
                                    <h6 class="m-0">
                                        <?php if (empty($accountId)) { ?>
                                            <a class="btn btn-outline-primary btn--sm" href="<?php echo CommonHelper::generateUrl($keyName, 'register'); ?>">
                                                <?php echo Labels::getLabel('LBL_REGISTER', $siteLangId); ?>
                                            </a>
                                            <a class="btn btn--primary btn--sm" href="<?php echo CommonHelper::generateUrl($keyName, 'login')?>" title="<?php echo Labels::getLabel('MSG_LOGIN', $siteLangId); ?>">
                                                <?php echo Labels::getLabel('LBL_ALREADY_HAVE_ACCOUNT_?', $siteLangId); ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php echo Labels::getLabel('LBL_ACCOUNT_ID', $siteLangId);?> : 
                                            <?php echo $accountId; ?>
                                            <a class="btn btn--primary btn--sm" href="<?php echo CommonHelper::generateUrl($keyName, 'deleteAccount')?>" onclick="return confirm('<?php echo Labels::getLabel('LBL_ARE_YOU_SURE?', $siteLangId); ?>')" title="<?php echo Labels::getLabel('LBL_DELETE_ACCOUNT', $siteLangId); ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if (!empty($loginUrl)) { ?>
                                             <a class="btn btn--primary btn--sm" href="<?php echo $loginUrl; ?>" target="_blank">
                                                <?php echo Labels::getLabel('LBL_STRIPE_DASHBOARD', $siteLangId); ?>
                                            </a>
                                        <?php } ?>
                                    </h6>
                                </div>
                                <div class="col-md-3">
                                    <?php if (!empty($requiredFields)) { ?>
                                        <a class="btn btn-outline-primary btn--sm" href="javascript:void(0)" onClick="requiredFieldsForm();">
                                            <?php echo Labels::getLabel('LBL_UPDATE_ACCOUNT', $siteLangId); ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php if (!empty($requiredFields)) { ?>
    <script>
        requiredFieldsForm();
    </script>
<?php }
