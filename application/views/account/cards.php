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
                <a class="btn btn-outline-primary btn-sm" href="javascript:void(0);" onclick="addNewCardForm()">
                    <i class="icn">
                        <svg class="svg">
                            <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#add"
                                href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#add">
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
                                    <img src="<?php echo CONF_WEBROOT_URL; ?>images/retina/no-saved-cards.svg"
                                        width="150px" height="150px">
                                </div>
                                <div class="data">
                                    <h2><?php echo Labels::getLabel("LBL_NO_SAVED_CARDS", $siteLangId); ?></h2>
                                    <p><?php echo Labels::getLabel("LBL_ADD_CARDS_TO_CHECKOUT_FASTER", $siteLangId); ?>
                                    </p>
                                    <div class="action">
                                        <a class="btn btn-primary btn-wide" href="javascript:void(0);"
                                            onclick="addNewCardForm()">
                                            <?php echo Labels::getLabel("LBL_ADD_NEW_CARD", $siteLangId); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php } else { ?>
                            <ul class="saved-cards savedCards-js">
                                <?php foreach ($savedCards as $cardDetail) { ?>
                                <li class="card-js <?php echo $defaultSource == $cardDetail['id'] ? "selected" : ""; ?>"
                                    title="<?php echo Labels::getLabel('LBL_MARK_AS_DEFAULT', $siteLangId); ?>">
                                    <label class="radio">
                                        <ul class="list-actions listActions-js">
                                            <li>
                                                <input name="card_id" type="radio"
                                                    value="<?php echo $cardDetail['id']; ?>"
                                                    <?php echo $defaultSource == $cardDetail['id'] ? "checked='checked'" : ""; ?>
                                                    onclick="markAsDefault('<?php echo $cardDetail['id']; ?>')">
                                                <i class="input-helper"></i>
                                            </li>
                                            <li>
                                                <a href="javascript::void(0);"
                                                    onclick="removeCard('<?php echo $cardDetail['id']; ?>')"
                                                    title="<?php echo Labels::getLabel('LBL_REMOVE', $siteLangId); ?>">
                                                    <svg class="svg">
                                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#bin"
                                                            href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#bin">
                                                        </use>
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="payment-card__photo">
                                            <?php 
                                                        $cardBrand = strtolower(str_replace(" ", "", $cardDetail['brand']));
                                                    ?>
                                            <svg class="svg">
                                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#<?php echo $cardBrand; ?>"
                                                    href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#<?php echo $cardBrand; ?>">
                                                </use>
                                            </svg>
                                        </div>
                                        <div class="cards-detail my-4">
                                            <h6><?php echo Labels::getLabel('LBL_CARD_NUMBER', $siteLangId); ?></h6>
                                            <p>
                                                <?php 
                                                            $msg = Labels::getLabel('LBL_****_****_****_{LAST4}', $siteLangId); 
                                                            echo CommonHelper::replaceStringData($msg, ['{LAST4}' => $cardDetail['last4']]);
                                                        ?>
                                            </p>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col-auto">
                                                <div class="cards-detail">
                                                    <h6><?php echo Labels::getLabel('LBL_CARD_HOLDER', $siteLangId); ?>
                                                    </h6>
                                                    <p><?php echo $cardDetail['name']; ?></p>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="cards-detail">
                                                    <h6><?php echo Labels::getLabel('LBL_EXPIRY_DATE', $siteLangId); ?>
                                                    </h6>
                                                    <p><?php echo $cardDetail['exp_month'] . '/' . $cardDetail['exp_year']; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </label>
                                </li>
                                <?php } ?>
                            </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content">
                            <div class="cms">
                                <div class="text-center my-5">
                                    <small class="mb-5"> FEATURES</small>
                                    <h3> The Complete ERP Solution For Your Business Needs</h3>
                                    <p> Now Sell, Manage, Reconcile all your online and offline businesses from a single
                                        dashboard.</p>
                                </div>
                            </div>

                            <ul class="list-features">
                                <li>
                                    <i class="icn fa fa-tv"></i>
                                    <div class="detail cms">
                                        <h5>Improve Process Fitness</h5>
                                        <p>With more than 50 channels integrated in the panel, our
                                            omni-channel
                                            order processing tool helps you increase efficiency.</p>
                                    </div>
                                </li>

                                <li>
                                    <i class="icn  fa fa-wrench"></i>
                                    <div class="detail cms">
                                        <h5>Smarter Stock Allocation and Purchasing Decision</h5>
                                        <p>Central inventory management system to optimise your overall inventory across
                                            channels.

                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <i class="icn fa fa-cubes"></i>
                                    <div class="detail cms">
                                        <h5>Automated Reconciliation Tool
                                        </h5>
                                        <p>Automated reconciliation tool helps you keep a track on returns and unsettled
                                            invoices.

                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <i class="icn fa fa-code"></i>
                                    <div class="detail cms">
                                        <h5>Add Efficiency & Quality Control With End to End WMS Solution
                                        </h5>
                                        <p>Our cloud based WMS helps your warehouse team manage multiple warehouses in a
                                            seamless manner.

                                        </p>
                                    </div>
                                </li>
                                <li>

                                    <i class="icn far fa-file-alt"></i>
                                    <div class="detail cms">
                                        <h5>Eliminate Tedious Data Entry & Duplication
                                        </h5>
                                        <p> Effortless integration of your accounting ERP with EasyEcom reduces error
                                            and cost involved.

                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <i class="icn fa fa-download"></i>
                                    <div class="detail cms">
                                        <h5>Gain Competitive Advantage With Data Analysis
                                        </h5>
                                        <p>The in-built advanced data analytics generates reports such as Margin report,
                                            Sales Report, Inventory forecasting etc helps you grow your business.

                                        </p>
                                    </div>
                                </li>
                            </ul>
                            <div class="mt-4 text-center"><a class="btn btn-primary btn-lg" href="https://www.easyecom.io/go/calendly/" style="background-color: #27ae60;
    border-color: #27ae60;"> Action button </a></div>
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
        addNewCardForm('<?php echo $orderInfo["id"]; ?>'); <
        ?
        php
    } ? >
});
</script>