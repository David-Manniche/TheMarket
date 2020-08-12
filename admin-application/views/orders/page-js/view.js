$(document).ready(function () {
    $(document).on('click', 'ul.linksvertical li a.redirect--js', function (event) {
        event.stopPropagation();
    });
});
(function () {
    updatePayment = function (frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('Orders', 'updatePayment'), data, function (t) {
            window.location.reload();
        });
    };

    generateLabel = function (orderId, opId) {
        fcom.updateWithAjax(fcom.makeUrl('ShippingServices', 'generateLabel', [orderId, opId]), '', function (t) {
            window.location.reload();
        });
    }

    proceedToShipment = function (opId) {
        $.systemMessage(langLbl.processing,'alert--process', false);
        fcom.ajax(fcom.makeUrl('ShippingServices', 'prceedToShipment', [opId]), '', function (t) {
            $.systemMessage.close();
            t = $.parseJSON(t);
            var classname = 'alert--success';
            if(1 > t.status){
                classname = 'alert--danger';
            }
            $.systemMessage(t.msg, classname, false);
            location.reload();
        });
    }

    track = function (opId) {
        $.systemMessage(langLbl.processing,'alert--process', false);
        fcom.ajax(fcom.makeUrl('ShippingServices', 'track', [opId]), '', function (t) {
            $.systemMessage.close();
            $.facebox(t, 'faceboxWidth fbminwidth');
        });
    }
})();