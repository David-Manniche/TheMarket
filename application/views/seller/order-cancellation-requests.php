<?php defined('SYSTEM_INIT') or die('Invalid Usage');
$frmOrderCancellationRequestsSrch->setFormTagAttribute('onSubmit', 'searchOrderCancellationRequests(this); return false;');
$frmOrderCancellationRequestsSrch->setFormTagAttribute('class', 'form');
$frmOrderCancellationRequestsSrch->developerTags['colClassPrefix'] = 'col-md-';
$frmOrderCancellationRequestsSrch->developerTags['fld_default_col'] = 12;

$orderIdFld = $frmOrderCancellationRequestsSrch->getField('op_invoice_number');
$orderIdFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Order_Id/Invoice_Number', $siteLangId));
$orderIdFld->setWrapperAttribute('class', 'col-lg-2');
$orderIdFld->developerTags['col'] = 2;
$orderIdFld->developerTags['noCaptionTag'] = true;

$statusFld = $frmOrderCancellationRequestsSrch->getField('ocrequest_status');
$statusFld->setWrapperAttribute('class', 'col-lg-2');
$statusFld->developerTags['col'] = 2;
$statusFld->developerTags['noCaptionTag'] = true;

$ocrequestDateFromFld = $frmOrderCancellationRequestsSrch->getField('ocrequest_date_from');
$ocrequestDateFromFld->setFieldTagAttribute('class', 'field--calender');
$ocrequestDateFromFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Date_From', $siteLangId));
$ocrequestDateFromFld->setWrapperAttribute('class', 'col-lg-2');
$ocrequestDateFromFld->developerTags['col'] = 2;
$ocrequestDateFromFld->developerTags['noCaptionTag'] = true;

$ocrequestDateToFld = $frmOrderCancellationRequestsSrch->getField('ocrequest_date_to');
$ocrequestDateToFld->setFieldTagAttribute('class', 'field--calender');
$ocrequestDateToFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Date_to', $siteLangId));
$ocrequestDateToFld->setWrapperAttribute('class', 'col-lg-2');
$ocrequestDateToFld->developerTags['col'] = 2;
$ocrequestDateToFld->developerTags['noCaptionTag'] = true;

$submitBtnFld = $frmOrderCancellationRequestsSrch->getField('btn_submit');
$submitBtnFld->setWrapperAttribute('class', 'col-lg-2');
$submitBtnFld->setFieldTagAttribute('class', 'btn--block');
$submitBtnFld->developerTags['col'] = 2;
$submitBtnFld->developerTags['noCaptionTag'] = true;

$cancelBtnFld = $frmOrderCancellationRequestsSrch->getField('btn_clear');
$cancelBtnFld->setFieldTagAttribute('class', 'btn--block');
$cancelBtnFld->setWrapperAttribute('class', 'col-lg-2');
$cancelBtnFld->developerTags['col'] = 2;
$cancelBtnFld->developerTags['noCaptionTag'] = true;
?>

<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header  row justify-content-between mb-3">
            <div class="col-md-auto">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Order_Cancellation_Requests', $siteLangId); ?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-header p-4">
                            <h5 class="cards-title "><?php echo Labels::getLabel('LBL_Search_Order_Cancellation_Requests', $siteLangId); ?></h5>
                        </div>
                        <div class="cards-content pl-4 pr-4 ">
                            <div class="bg-gray-light p-3 pb-0">
                                <?php
                                $submitFld = $frmOrderCancellationRequestsSrch->getField('btn_submit');
                                $submitFld->setFieldTagAttribute('class', 'btn--block btn btn--primary');

                                $fldClear= $frmOrderCancellationRequestsSrch->getField('btn_clear');
                                $fldClear->setFieldTagAttribute('class', 'btn--block btn btn--primary-border');
                                echo $frmOrderCancellationRequestsSrch->getFormHtml();
                                ?>
                            </div>
                            <span class="gap"></span>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-header p-4">
                            <h5 class="cards-title">Data heading goes here</h5>
                        </div>
                        <div class="cards-content pl-4 pr-4 ">
                            <div id="cancelOrderRequestsListing"></div>
                            <span class="gap"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
