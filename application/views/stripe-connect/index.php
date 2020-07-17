<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
?>

<div class="col-md-9">
    <h6 class="m-0">
        <?php if (empty($accountId)) { ?>
            <a class="btn btn-outline-primary btn--sm" onClick="register(this)" href="javascript:void(0)"  data-href="<?php echo UrlHelper::generateUrl($keyName, 'register'); ?>">
                <?php echo Labels::getLabel('LBL_REGISTER', $siteLangId); ?>
            </a>
            <a class="btn btn--primary btn--sm" href="<?php echo UrlHelper::generateUrl($keyName, 'login')?>" title="<?php echo Labels::getLabel('MSG_LOGIN', $siteLangId); ?>">
                <?php echo Labels::getLabel('LBL_ALREADY_HAVE_ACCOUNT_?', $siteLangId); ?>
            </a>
        <?php } else { ?>
            <?php echo Labels::getLabel('LBL_ACCOUNT_ID', $siteLangId);?> : 
            <?php echo $accountId; ?>
            <?php if ('custom' == $stripeAccountType) { ?>
                <a class="btn btn--primary btn--sm"  onClick="deleteAccount(this)" href="javascript:void(0)"  data-href="<?php echo UrlHelper::generateUrl($keyName, 'deleteAccount')?>" title="<?php echo Labels::getLabel('LBL_DELETE_ACCOUNT', $siteLangId); ?>">
                    <i class="fa fa-trash"></i>
                </a>
            <?php } ?>
        <?php } ?>
        <?php if (!empty($loginUrl)) { ?>
                <a class="btn btn-primary btn-sm" href="<?php echo $loginUrl; ?>" target="_blank">
                <?php echo Labels::getLabel('LBL_STRIPE_DASHBOARD', $siteLangId); ?>
            </a>
        <?php } ?>
    </h6>
</div>
<div class="col-md-3">
    <?php if (!empty($requiredFields)) { ?>
        <a class="btn btn-outline-primary btn-sm" href="javascript:void(0)" onClick="requiredFieldsForm();">
            <?php echo Labels::getLabel('LBL_UPDATE_ACCOUNT', $siteLangId); ?>
        </a>
    <?php } ?>
</div>
<div class="col-md-12 requiredFieldsForm-js"></div>

<?php if (!empty($requiredFields)) { ?>
    <script>
        requiredFieldsForm();
    </script>
<?php }
