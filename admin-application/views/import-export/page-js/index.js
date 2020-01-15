$(document).ready(function() {
    loadForm('general_instructions');
});

(function() {
    var dv = '#importExportBlock';
    var settingDv = '#settingFormBlock';
    var exportDv = '#exportFormBlock';
    var importDv = '#importFormBlock';
    var runningAjaxReq = false;

    loadForm = function(formType) {
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('ImportExport', 'loadForm', [formType]), '', function(t) {
            $(dv).html(t);
            if ( 'bulk_media' == formType ) {
                searchFiles();
            }
        });
    };

})();