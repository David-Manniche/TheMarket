<?php defined('SYSTEM_INIT') or die('Invalid Usage'); ?>
<?php if (!isset($error)) {
    $frm->setFormTagAttribute('onsubmit', 'confirmPayment(this); return(false);');
    $btn = $frm->getField('btn_submit');
    $btn->addFieldTagAttribute('class', 'btn btn-primary');
    $btn->addFieldTagAttribute('data-processing-text', Labels::getLabel('LBL_PLEASE_WAIT..', $siteLangId));
    echo $frm->getFormHtml();
} else { ?>
    <div class="alert alert--danger"><?php echo $error ?></div>
<?php } ?>
<div id="ajax_message"></div>
<script>
    var confirmPayment = function(frm) {
        var me = $(frm);
        if (me.data('requestRunning')) {
            return;
        }
        if (!me.validate()) return;
        $("input[type='submit']").val(langLbl.processing);
        var data = fcom.frmData(frm);
        var action = me.attr('action');
        fcom.ajax(action, data, function(t) {
            try {
                var json = $.parseJSON(t);
                var el = $('#ajax_message');
                if (json['error']) {
                    el.html('<div class="alert alert--danger">' + json['error'] + '<div>');
                }
                if (json['redirect']) {
                    $(location).attr("href", json['redirect']);
                }
            } catch (exc) {
                console.log(t);
            }
        });
    };
</script>