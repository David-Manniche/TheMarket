<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row justify-content-between mb-3">
            <div class="col-md-auto">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
            </div>
        </div>
        <div class="content-body">
            <div class="cards">
                <div class="cards-content pt-3 pl-4 pr-4 ">
                    <div class="message message--success align--center">
                        <?php if ($supplierRequest["usuprequest_status"] == User::SUPPLIER_REQUEST_PENDING) { ?>
                        <i class="fa fa-hourglass-1"></i>
                        <h1><span><?php //echo /* Labels::getLabel('LBL_Oops',$siteLangId); */?></span></h1>
                        <h4><?php echo Labels::getLabel('LBL_Hello', $siteLangId) ,' ', $supplierRequest["user_name"]?> , <?php echo Labels::getLabel('LBL_Thank_you_for_submitting_your_application', $siteLangId)?></h4>
                        <h6><?php echo Labels::getLabel('LBL_application_awaiting_approval', $siteLangId)?></h6>
                        <p><?php echo Labels::getLabel('LBL_Application_Reference', $siteLangId)?>: <strong><?php echo $supplierRequest["usuprequest_reference"]; ?></strong></p>

                        <?php } elseif ($supplierRequest["usuprequest_status"] == User::SUPPLIER_REQUEST_APPROVED) { ?>
                        <i class="fa fa-check-circle"></i>
                        <h1><span><?php echo /* Labels::getLabel('LBL_Approved',$siteLangId) */ Labels::getLabel('LBL_Congratulations', $siteLangId); ?></span></h1>
                        <h4><?php echo Labels::getLabel('LBL_Hello', $siteLangId) ,' ', $supplierRequest["user_name"]?> , <?php echo Labels::getLabel('LBL_Your_Application_Approved', $siteLangId)?></h4>
                        <h6><?php echo Labels::getLabel('LBL_Start_Using_Seller_Please_Contact_Us', $siteLangId)?></h6>
                        <p><?php echo Labels::getLabel('LBL_Application_Reference', $siteLangId)?>: <strong> <?php echo $supplierRequest["usuprequest_reference"]; ?></strong></p>

                        <?php } elseif ($supplierRequest["usuprequest_status"] == User::SUPPLIER_REQUEST_CANCELLED) { ?>
                        <i class="fa fa-ban"></i>
                        <h2><span><?php echo /* Labels::getLabel('LBL_Declined_Cancelled',$siteLangId) */ Labels::getLabel('LBL_Oops', $siteLangId); ?></span></h2>
                        <h6><strong><?php echo Labels::getLabel('LBL_Reason_for_cancellation', $siteLangId)?></strong></h6><br>
                        <p><?php echo nl2br($supplierRequest["usuprequest_comments"]);?></p>
                        <h4><?php echo Labels::getLabel('LBL_Hello', $siteLangId) ,' ', $supplierRequest["user_name"]?> , <?php echo Labels::getLabel('LBL_Your_Application_Declined', $siteLangId)?></h4>
                        <h6><?php echo Labels::getLabel('LBL_Think_Error_Please_Contact_Us', $siteLangId)?></h6>
                        <a class="btn btn--secondary" href="<?php echo CommonHelper::generateUrl('account', 'supplierApprovalForm', array('reopen')); ?>">
                            <?php echo Labels::getLabel('LBL_Submit_Revised_Request', $siteLangId)?></a>
                        <div class="gap"></div>
                        <p><?php echo Labels::getLabel('LBL_Application_Reference', $siteLangId)?>: <strong><?php echo $supplierRequest["usuprequest_reference"]; ?></strong></p>

                        <?php } ?>
                        <span class="gap"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
