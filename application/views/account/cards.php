<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/dashboardNavigation.php'); 
?>
<main id="main-area" class="main" role="main">
    <main class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_My_CARDS', $siteLangId);?></h2>
            </div>
            <div class="col-auto"><a class="btn btn-outline-primary btn-sm" href="javascript:void(0)"
                    onclick="truncateDataRequestPopup()"> <i class="icn">
                        <svg class="svg">
                            <use xlink:href="/yokart/images/retina/sprite.svg#add"
                                href="/yokart/images/retina/sprite.svg#add">
                            </use>
                        </svg>
                    </i>
                    Add New Card </a>
            </div>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content">
                            <div class="no-data-found">
                                <div class="img">
                                    <img src="/yokart/images/retina/no-saved-cards.svg" width="150px" height="150px">
                                </div>
                                <div class="data">
                                    <h2>No Save cards</h2>
                                    <p>There are no saved cards to show</p>
                                    <div class="action">
                                        <a class="btn btn-primary btn-wide" href="#">Add New Card</a>
                                    </div>
                                </div>
                            </div>




                            <ul class="saved-cards">
                                <li class="selected">
                                    <div class="payment-card__photo">
                                        <svg class="svg">
                                            <use xlink:href="/yokart/images/retina/sprite.svg#visa"
                                                href="/yokart/images/retina/sprite.svg#visa">
                                            </use>
                                        </svg>
                                    </div>
                                    <label class="radio">
                                        <input name="card_id" type="radio" value="card_1HEXlNCvMMMb9OAZN3P9NTHv"
                                            checked="">
                                        <i class="input-helper"></i>
                                    </label>
                                    <div class="cards-detail my-4">
                                        <h6>Card Number</h6>
                                        <p>**** **** **** 2345</p>
                                    </div>

                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <div class="cards-detail">
                                                <h6>Card Holder</h6>
                                                <p>Pawan Yadhuwanshi</p>
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <div class="cards-detail">
                                                <h6>Expiry Date</h6>
                                                <p>04/28</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="payment-card__photo">
                                        <svg class="svg">
                                            <use xlink:href="/yokart/images/retina/sprite.svg#visa"
                                                href="/yokart/images/retina/sprite.svg#visa">
                                            </use>
                                        </svg>
                                    </div>

                                    <label class="radio">
                                        <input name="card_id" type="radio" value="card_1HEXlNCvMMMb9OAZN3P9NTHv"
                                            checked="">
                                        <i class="input-helper"></i>
                                    </label>

                                    <div class="cards-detail my-4">
                                        <h6>Card Number</h6>
                                        <p>**** **** **** 2345</p>
                                    </div>

                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <div class="cards-detail">
                                                <h6>Card Holder</h6>
                                                <p>Pawan Yadhuwanshi</p>
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <div class="cards-detail">
                                                <h6>Expiry Date</h6>
                                                <p>04/28</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="payment-card__photo">
                                        <svg class="svg">
                                            <use xlink:href="/yokart/images/retina/sprite.svg#visa"
                                                href="/yokart/images/retina/sprite.svg#visa">
                                            </use>
                                        </svg>
                                    </div>

                                    <label class="radio">
                                        <input name="card_id" type="radio" value="card_1HEXlNCvMMMb9OAZN3P9NTHv"
                                            checked="">
                                        <i class="input-helper"></i>
                                    </label>

                                    <div class="cards-detail my-4">
                                        <h6>Card Number</h6>
                                        <p>**** **** **** 2345</p>
                                    </div>

                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <div class="cards-detail">
                                                <h6>Card Holder</h6>
                                                <p>Pawan Yadhuwanshi</p>
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <div class="cards-detail">
                                                <h6>Expiry Date</h6>
                                                <p>04/28</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="payment-card__photo">
                                        <svg class="svg">
                                            <use xlink:href="/yokart/images/retina/sprite.svg#visa"
                                                href="/yokart/images/retina/sprite.svg#visa">
                                            </use>
                                        </svg>
                                    </div>

                                    <label class="radio">
                                        <input name="card_id" type="radio" value="card_1HEXlNCvMMMb9OAZN3P9NTHv"
                                            checked="">
                                        <i class="input-helper"></i>
                                    </label>

                                    <div class="cards-detail my-4">
                                        <h6>Card Number</h6>
                                        <p>**** **** **** 2345</p>
                                    </div>

                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <div class="cards-detail">
                                                <h6>Card Holder</h6>
                                                <p>Pawan Yadhuwanshi</p>
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <div class="cards-detail">
                                                <h6>Expiry Date</h6>
                                                <p>04/28</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <ul class="list-group payment-card payment-card-double">
                                <?php
                            foreach ($savedCards as $cardDetail) { ?>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-auto">
                                            <label class="radio">
                                                <input name="card_id" type="radio"
                                                    value="<?php echo $cardDetail['id']; ?>"
                                                    <?php echo $defaultSource == $cardDetail['id'] ? "checked" : ""; ?>>
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                        <div class="col-auto">
                                            <div class="payment-card__photo">
                                                <svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#<?php echo strtolower($cardDetail['brand']); ?>"
                                                        href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#<?php echo strtolower($cardDetail['brand']); ?>">
                                                    </use>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="payment-card__number">
                                                <?php echo Labels::getLabel('LBL_ENDING_IN', $siteLangId); ?>
                                                <strong><?php echo $cardDetail['last4']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="payment-card__name"><?php echo $cardDetail['name']; ?></div>
                                        </div>
                                        <div class="col">
                                            <div class="payment-card__expiry">
                                                <?php echo Labels::getLabel('LBL_EXPIRY', $siteLangId); ?>
                                                <strong><?php echo $cardDetail['exp_month'] . '/' . $cardDetail['exp_year']; ?></strong>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="payment-card__actions">
                                                <ul class="list-actions">
                                                    <li>
                                                        <a href="javascript:void(0)"
                                                            onClick="removeCard('<?php echo $cardDetail['id']; ?>');">
                                                            <svg class="svg" width="24px" height="24px">
                                                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#remove"
                                                                    href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#remove">
                                                                </use>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                            <div class="my-3 text-right">
                                <a class="link-text" href="javascript:void(0);" onclick="addNewCard()">
                                    <i class="icn">
                                        <svg class="svg">
                                            <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#add"
                                                href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#add">
                                            </use>
                                        </svg>
                                    </i>
                                    <?php echo Labels::getLabel('LBL_ADD_NEW_CARD', $siteLangId); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</main>
<script>
$(document).ready(function() {
    <
    ?
    php
    if (empty($savedCards)) {
        ?
        >
        addNewCard('<?php echo $orderInfo["id"]; ?>'); <
        ?
        php
    } ? >
});
</script>