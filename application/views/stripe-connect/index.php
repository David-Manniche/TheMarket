<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
?>

<div class="row justify-content-center my-5">
    <div class="col-lg-8">

        <?php if (empty($accountId)) { ?>
        <div class="features-block">
            <div class="features-block_head">
                <h4>Features</h4>
                <p>Connect’s flexible set of features includes:</p>
            </div>
            <ul class="features-block_list">
                <li>
                    <p><strong>Payouts</strong>:</p>
                    <p> Route funds to your recipients’ bank accounts and debit card
                        flexibly and programmatically </p>
                </li>

                <li>
                    <p> <strong>Fee collection</strong>: </p>
                    <p> Drive revenue for your business by collecting fees for
                        your services </p>
                </li>
                <li>
                    <p> <strong>Onboarding</strong>:</p>
                    <p> Collect any information through your own flow and let
                        Stripe take
                        care of the rest through its mobile friendly and conversion-optimized UI </p>
                </li>
            </ul>



            <div class="text-center"> <a class="btn btn-outline-primary btn-sm mr-2" onClick="register(this)"
                    href="javascript:void(0)" data-href="<?php echo UrlHelper::generateUrl($keyName, 'register'); ?>">
                    <?php echo Labels::getLabel('LBL_REGISTER', $siteLangId); ?>
                </a>
                <a class="btn btn-primary btn-sm" href="<?php echo UrlHelper::generateUrl($keyName, 'login') ?>"
                    title="<?php echo Labels::getLabel('MSG_LOGIN', $siteLangId); ?>">
                    <?php echo Labels::getLabel('LBL_ALREADY_HAVE_ACCOUNT_?', $siteLangId); ?>
                </a>
            </div>


            <?php } else { ?>

            <div class="text-center">
                <h5><?php echo Labels::getLabel('LBL_ACCOUNT_ID', $siteLangId); ?> : <?php echo $accountId; ?>
                    <?php if ('custom' == $stripeAccountType) { ?>
                    <a class="btn btn-primary btn-sm" onClick="deleteAccount(this)" href="javascript:void(0)"
                        data-href="<?php echo UrlHelper::generateUrl($keyName, 'deleteAccount') ?>"
                        title="<?php echo Labels::getLabel('LBL_DELETE_ACCOUNT', $siteLangId); ?>">
                        <i class="fa fa-trash"></i>
                    </a></h5>
            </div>


            <?php } ?>
            <?php } ?>
            <?php if (!empty($loginUrl)) { ?>
            <a class="btn btn-primary btn-sm" href="<?php echo $loginUrl; ?>" target="_blank">
                <?php echo Labels::getLabel('LBL_STRIPE_DASHBOARD', $siteLangId); ?>
            </a>
            <?php } ?>
        </div>
    </div>
</div>

<?php if (!empty($requiredFields) && !empty($accountId)) { ?>
<div class="row">
    <div class="col-md-12 requiredFieldsForm-js"></div>
</div>
<script>
requiredFieldsForm();
</script>
<?php } elseif (!empty($accountId) && !empty($stripeUserData)) { ?>

<ul class="stripe-stats">
    <li>
        <div class="stats">
            <span class="title"><?php echo Labels::getLabel('MSG_BUSINESS_PROFILE_NAME', $siteLangId); ?></span>
            <p><?php echo $stripeUserData['business_profile']['name']; ?></p>
        </div>
    </li>
    <li>
        <div class="stats">
            <p>
                <?php echo Labels::getLabel('MSG_CHARGES', $siteLangId); ?> :
                <?php echo 0 < $stripeUserData['charges_enabled'] ? Labels::getLabel('MSG_ENABLED', $siteLangId) : Labels::getLabel('MSG_NOT_ENABLED', $siteLangId); ?>
            </p>
            <p>
                <?php echo Labels::getLabel('MSG_PAYOUTS', $siteLangId); ?> :
                <?php echo ucwords($stripeUserData['settings']['payouts']['schedule']['interval']); ?>
            </p>
        </div>
    </li>
    <li>
        <div class="stats">
            <span class="title"><?php echo Labels::getLabel('MSG_SUPPORT_EMAIL', $siteLangId); ?></span>
            <p><?php echo $stripeUserData['business_profile']['support_email']; ?></p>
        </div>
    </li>
    <li>
        <div class="stats">
            <span class="title"><?php echo Labels::getLabel('MSG_SUPPORT_PHONE', $siteLangId); ?></span>
            <p><?php echo $stripeUserData['business_profile']['support_phone']; ?></p>
        </div>
    </li>
    <li>
        <div class="stats">
            <span class="title"><?php echo Labels::getLabel('MSG_SUPPORT_ADDRESS', $siteLangId); ?></span>
            <?php $address = $stripeUserData['business_profile']['support_address']; ?>
            <p><?php echo $address['line1']; ?></p>
            <p><?php echo $address['line2']; ?></p>
            <p>
                <?php echo $address['city'] . ', ' . 
                    $address['state'] . ', ' . 
                    $address['country'] . ' ' . $address['postal_code'] . ''; ?>
            </p>
        </div>
    </li>
    <li>
        <div class="stats">
            <span class="title"><?php echo Labels::getLabel('MSG_BANK_DETAIL', $siteLangId); ?></span>
            <?php foreach ($stripeUserData['external_accounts']['data'] as $index => $bank) { ?>
            <p><?php echo Labels::getLabel('MSG_BANK_NAME', $siteLangId); ?> : <?php echo $bank['bank_name']; ?></p>
            <p><?php echo Labels::getLabel('MSG_ACCOUNT_HOLDER_NAME', $siteLangId); ?> :
                <?php echo $bank['account_holder_name']; ?></p>
            <p><?php echo Labels::getLabel('MSG_ACCOUNT_NUMBER', $siteLangId); ?> :
                <?php echo '****' . $bank['last4']; ?></p>
            <p><?php echo Labels::getLabel('MSG_ROUTING_NUMBER', $siteLangId); ?> :
                <?php echo $bank['routing_number']; ?></p>
            <?php if (($index + 1) < count($stripeUserData['external_accounts']['data'])) { ?>
            
            <?php } ?>
            <?php } ?>
        </div>
    </li>
</ul>

<?php } ?>