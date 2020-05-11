$(document).ready(function() {
    searchUrls(document.frmSearch);
});
(function() {
    var currentPage = 1;
    var dv = '#listing';

    goToSearchPage = function(page) {
        if (typeof page == undefined || page == null) {
            page = 1;
        }
        var frm = document.frmImgAttrPaging;
        $(frm.page).val(page);
        searchUrls(frm);
    };

    reloadList = function() {
        var frm = document.frmImgAttrPaging;
        searchUrls(frm);
    };

    searchUrls = function(form) {
        var data = '';
        if (form) {
            data = fcom.frmData(form);
        }
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('ImageAttributes', 'search'), data, function(res) {
            $(dv).html(res);
        });
    };

    urlForm = function(id) {
        var frm = document.frmImgAttrPaging;
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('ImageAttributes', 'form', [id]), '', function(t) {
                $.facebox(t, 'faceboxWidth');
            });
        });
    };

    setup = function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('ImageAttributes', 'setup'), data, function(t) {
            reloadList();
            $(document).trigger('close.facebox');
        });
    };
	
	attributeForm = function(record_id){
		fcom.ajax(fcom.makeUrl('ImageAttributes', 'attributeForm', [record_id, moduleType]), '', function(t) {
			$("#dvForm").html(t).show();
			$("#dvAlert").hide();
		});

	};

    deleteRecord = function(id) {
        if (!confirm(langLbl.confirmDelete)) {
            return;
        }
        data = 'id=' + id;
        fcom.updateWithAjax(fcom.makeUrl('ImageAttributes', 'deleteRecord'), data, function(res) {
            reloadList();
        });
    };

	deleteSelected = function(){
        if(!confirm(langLbl.confirmDelete)){
            return false;
        }
        $("#frmImgAttributeListing").submit();
    };

    clearSearch = function() {
        document.frmSearch.reset();
        searchUrls(document.frmSearch);
    };

})();
