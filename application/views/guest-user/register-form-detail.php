<div class="form-side-inner">
    <div class="section-head">
        <div class="section__heading">
            <h2>
                <?php echo Labels::getLabel('LBL_Create_Your_Account_For_Sign_Up', $siteLangId);?>
                <span class="note">
                    <?php if (isset($registerdata['signUpWithPhone']) && true === $smsPluginStatus) {
                            if (0 == $registerdata['signUpWithPhone']) { ?>
                                <a href="javaScript:void(0)" onClick="signUpWithPhone()"><?php echo Labels::getLabel('LBL_WITH_PHONE_NUMBER_?', $siteLangId); ?></a>
                            <?php } else { ?>
                                <a href="javaScript:void(0)" onClick="signUpWithEmail()"><?php echo Labels::getLabel('LBL_WITH_EMAIL_?', $siteLangId); ?></a>
                            <?php } ?>
                    <?php } ?>
                </span>
            </h2>
        </div>
    </div>
    <?php $this->includeTemplate('guest-user/registerationFormTemplate.php', $registerdata, false); ?>
</div>