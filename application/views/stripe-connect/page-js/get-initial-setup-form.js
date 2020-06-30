var keyName = 'StripeConnect';

$(document).ready(function(){
    $(document).on("change", "#country", function(){
        $("#state").attr('disabled', 'disabled');
        getStatesByCountryCode($(this).val(),'','#state', 'state_code');
    });

    $(document).on("change", "#state", function(){
        $(this).removeAttr("disabled");
    });
}); 

(function() {
    initialSetup = function (frm){
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl(keyName, 'initialSetup'), data, function(t) {
            if( t.status ){
                window.location = fcom.makeUrl(keyName);
            }
        });
    }
})();