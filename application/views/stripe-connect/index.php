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
                                        <?php echo Labels::getLabel('Lbl_ACCOUNT_ID', $siteLangId);?> : 
                                        <?php echo $accountId; ?>
                                    </h6>
                                </div>
                                <div class="col-md-3">
                                    <?php if (!empty($requiredFields)) { ?>
                                        <a class="btn btn-outline-primary btn--sm" href="javascript:void(0)" onClick="requiredFieldsForm();" id="js-required-fields" title="<?php echo Labels::getLabel('LBL_SETUP_ACCOUNT', $siteLangId); ?>">
                                            <?php echo Labels::getLabel('Lbl_SETUP_ACCOUNT', $siteLangId); ?>
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
