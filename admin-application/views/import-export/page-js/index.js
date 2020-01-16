$(document).ready(function() { 
    loadForm('general_instructions');
});

(function() {
    var dv = '#tabData';
    
    loadForm = function(formType) { 
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('ImportExport', 'loadForm', [formType]), '', function(t) {
            $(dv).html(t);
            if ( 'bulk_media' == formType ) {
                searchFiles();
            }
        });
    };
    
    updateSettings = function(frm) {
        var data = fcom.frmData(frm);
        $(dv).html(fcom.getLoader());
        fcom.updateWithAjax(fcom.makeUrl('Configurations', 'setup'), data, function(ans) {
            loadForm('settings');
        });
    };

})();