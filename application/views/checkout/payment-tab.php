<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $frm->setFormTagAttribute('class', 'form form--normal');
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'confirmOrder(this); return(false);');

$pmethodName = $paymentMethod["plugin_name"];
$pmethodDescription = $paymentMethod["plugin_description"];
$pmethodCode = $paymentMethod["plugin_code"];

$submitFld = $frm->getField('btn_submit');
$submitFld->setFieldTagAttribute('class', "btn btn-primary");
?>
<div class="">
    <p><strong><?php echo sprintf(Labels::getLabel('LBL_Pay_using_Payment_Method', $siteLangId), $pmethodName) ?>:</strong></p><br />
    <p><?php echo $pmethodDescription; ?></p><br />
    <?php if (!isset($error)) {
        echo $frm->getFormHtml();
    }
    ?>
</div>
<script type="text/javascript">
    $("document").ready(function() {
        <?php if (isset($error)) { ?>
            $.mbsmessage(<?php echo $error; ?>,true,'alert--danger');
        <?php } ?>
    });
    var containerId = '#tabs-container';

    function confirmOrder(frm) {
        var data = fcom.frmData(frm);
        var action = $(frm).attr('action')
        var getExternalLibraryUrl = $(frm).data('external');
        fcom.updateWithAjax(fcom.makeUrl('Checkout', 'ConfirmOrder'), data, function(res) {
            // $(location).attr("href", action);

            if ('undefined' != typeof getExternalLibraryUrl) {
                fcom.ajax(getExternalLibraryUrl, '', function(t) {
                    var json = $.parseJSON(t);
                    if (1 > json.status) {
                        $.mbsmessage(json.msg, true, 'alert--danger');
                        return;
                    }

                    if (0 < (json.libraries).length) {
                        $.each(json.libraries, function(key, src) {
                            loadScript(src, loadChargeForm, [action]);
                        });
                    } else {
                        loadChargeForm(action);        
                    }
                });
            } else {
                loadChargeForm(action);
            }
        });
    }

    function loadChargeForm(action)
    {
        $.mbsmessage.close();
        fcom.ajax(action, '', function(t) {
            try {
                var ans = $.parseJSON(t);
                if (1 > ans.status) {
                    $.mbsmessage(ans.msg, true, 'alert--danger');
                    return false;
                } else if ('undefined' != typeof ans.redirect) {
                    location.href = ans.redirect;
                } else {
                    $(containerId).html(ans.html);
                }
            } catch (e) {
                // console.log(e);
            }
        });
    }
</script>