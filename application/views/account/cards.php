<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/dashboardNavigation.php');
?>
<main id="main-area" class="main" role="main">
    <main class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_My_CARDS', $siteLangId); ?></h2>
            </div>
            <?php if (!empty($savedCards)) { ?>
                <div class="col-auto">
                    <a class="btn btn-outline-primary btn-sm" href="javascript:void(0);" onclick="addNewCard()">
                        <i class="icn">
                            <svg class="svg">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#add" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#add">
                                </use>
                            </svg>
                        </i>
                        <?php echo Labels::getLabel("LBL_ADD_NEW_CARD", $siteLangId); ?>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content">
                            <?php if (empty($savedCards)) { ?>
                                <div class="no-data-found">
                                    <div class="img">
                                        <img src="<?php echo CONF_WEBROOT_URL; ?>images/retina/no-saved-cards.svg" width="150px" height="150px">
                                    </div>
                                    <div class="data">
                                        <h2><?php echo Labels::getLabel("LBL_NO_SAVED_CARDS", $siteLangId); ?></h2>
                                        <p><?php echo Labels::getLabel("LBL_ADD_CARDS_TO_CHECKOUT_FASTER", $siteLangId); ?></p>
                                        <div class="action">
                                            <a class="btn btn-primary btn-wide" href="javascript:void(0);" onclick="addNewCard()">
                                                <?php echo Labels::getLabel("LBL_ADD_NEW_CARD", $siteLangId); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <ul class="saved-cards">
                                    <?php foreach ($savedCards as $cardDetail) { ?>
                                        <li class="<?php echo $defaultSource == $cardDetail['id'] ? "selected" : ""; ?>">
                                            <ul class="list-actions">
                                                <li>
                                                    <label class="radio">
                                                        <input name="card_id" type="radio" value="<?php echo $cardDetail['id']; ?>" <?php echo $defaultSource == $cardDetail['id'] ? "checked='checked'" : ""; ?>>
                                                        <i class="input-helper"></i>
                                                    </label>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <svg class="svg">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#bin" href="/yokart/images/retina/sprite.svg#bin">
                                                            </use>
                                                        </svg>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="payment-card__photo">
                                                <svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#<?php echo strtolower($cardDetail['brand']); ?>" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#<?php echo strtolower($cardDetail['brand']); ?>">
                                                    </use>
                                                </svg>
                                            </div>
                                            <div class="cards-detail my-4">
                                                <h6><?php echo Labels::getLabel('LBL_CARD_NUMBER', $siteLangId); ?></h6>
                                                <p>**** **** **** <?php echo $cardDetail['last4']; ?></p>
                                            </div>

                                            <div class="row justify-content-between">
                                                <div class="col-auto">
                                                    <div class="cards-detail">
                                                        <h6><?php echo Labels::getLabel('LBL_CARD_HOLDER', $siteLangId); ?></h6>
                                                        <p><?php echo $cardDetail['name']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="cards-detail">
                                                        <h6><?php echo Labels::getLabel('LBL_EXPIRY_DATE', $siteLangId); ?></h6>
                                                        <p><?php echo $cardDetail['exp_month'] . '/' . $cardDetail['exp_year']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</main>
<script>
    $(document).ready(function() {
        <?php
        if (empty($savedCards)) {
        ?>
            addNewCard('<?php echo $orderInfo["id"]; ?>');
        <?php
        } ?>
    });
</script>