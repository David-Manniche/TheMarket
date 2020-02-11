$(document).ready(function () {
    searchEtpls(document.frmEtplsSearch);
});

(function () {
    var currentPage = 1;
    var runningAjaxReq = false;
    var dv = '#listing';

    goToSearchPage = function (page) {
        if (typeof page == undefined || page == null) {
            page = 1;
        }
        var frm = document.frmEtplsSrchPaging;
        $(frm.page).val(page);
        searchEtpls(frm);
    };

    reloadList = function () {
        var frm = document.frmEtplsSrchPaging;
        searchEtpls(frm);
    };

    searchEtpls = function (form) {
        /*[ this block should be written before overriding html of 'form's parent div/element, otherwise it will through exception in ie due to form being removed from div */
        var data = '';
        if (form) {
            data = fcom.frmData(form);
        }
        /*]*/
        $(dv).html(fcom.getLoader());

        fcom.ajax(fcom.makeUrl('EmailTemplates', 'search'), data, function (res) {
            $(dv).html(res);
        });
    };

    editEtplLangForm = function (etplCode, langId) {
        fcom.resetEditorInstance();
        $.facebox(function () {
            editLangForm(etplCode, langId);
        });
    };


    editLangForm = function (etplCode, langId, autoFillLangData = 0) {
        fcom.displayProcessing();
        fcom.resetEditorInstance();

        fcom.ajax(fcom.makeUrl('EmailTemplates', 'langForm', [etplCode, langId, autoFillLangData]), '', function (t) {
            fcom.updateFaceboxContent(t);
            fcom.setEditorLayout(langId);
            fcom.resetFaceboxHeight();
            var frm = $('#facebox form')[0];
            var validator = $(frm).validation({
                errordisplay: 3
            });
            $(frm).submit(function (e) {
                e.preventDefault();
                validator.validate();
                if (!validator.isValid()) return;
                var data = fcom.frmData(frm);
                fcom.updateWithAjax(fcom.makeUrl('EmailTemplates', 'langSetup'), data, function (t) {
                    fcom.resetEditorInstance();
                    reloadList();
                    if (t.lang_id > 0) {
                        editLangForm(t.etplCode, t.lang_id);
                        return;
                    }
                    $(document).trigger('close.facebox');
                });
            });

        });
    };

    setupEtplLang = function (frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('EmailTemplates', 'langSetup'), data, function (t) {
            reloadList();
            $(document).trigger('close.facebox');
        });
    };

    sendTestEmail = function () {
        var data = fcom.frmData(document.frmEtplLang);
        $.systemMessage(langLbl.processing, 'alert--process', false);
        fcom.ajax(fcom.makeUrl('EmailTemplates', 'sendTestMail'), data, function (res) {
            var ans = $.parseJSON(res);
            if (ans.status == 1) {
                fcom.displaySuccessMessage(ans.msg);
            } else {
                fcom.displayErrorMessage(ans.msg);
            }
            $(document).trigger('close.facebox');
        });
    };

    toggleStatus = function (obj) {
        if (!confirm(langLbl.confirmUpdateStatus)) {
            return;
        }
        var etplCode = obj.id;
        if (etplCode == '') {
            fcom.displayErrorMessage(langLbl.invalidRequest);
            return false;
        }
        data = 'etplCode=' + etplCode;
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('EmailTemplates', 'changeStatus'), data, function (res) {
            var ans = $.parseJSON(res);
            if (ans.status == 1) {
                $(obj).toggleClass("active");
                fcom.displaySuccessMessage(ans.msg);
            } else {
                fcom.displayErrorMessage(ans.msg);
            }
        });
        $.systemMessage.close();
    };

    clearSearch = function () {
        document.frmEtplsSearch.reset();
        searchEtpls(document.frmEtplsSearch);
    };

    toggleBulkStatues = function (status) {
        if (!confirm(langLbl.confirmUpdateStatus)) {
            return false;
        }
        $("#frmEmailTempListing input[name='status']").val(status);
        $("#frmEmailTempListing").submit();
    };

    settingsForm = function(langId) {
        fcom.resetEditorInstance();
        $.facebox(function() {
            editSettingsForm(langId);
        });
    };


    editSettingsForm = function(langId, autoFillLangData = 0) {
        fcom.displayProcessing();
        fcom.resetEditorInstance();

        fcom.ajax(fcom.makeUrl('EmailTemplates', 'settingsForm', [langId, autoFillLangData]), '', function(t) {
            fcom.updateFaceboxContent(t);
            jscolor.installByClassName('jscolor');
            fcom.setEditorLayout(langId);
            fcom.resetFaceboxHeight();
            var frm = $('#facebox form')[0];
            var validator = $(frm).validation({
                errordisplay: 3
            });
            $(frm).submit(function(e) {
                e.preventDefault();
                validator.validate();
                if (!validator.isValid()) return;
                var data = fcom.frmData(frm);
                fcom.updateWithAjax(fcom.makeUrl('EmailTemplates', 'setupSettings'), data, function(t) {
                    fcom.resetEditorInstance();
                    reloadList();
                    if (t.lang_id > 0) {
                        editSettingsForm(t.lang_id);
                        return;
                    }
                    $(document).trigger('close.facebox');
                });
            });

        });
    };

    setupSettings = function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('EmailTemplates', 'setupSettings'), data, function(t) {
            reloadList();
            $(document).trigger('close.facebox');
        });
    };

    resetToDefaultContent =  function(){
		var agree  = confirm(langLbl.confirmReplaceCurrentToDefault);
		if( !agree ){ return false; }
		oUtil.obj.putHTML( $("#editor_default_content").html() );
	};

    removeEmailLogo = function(lang_id) {
        if (!confirm(langLbl.confirmDeleteImage)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('EmailTemplates', 'removeEmailLogo', [lang_id]), '', function(t) {
            settingsForm(lang_id);
        });
    };

})();

$(document).on('click', '.logoFile-Js', function() {
    var node = this;
    $('#form-upload').remove();
    var formName = $(node).attr('data-frm');

    var lang_id = document.frmEtplSettingsForm.lang_id.value;

    var fileType = $(node).attr('data-file_type');

    var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';
    frm = frm.concat('<input type="file" name="file" />');
    frm = frm.concat('<input type="hidden" name="file_type" value="' + fileType + '">');
    frm = frm.concat('<input type="hidden" name="lang_id" value="' + lang_id + '">');
    frm = frm.concat('</form>');
    $('body').prepend(frm);
    $('#form-upload input[name=\'file\']').trigger('click');
    if (typeof timer != 'undefined') {
        clearInterval(timer);
    }
    timer = setInterval(function() {
        if ($('#form-upload input[name=\'file\']').val() != '') {
            clearInterval(timer);
            $val = $(node).val();
            $.ajax({
                url: fcom.makeUrl('EmailTemplates', 'uploadLogo'),
                type: 'post',
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(node).val('Loading');
                },
                complete: function() {
                    $(node).val($val);
                },
                success: function(ans) {
                    if (!ans.status) {
                        $.systemMessage(ans.msg, 'alert--danger');
                        return;
                    }
                    $(".temp-hide").show();
                    var dt = new Date();
                    var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
                    $(".uploaded--image").html('<img src="' + fcom.makeUrl('image', 'emailLogo', [ans.lang_id], SITE_ROOT_URL) + '?' + time + '">');
                    $.systemMessage(ans.msg, 'alert--success');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }, 500);
});
