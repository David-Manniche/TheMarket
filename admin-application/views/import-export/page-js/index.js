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
    generalInstructions = function(frmType) {
        fcom.resetEditorInstance();
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('Configurations', 'generalInstructions', [frmType]), '', function(t) {
            $(dv).html(t);
        });
    };
    
    updateSettings = function(frm) {
        var data = fcom.frmData(frm);
        $(settingDv).html(fcom.getLoader());
        fcom.updateWithAjax(fcom.makeUrl('ImportExport', 'updateSettings'), data, function(ans) {
            loadForm('settings');
        });
    };
    
})();