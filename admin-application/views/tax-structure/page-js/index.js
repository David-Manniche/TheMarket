$(document).ready(function() {
    searchTaxStructure();
});
(function() {
    var dv = '#taxStrListing';
    searchTaxStructure = function() {
        var data = '';
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('TaxStructure', 'search'), '', function(res) {
            $(dv).html(res);
        });
    };

    addStructureForm = function(id){
        $.facebox(function() {
            structureForm(id);
        });
    };

    reloadList = function(){

    };

    structureForm = function(id){
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('TaxStructure', 'form', [id]), '', function(t) {
            fcom.updateFaceboxContent(t);
        });
    };

    setupTaxStructure = function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('TaxStructure', 'setup'), data, function(t) {
            reloadList();
            if (t.langId > 0) {
                addLangForm(t.taxStrId, t.langId);
                return;
            }
            $(document).trigger('close.facebox');
        });
    };

    addLangForm = function(taxStrId, langId){
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('TaxStructure', 'langForm', [taxStrId, langId]), '', function(t) {
            fcom.updateFaceboxContent(t);
        });
    };

    setUpLang = function(frm){
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('TaxStructure', 'langSetup'), data, function(t) {
            reloadList();
            if (t.langId > 0) {
                addLangForm(t.taxStrId, t.langId);
                return;
            }
            $(document).trigger('close.facebox');
        });
    };

    options = function(taxStrId){
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('TaxStructure', 'options', [taxStrId]), '', function(t) {
            searchOptions(taxStrId);
            fcom.updateFaceboxContent(t);
        });
    };

    searchOptions = function(taxStrId) {
        $("#optionListing").html('Loading....');
        fcom.ajax(fcom.makeUrl('TaxStructure', 'searchOptions', [taxStrId]), '', function(res) {
            $("#optionListing").html(res);
        });
    };

    addOptionForm = function(taxstrId, taxstrOptionId) {
        $("#optionForm").html('Loading....');
        fcom.ajax(fcom.makeUrl('TaxStructure', 'addOptionForm', [taxstrId, taxstrOptionId]), '', function(t) {
            $("#optionForm").html(t);
            fcom.resetFaceboxHeight();
        });
    };

    optionSetup = function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('TaxStructure', 'optionSetup'), data, function(t) {
            options(t.taxstrId);
            fcom.resetFaceboxHeight();
        });
    };

    deleteOptionRecord = function(taxstrId, taxstrOptionId) {
        if (!confirm(langLbl.confirmDelete)) {
            return;
        }
        data = 'id=' + taxstrOptionId;
        fcom.updateWithAjax(fcom.makeUrl('TaxStructure', 'deleteOption'), data, function(t) {
            searchOptions(taxstrId);
        });
    };

})();
