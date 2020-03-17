$(document).ready(function () {
    setTimeout(function () {
        $('body').addClass('loaded');
    }, 1000);

    $(document).on("click", ".selectItem--js", function () {
        if ($(this).prop("checked") == false) {
            $(".selectAll-js").prop("checked", false);
        }
        if ($(".selectItem--js").length == $(".selectItem--js:checked").length) {
            $(".selectAll-js").prop("checked", true);
        }
        showFormActionsBtns();
    });
    if (0 < $('.js-widget-scroll').length) {
        slickWidgetScroll();
    }

    /*$(document).on('change', 'input.phone-js', function(e) {
        $(this).keydown()
    });
    $(document).on('keydown', 'input.phone-js', function(e) {
        var key = e.which || e.charCode || e.keyCode || 0;
        $phone = $(this);

        // Don't let them remove the starting '('
        if ($phone.val().length === 1 && (key === 8 || key === 46)) {
            $phone.val('(');
            return false;
        }
        // Reset if they highlight and type over first char.
        else if ($phone.val().charAt(0) !== '(') {
            $phone.val('(');
        }

        // Auto-format- do not expose the mask as the user begins to type
        if (key !== 8 && key !== 9) {
            if ($phone.val().length === 4) {
                $phone.val($phone.val() + ')');
            }
            if ($phone.val().length === 5) {
                $phone.val($phone.val() + ' ');
            }
            if ($phone.val().length === 9) {
                $phone.val($phone.val() + '-');
            }
        }

        // Allow numeric (and tab, backspace, delete, hyphen, space) keys only
        return (key == 8 ||
            key == 9 ||
            key == 46 ||
            key == 189 ||
            key == 32 ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105));
    });
    $(document).on('blur', 'input.phone-js', function() {
        $phone = $(this);
        if ($phone.val() === '(') {
            $phone.val('');
        }
    });*/

    $(document).on('click', '.accordianheader', function () {
        $(this).next('.accordianbody').slideToggle();
        $(this).parent().parent().siblings().children().children().next().slideUp();
        return false;
    });

    if ('rtl' == langLbl.layoutDirection && 0 < $("[data-simplebar]").length && 1 > $("[data-simplebar-direction='rtl']").length) {
        $("[data-simplebar]").attr('data-simplebar-direction', 'rtl');
    }
});

$(document).on('keyup', 'input.otpVal', function (e) {
    if ('' != $(this).val()) {
        $(this).removeClass('is-invalid');
    }

    var element = '';

    /* 
    # e.which = 8(Backspace)
    */
    if (8 != e.which && '' != $(this).val()) {
        element = $(this).parent().nextAll();
    } else {
        element = $(this).parent().prevAll();
    }
    element.children("input.otpVal").eq(0).focus();
});

unlinkSlick = function () {
    $('.js-widget-scroll').slick('unslick');
}

slickWidgetScroll = function () {
    $('.js-widget-scroll').slick(getSlickSliderSettings(3, 1, langLbl.layoutDirection, false, {
        1199: 3,
        1023: 2,
        767: 1,
        480: 1
    }));
}

invalidOtpField = function () {
    $("input.otpVal").val('').addClass('is-invalid').attr('onkeyup', 'checkEmpty($(this))');
}

checkEmpty = function (element) {
    if ('' == element.val()) {
        element.addClass('is-invalid');
    }
}

var otpIntervalObj;
startOtpInterval = function (parent = '') {
    if ('undefined' != typeof otpIntervalObj) {
        clearInterval(otpIntervalObj);
    }

    var parent = '' != parent ? parent + ' ' : '';
    var element = $(parent + ".intervalTimer-js");
    var counter = langLbl.otpInterval;
    element.parent().parent().show();
    element.text(counter);
    $(parent + '.resendOtp-js').addClass('d-none');
    otpIntervalObj = setInterval(function () {
        counter--;
        if (counter === 0) {
            clearInterval(otpIntervalObj);
            $(parent + '.resendOtp-js').removeClass('d-none');
            element.parent().parent().hide();
        }
        element.text(counter);
    }, 1000);
}

loginPopupOtp = function (userId, getOtpOnly = 0) {
    $.mbsmessage(langLbl.processing, false, 'alert--process');
    fcom.ajax(fcom.makeUrl('GuestUser', 'resendOtp', [userId, getOtpOnly]), '', function (t) {
        t = $.parseJSON(t);
        if (1 > t.status) {
            $.mbsmessage(t.msg, false, 'alert--danger');
            return false;
        }
        $.mbsmessage.close();
        var parent = '';
        if (0 < $('#facebox .loginpopup').length) {
            fcom.updateFaceboxContent(t.html, 'faceboxWidth loginpopup');
            var parent = '.loginpopup';
        } else {
            $('#sign-in').html(t.html);
        }
        startOtpInterval(parent);
    });
    return false;
};

function setCurrDateFordatePicker() {
    $('.start_date_js').datepicker('option', {
        minDate: new Date()
    });
    $('.end_date_js').datepicker('option', {
        minDate: new Date()
    });
}

function showFormActionsBtns() {
    if (typeof $(".selectItem--js:checked").val() === 'undefined') {
        $(".formActionBtn-js").addClass('formActions-css');
    } else {
        $(".formActionBtn-js").removeClass('formActions-css');
    }
}

function selectAll(obj) {
    $(".selectItem--js").each(function () {
        if (obj.prop("checked") == false) {
            $(this).prop("checked", false);
        } else {
            $(this).prop("checked", true);
        }
    });
    showFormActionsBtns();
}

function formAction(frm, callback) {
    if (typeof $(".selectItem--js:checked").val() === 'undefined') {
        $.mbsmessage(langLbl.atleastOneRecord, true, 'alert--danger');
        return false;
    }

    $.mbsmessage(langLbl.processing, true, 'alert--process alert');
    data = fcom.frmData(frm);

    fcom.updateWithAjax(frm.action, data, function (resp) {
        callback();
    });
}

function initialize() {
    geocoder = new google.maps.Geocoder();
}

function getCountryStates(countryId, stateId, dv) {
    fcom.ajax(fcom.makeUrl('GuestUser', 'getStates', [countryId, stateId]), '', function (res) {
        $(dv).empty();
        $(dv).append(res);
    });
};

function recentlyViewedProducts(selprodId) {
    if (typeof selprodId == 'undefined') {
        selprodId = 0;
    }

    $("#recentlyViewedProductsDiv").html(fcom.getLoader());

    fcom.ajax(fcom.makeUrl('Products', 'recentlyViewedProducts', [selprodId]), '', function (ans) {
        $("#recentlyViewedProductsDiv").html(ans);
        $('.js-collection-corner:not(.slick-initialized)').slick(getSlickSliderSettings(5, 1, langLbl.layoutDirection, true));
    });
}

function resendVerificationLink(user) {
    if (user == '') {
        return false;
    }
    $(document).trigger('close.systemMessage');
    $.mbsmessage(langLbl.processing, false, 'alert--process alert');
    fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'resendVerification', [user]), '', function (ans) {
        $.mbsmessage(ans.msg, false, 'alert--success');
    });
}

function getCardType(number) {
    // visa
    var re = new RegExp("^4");
    if (number.match(re) != null)
        return "Visa";

    // Mastercard
    re = new RegExp("^5[1-5]");
    if (number.match(re) != null)
        return "Mastercard";

    // AMEX
    re = new RegExp("^3[47]");
    if (number.match(re) != null)
        return "AMEX";

    // Discover
    re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
    if (number.match(re) != null)
        return "Discover";

    // Diners
    re = new RegExp("^36");
    if (number.match(re) != null)
        return "Diners";

    // Diners - Carte Blanche
    re = new RegExp("^30[0-5]");
    if (number.match(re) != null)
        return "Diners - Carte Blanche";

    // JCB
    re = new RegExp("^35(2[89]|[3-8][0-9])");
    if (number.match(re) != null)
        return "JCB";

    // Visa Electron
    re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
    if (number.match(re) != null)
        return "Visa Electron";

    return "";
}

viewWishList = function (selprod_id, dv, event, excludeWishList = 0) {
    event.stopPropagation();
    /*var dv = "#listDisplayDiv_" + selprod_id; */

    if ($(dv).next().hasClass("is-item-active")) {
        $(dv).next().toggleClass('open-menu');
        $(dv).parent().toggleClass('list-is-active');
        return;
    }
    $('.collection-toggle').next().removeClass("is-item-active");
    if (isUserLogged() == 0) {
        loginPopUpBox();
        return false;
    }

    $.facebox(function () {
        fcom.ajax(fcom.makeUrl('Account', 'viewWishList', [selprod_id, excludeWishList]), '', function (ans) {
            fcom.updateFaceboxContent(ans, 'faceboxWidth collection-ui-popup small-fb-width');
            //$(dv).next().html(ans);
            $("input[name=uwlist_title]").bind('focus', function (e) {
                e.stopPropagation();
            });

            activeFavList = selprod_id;

        });

    });

    return false;
}

toggleShopFavorite = function (shop_id) {
    if (isUserLogged() == 0) {
        loginPopUpBox();
        return false;
    }
    var data = 'shop_id=' + shop_id;
    fcom.updateWithAjax(fcom.makeUrl('Account', 'toggleShopFavorite'), data, function (ans) {
        if (ans.status) {
            if (ans.action == 'A') {
                $("#shop_" + shop_id).addClass("is-active");
                $("#shop_" + shop_id).prop('title', 'Unfavorite Shop');
            } else if (ans.action == 'R') {
                $("#shop_" + shop_id).removeClass("is-active");
                $("#shop_" + shop_id).prop('title', 'Favorite Shop');
            }
        }
    });

}

setupWishList = function (frm, event) {
    if (!$(frm).validate()) return false;
    var data = fcom.frmData(frm);
    var selprod_id = $(frm).find('input[name="selprod_id"]').val();
    fcom.updateWithAjax(fcom.makeUrl('Account', 'setupWishList'), data, function (ans) {

        if (ans.status) {
            fcom.ajax(fcom.makeUrl('Account', 'viewWishList', [selprod_id]), '', function (ans) {
                $(".collection-ui-popup").html(ans);
                $("input[name=uwlist_title]").bind('focus', function (e) {
                    e.stopPropagation();
                });
            });
            if (ans.productIsInAnyList) {
                $("[data-id=" + selprod_id + "]").addClass("is-active");
            } else {
                $("[data-id=" + selprod_id + "]").removeClass("is-active");
            }
        }
    });
}

addRemoveWishListProduct = function (selprod_id, wish_list_id, event) {
    event.stopPropagation();
    if (isUserLogged() == 0) {
        loginPopUpBox();
        return false;
    }
    wish_list_id = (typeof (wish_list_id) != "undefined") ? parseInt(wish_list_id) : 0;
    var dv = ".collection-ui-popup";
    var action = 'addRemoveWishListProduct';
    var alternateData = '';
    if (0 >= selprod_id) {
        var oldWishListId = $("input[name='uwlist_id']").val();
        if (typeof oldWishListId !== 'undefined' && wish_list_id != oldWishListId) {
            action = 'updateRemoveWishListProduct';
            alternateData = $('#wishlistForm').serialize();
        }
    }

    fcom.updateWithAjax(fcom.makeUrl('Account', action, [selprod_id, wish_list_id]), alternateData, function (ans) {
        if (ans.status == 1) {
            if (ans.productIsInAnyList) {
                $("[data-id=" + selprod_id + "]").addClass("is-active");
            } else {
                $("[data-id=" + selprod_id + "]").removeClass("is-active");
            }
            if (ans.action == 'A') {
                events.addToWishList();
                $(dv).find(".wishListCheckBox_" + ans.wish_list_id).addClass('is-active');
            } else if (ans.action == 'R') {
                $(dv).find(".wishListCheckBox_" + ans.wish_list_id).removeClass('is-active');
            }

            if ('updateRemoveWishListProduct' == action) {
                viewWishListItems(oldWishListId);
            }
        }
    });
};

removeFromCart = function (key) {
    var data = 'key=' + key;
    fcom.updateWithAjax(fcom.makeUrl('Cart', 'remove'), data, function (ans) {
        if (ans.status) {
            if (ans.total == 0) {
                $('.emtyCartBtn-js').hide();
            }
            listCartProducts();
            $('#cartSummary').load(fcom.makeUrl('cart', 'getCartSummary'));
        }
        $.mbsmessage.close();
        $.systemMessage(langLbl.MovedSuccessfully, 'alert--success');
    });
};

function submitSiteSearch(frm, page) {
    events.search();
    var keyword = $.trim($(frm).find('input[name="keyword"]').val());
    keyword = keyword.replace('&', '++');

    if (3 > keyword.length || '' === keyword) {
        $.mbsmessage(langLbl.searchString, true, 'alert--danger');
        return;
    }

    //var data = fcom.frmData(frm);
    var qryParam = ($(frm).serialize_without_blank());

    var urlString = '';
    if (qryParam.indexOf("keyword") > -1) {
        var protomatch = /^(https?|ftp):\/\//;
        urlString = urlString + setQueryParamSeperator(urlString) + 'keyword-' + encodeURIComponent(keyword.replace(protomatch, '').replace(/\//g, '-')) + '&pagesize=' + page;
    }

    if (qryParam.indexOf("category") > -1 && $(frm).find('input[name="category"]').val() > 0) {
        urlString = urlString + setQueryParamSeperator(urlString) + 'category-' + $(frm).find('input[name="category"]').val();
    }

    /* url_arr = []; */

    if (themeActive == true) {
        url = fcom.makeUrl('Products', 'search', []) + urlString + '&theme-preview';
        document.location.href = url;
        return;
    }
    url = fcom.makeUrl('Products', 'search', []) + urlString;
    document.location.href = url;
}

function getSlickGallerySettings(imagesForNav, layoutDirection, slidesToShow = 4, slidesToScroll = 1) {
    slidesToShow = (typeof slidesToShow != "undefined") ? parseInt(slidesToShow) : 4;
    slidesToScroll = (typeof slidesToScroll != "undefined") ? parseInt(slidesToScroll) : 1;
    layoutDirection = (typeof layoutDirection != "undefined") ? layoutDirection : 'ltr';
    if (imagesForNav) {
        var sliderSettings = {
            slidesToShow: slidesToShow,
            slidesToScroll: slidesToScroll,
            asNavFor: '.slider-for',
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            autoplay: true,
            arrows: true,
            vertical: true,
            verticalSwiping: true,
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 3,

                }
            },
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 4,
                    vertical: false,
                    verticalSwiping: false
                }
            },

            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    vertical: false,
                    verticalSwiping: false
                }
            }
            ]
        };
        if ($(window).width() < 1025 && layoutDirection == 'rtl') {
            sliderSettings['rtl'] = true;
        }

    } else {
        var sliderSettings = {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            autoplay: true,
        };

        if (layoutDirection == 'rtl') {
            sliderSettings['rtl'] = true;
        }
    }
    return sliderSettings;
}

var screenResolutionForSlider = {
    1199: 4,
    1023: 3,
    767: 2,
    480: 2,
    375: 1
};

function getSlickSliderSettings(slidesToShow, slidesToScroll, layoutDirection, autoInfinitePlay, slidesToShowForDiffResolution) {
    slidesToShow = (typeof slidesToShow != "undefined") ? parseInt(slidesToShow) : 4;
    slidesToScroll = (typeof slidesToScroll != "undefined") ? parseInt(slidesToScroll) : 1;
    layoutDirection = (typeof layoutDirection != "undefined") ? layoutDirection : 'ltr';
    autoInfinitePlay = (typeof autoInfinitePlay != "undefined") ? autoInfinitePlay : true;
    if (typeof slidesToShowForDiffResolution != "undefined") {
        slidesToShowForDiffResolution = $.extend(screenResolutionForSlider, slidesToShowForDiffResolution);
    } else {
        slidesToShowForDiffResolution = screenResolutionForSlider;
    }

    var sliderSettings = {
        dots: false,
        slidesToShow: slidesToShow,
        slidesToScroll: slidesToScroll,
        infinite: autoInfinitePlay,
        autoplay: autoInfinitePlay,
        arrows: true,
        responsive: [{
            breakpoint: 1199,
            settings: {
                slidesToShow: slidesToShowForDiffResolution[1199],
            }
        },
        {
            breakpoint: 1023,
            settings: {
                slidesToShow: slidesToShowForDiffResolution[1023],
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: slidesToShowForDiffResolution[767],
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: slidesToShowForDiffResolution[480],
                arrows: false,
                dots: true
            }
        },
        {
            breakpoint: 375,
            settings: {
                slidesToShow: slidesToShowForDiffResolution[375],
                arrows: false,
                dots: true
            }
        }
        ]
    };

    if (layoutDirection == 'rtl') {
        sliderSettings['rtl'] = true;
    }
    return sliderSettings;
}


function codeLatLng(lat, lng) {
    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({
        'latLng': latlng
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            // console.log(results)
            if (results[1]) {
                //formatted address
                for (var i = 0; i < results[0].address_components.length; i++) {
                    if (results[0].address_components[i].types[0] == "country") {
                        var country = results[0].address_components[i].short_name;
                    }

                    if (results[0].address_components[i].types[0] == "administrative_area_level_1") {
                        var state_code = results[0].address_components[i].short_name;
                        var state = results[0].address_components[i].long_name;
                    }

                    if (results[0].address_components[i].types[0] == "administrative_area_level_2") {
                        var city = results[0].address_components[i].long_name;
                    }
                }

                var data = "country=" + country + "&state=" + state + "&state_code=" + state_code + "&city=" + city;
                fcom.updateWithAjax(fcom.makeUrl('Home', 'setCurrentLocation'), data, function (ans) {
                    window.location.reload();
                });
            } else {
                Console.log("Geocoder No results found");
            }
        } else {
            Console.log("Geocoder failed due to: " + status);
        }
    });
}

function defaultSetUpLogin(frm, v) {
    v.validate();
    if (!v.isValid()) {

        return false;
    }
    fcom.ajax(fcom.makeUrl('GuestUser', 'login'), fcom.frmData(frm), function (t) {
        var ans = JSON.parse(t);
        /* alert(t); */
        if (ans.notVerified == 1) {
            var autoClose = false;
        } else {
            var autoClose = true;
        }
        if (ans.status == 1) {
            $.mbsmessage(ans.msg, autoClose, 'alert--success');
            location.href = ans.redirectUrl;
            return;
        }
        $.mbsmessage(ans.msg, autoClose, 'alert--danger');
    });
    return false;
}

(function ($) {
    var screenHeight = $(window).height() - 100;
    window.onresize = function (event) {
        var screenHeight = $(window).height() - 100;
    };

    $.extend(fcom, {
        getLoader: function () {
            return '<div class="loader-yk"><div class="loader-yk-inner"></div></div>';
        },

        scrollToTop: function (obj) {
            if (typeof obj == undefined || obj == null) {
                $('html, body').animate({
                    scrollTop: $('html, body').offset().top - 100
                }, 'slow');
            } else {
                $('html, body').animate({
                    scrollTop: $(obj).offset().top - 100
                }, 'slow');
            }
        },
        resetEditorInstance: function () {
            if (extendEditorJs == true) {
                var editors = oUtil.arrEditor;
                for (x in editors) {
                    eval('delete window.' + editors[x]);
                }
                oUtil.arrEditor = [];
            }
        },

        resetEditorWidth: function (width = "100%") {
            if (typeof oUtil != 'undefined') {
                (oUtil.arrEditor).forEach(function (input) {
                    var oEdit1 = eval(input);
                    $("#idArea" + oEdit1.oName).attr("width", width);
                });
            }
        },

        setEditorLayout: function (lang_id) {
            if (extendEditorJs == true) {
                var editors = oUtil.arrEditor;
                layout = langLbl['language' + lang_id];
                for (x in editors) {
                    $('#idContent' + editors[x]).contents().find("body").css('direction', layout);
                }
            }
        },

        resetFaceboxHeight: function () {
            /* $('html').css('overflow','hidden'); */
            facebocxHeight = screenHeight;
            var fbContentHeight = parseInt($('#facebox .content').height()) + parseInt(150);
            setTimeout(function () {
                $('#facebox .content').css('max-height', (parseInt(facebocxHeight) - parseInt(facebocxHeight) / 4) + 'px');
            }, 700);
            $('#facebox .content').css('overflow-y', 'auto');
            if (fbContentHeight > screenHeight - parseInt(100)) {
                $('#facebox .content').css('display', 'block');
            } else {
                $('#facebox .content').css('max-height', '');
            }
        },
        updateFaceboxContent: function (t, cls) {
            if (typeof cls == 'undefined' || cls == 'undefined') {
                cls = '';
            }
            $.facebox(t, cls);
            $.systemMessage.close();
            fcom.resetFaceboxHeight();
        },
    });

    $(document).bind('reveal.facebox', function () {
        fcom.resetFaceboxHeight();
    });

    $(window).on("orientationchange", function () {
        fcom.resetFaceboxHeight();
    });

    $(document).bind('loading.facebox', function () {
        $('#facebox .content').addClass('fbminwidth');
    });

    $(document).bind('afterClose.facebox', function () {
        $('html').css('overflow', '');
    });

    /* $(document).bind('afterClose.facebox', fcom.resetEditorInstance); */
    $(document).bind('beforeReveal.facebox', function () {
        $('#facebox .content').addClass('fbminwidth');
        $('html').css('overflow', '')
    });

    $(document).bind('reveal.facebox', function () {
        $('#facebox .content').addClass('fbminwidth');
    });

    $.systemMessage = function (data, cls, autoClose) {
        if (typeof autoClose == 'undefined' || autoClose == 'undefined') {
            autoClose = false;
        } else {
            autoClose = true;
        }
        initialize();
        $.systemMessage.loading();
        $.systemMessage.fillSysMessage(data, cls, autoClose);
    };

    $.extend($.systemMessage, {
        settings: {
            closeimage: siteConstants.webroot + 'images/facebox/close.gif',
        },
        loading: function () {
            $('.system_message').show();
        },
        fillSysMessage: function (data, cls, autoClose) {
            if (cls) {
                $('.system_message').removeClass('alert--process');
                $('.system_message').removeClass('alert--danger');
                $('.system_message').removeClass('alert--success');
                $('.system_message').removeClass('alert--info');
                $('.system_message').addClass(cls);
            }
            $('.system_message .content').html(data);
            $('.system_message').fadeIn();

            if (!autoClose && CONF_AUTO_CLOSE_SYSTEM_MESSAGES == 1) {
                var time = CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES * 1000;
                setTimeout(function () {
                    $.systemMessage.close();
                }, time);
            }

            /* $('.system_message').css({top:10}); */
        },
        close: function () {
            $(document).trigger('close.systemMessage');
        },
    });

    $(document).bind('close.systemMessage', function () {
        $('.system_message').fadeOut();
    });

    function initialize() {
        $('.system_message .close').click($.systemMessage.close);
    }
    /* [ */
    $.fn.serialize_without_blank = function () {
        var $form = this,
            result,
            $disabled = $([]);

        $form.find(':input').each(function () {
            var $this = $(this);
            if ($.trim($this.val()) === '' && !$this.is(':disabled')) {
                $disabled.add($this);
                $this.attr('disabled', true);
            }
        });

        result = $form.serialize();
        $disabled.removeAttr('disabled');
        return result;
    };
    /* ] */

})(jQuery);


$(document).ready(function () {
    /* $('#header_search_keyword').autocomplete({
        'classes': {
            "ui-autocomplete": "custom-ui-autocomplete"
        },
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Products', 'searchProductTagsAutocomplete'),
				data: {keyword: encodeURIComponent(request['term']), fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['value'], value: item['value'], name: item['value'] };
					}));
				},
			});
		},
		select: function (event, ui) {
			submitSiteSearch(document.frmSiteSearch);
		}
	}); */


    var $elem = $('#header_search_keyword').autocomplete({
        'classes': {
            "ui-autocomplete": "custom-ui-autocomplete"
        },
        'source': function (request, response) {
            $.ajax({
                url: fcom.makeUrl('Products', 'searchProductTagsAutocomplete'),
                data: { keyword: encodeURIComponent(request['term']), fIsAjax: 1 },
                dataType: 'json',
                type: 'post',
                success: function (json) {
                    response($.map(json, function (item) {
                        return { label: item['value'], value: item['value'] };
                    }));
                },
            });
        },
        select: function (event, ui) {
            $(document.frmSiteSearch.keyword).val(ui.item.label);
            submitSiteSearch(document.frmSiteSearch);
        }
    }),
        elemAutocomplete = $elem.data("ui-autocomplete") || $elem.data("autocomplete");
    if (elemAutocomplete) {
        elemAutocomplete._renderItem = function (ul, item) {
            var newText = String(item.value).replace(
                new RegExp(this.term, "gi"),
                "<strong>$&</strong>");

            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<div>" + newText + "</div>")
                .appendTo(ul);
        };
    }


    /* $('#header_search_keyword').autocomplete({
        'classes': {
            "ui-autocomplete": "custom-ui-autocomplete"
        },
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Products', 'searchProductTagsAutocomplete'),
				data: {keyword: encodeURIComponent(request['term']), fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['value'], value: item['value'], name: item['value'] };
					}));
				},
			});
		},
		select: function (event, ui) {
			submitSiteSearch(document.frmSiteSearch);
		}
	})
    .data("autocomplete")._renderItem = function (ul, item) {
        var newText = String(item.value).replace(
                new RegExp(this.term, "gi"),
                "<span class='ui-state-highlight'>$&</span>");

        return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<div>" + newText + "</div>")
            .appendTo(ul);
    }; */


    /* if (typeof $.fn.autocomplete_advanced !== typeof undefined) {
		$('#header_search_keyword').autocomplete_advanced({
			appendTo: ".main-search__field",
			minChars: 2,
			autoSelectFirst: false,
			lookup: function (query, done) {
				$.ajax({
					url: fcom.makeUrl('Products', 'searchProductTagsAutocomplete'),
					data: {
						keyword: encodeURIComponent(query)
					},
					dataType: 'json',
					type: 'post',
					success: function (json) {
						done(json);
						// $('.autocomplete-suggestions').appendTo('.form__cover');
						// $('.autocomplete-suggestions').insertAfter( "#header_search_keyword" );
					}
				});
			},
			triggerSelectOnValidInput: false,
			onSelect: function (suggestion) {
				submitSiteSearch(document.frmSiteSearch);
				//alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
			}
		});
	} */

    if ($('.system_message').find('.div_error').length > 0 || $('.system_message').find('.div_msg').length > 0 || $('.system_message').find('.div_info').length > 0 || $('.system_message').find('.div_msg_dialog').length > 0) {
        $('.system_message').show();
    }
    $('.close').click(function () {
        $('.system_message').hide();
    });
    addCatalogPopup = function () {
        $.facebox(function () {
            fcom.ajax(fcom.makeUrl('Seller', 'addCatalogPopup'), '', function (t) {
                fcom.updateFaceboxContent(t, 'faceboxWidth loginpopup');

            });
        });
    }

    markAsFavorite = function (selProdId) {
        if (isUserLogged() == 0) {
            loginPopUpBox();
            return false;
        }
        $.mbsmessage.close();
        fcom.updateWithAjax(fcom.makeUrl('Account', 'markAsFavorite', [selProdId]), '', function (ans) {
            if (ans.status) {
                $("[data-id=" + selProdId + "]").addClass("is-active");
                $("[data-id=" + selProdId + "]").attr("onclick", "removeFromFavorite(" + selProdId + ")");
                $("[data-id=" + selProdId + "] span").attr('title', langLbl.RemoveProductFromFavourite);
            }
        });
    };

    removeFromFavorite = function (selProdId, callbackFunction = false) {
        if (isUserLogged() == 0) {
            loginPopUpBox();
            return false;
        }
        $.mbsmessage.close();
        fcom.updateWithAjax(fcom.makeUrl('Account', 'removeFromFavorite', [selProdId]), '', function (ans) {
            if (ans.status) {
                $("[data-id=" + selProdId + "]").removeClass("is-active");
                $("[data-id=" + selProdId + "]").attr("onclick", "markAsFavorite(" + selProdId + ")");
                $("[data-id=" + selProdId + "] span").attr('title', langLbl.AddProductToFavourite);
            }
        });
        if (callbackFunction !== false) {
            window[callbackFunction]();
        }
    };

    guestUserFrm = function () {
        fcom.ajax(fcom.makeUrl('GuestUser', 'form'), '', function (t) {
            fcom.updateFaceboxContent(t, 'faceboxWidth loginpopup');
        });
    };

    openSignInForm = function (includeGuestLogin) {
        if (typeof includeGuestLogin == 'undefined') {
            includeGuestLogin = false;
        }
        data = 'includeGuestLogin=' + includeGuestLogin;
        fcom.ajax(fcom.makeUrl('GuestUser', 'LogInFormPopUp'), data, function (t) {
            fcom.updateFaceboxContent(t, 'faceboxWidth loginpopup');
        });
    };

    guestUserLogin = function (frm, v) {
        v.validate();
        if (!v.isValid()) return;
        $.mbsmessage(langLbl.processing, false, 'alert--process');
        fcom.ajax(fcom.makeUrl('GuestUser', 'guestLogin'), fcom.frmData(frm), function (t) {
            var ans = JSON.parse(t);
            if (ans.status == 1) {
                $.mbsmessage(ans.msg, true, 'alert--success');
                location.href = ans.redirectUrl;
                return;
            }
            $.mbsmessage(ans.msg, true, 'alert--danger');
        });
        return false;
    };

    autofillLangData = function (autoFillBtn, frm) {
        var actionUrl = autoFillBtn.data('action');

        var defaultLangField = $('input.defaultLang', frm);
        if (1 > defaultLangField.length) {
            $.systemMessage(langLbl.unknownPrimaryLanguageField, 'alert--danger');
            return false;
        }
        var proceed = true;
        var stringToTranslate = '';
        defaultLangField.each(function (index) {
            if ('' != $(this).val()) {
                if (0 < index) {
                    stringToTranslate += "&";
                }
                stringToTranslate += $(this).attr('name') + "=" + $(this).val();
            } else {
                $(this).focus();
                $.systemMessage(langLbl.primaryLanguageField, 'alert--danger');
                proceed = false;
                return false;
            }
        });

        if (true == proceed) {
            $.mbsmessage(langLbl.processing, true, 'alert--process alert');
            fcom.ajax(actionUrl, stringToTranslate, function (t) {
                var res = $.parseJSON(t);
                $.each(res, function (langId, values) {
                    $.each(values, function (selector, value) {
                        $("input.langField_" + langId + "[name='" + selector + "']").val(value);
                    });
                });
                $(document).trigger('close.mbsmessage');
            });
        }
    }

    signInWithPhone = function (obj, flag) {
        var form = $(obj).data('form');
        var formElement = ('undefined' != typeof form) ? 'form[name="' + form + '"]' : 'form';
        var inputElement = $(formElement + " input[name='username']");
        var altPlaceHolder = inputElement.attr('data-alt-placeholder');
        var placeHolder = inputElement.attr('placeholder')
        inputElement.attr({ 'placeholder': altPlaceHolder, 'data-alt-placeholder': placeHolder });
        var objLbl = 0 < flag ? langLbl.withUsernameOrEmail : langLbl.withPhoneNumber;
        $(obj).attr('onclick', 'signInWithPhone(this, ' + (!flag) + ')').text(objLbl)
        stylePhoneNumberFld(formElement + " input[name='username']", (!flag));
    };

    $(".sign-in-popup-js").click(function () {
        openSignInForm();
    });

    $(".cc-cookie-accept-js").click(function () {
        fcom.ajax(fcom.makeUrl('Custom', 'updateUserCookies'), '', function (t) {
            $(".cookie-alert").hide('slow');
            $(".cookie-alert").remove();
        });
    });


    $(document).on("click", '.increase-js', function () {
        $(this).siblings('.not-allowed').removeClass('not-allowed');
        var rval = $(this).parent().parent('div').find('input').val();
        if (isNaN(rval)) {
            $(this).parent().parent('div').find('input').val(1);
            return false;
        }
        var key = $(this).parent().parent('div').find('input').attr('data-key');
        var page = $(this).parent().parent('div').find('input').attr('data-page');
        val = parseInt(rval) + 1;
        if (val > $(this).parent().data('stock')) {
            val = $(this).parent().data('stock');
            $(this).addClass('not-allowed');
        }
        if ($(this).hasClass('not-allowed') && rval >= $(this).parent().data('stock')) {
            return false;
        }
        $(this).parent().parent('div').find('input').val(val);
        if (page == 'product-view') {
            return false;
        }
        cart.update(key, page);
    });

    $(document).on("keyup", '.productQty-js', function () {
        if ($(this).val() > $(this).parent().data('stock')) {
            val = $(this).parent().data('stock');
            var message = langLbl.quantityAdjusted.replace(/{qty}/g, val);
            $.mbsmessage(message, '', 'alert--success');
            $(this).parent().parent('div').find('.increase-js').addClass('not-allowed');
            $(this).parent().parent('div').find('.decrease-js').removeClass('not-allowed');
        } else if ($(this).val() <= 0) {
            val = 1;
            $(this).parent().parent('div').find('.decrease-js').addClass('not-allowed');
            $(this).parent().parent('div').find('.increase-js').removeClass('not-allowed');
        } else {
            val = $(this).val();
        }
        $(this).val(val);
        var key = $(this).attr('data-key');
        var page = $(this).attr('data-page');
        if (page == 'product-view') {
            return false;
        }
        cart.update(key, page);
    });

    $(document).on("click", '.decrease-js', function () {
        if ($(this).hasClass('not-allowed')) {
            return false;
        }
        $(this).siblings('.not-allowed').removeClass('not-allowed');
        var rval = $(this).parent().parent('div').find('input').val();
        if (isNaN(rval)) {
            $(this).parent().parent('div').find('input').val(1);
            return false;
        }
        var key = $(this).parent().parent('div').find('input').attr('data-key');
        var page = $(this).parent().parent('div').find('input').attr('data-page');
        var minQty = $(this).parent().parent('div').find('input').attr('data-min-qty');
        var minVal = (minQty > 1) ? minQty : 1;
        val = parseInt(rval) - 1;
        if (val <= minVal) {
            val = minVal;
            $(this).addClass('not-allowed');
        }
        if ($(this).hasClass('not-allowed') && rval <= minVal) {
            return false;
        }
        $(this).parent().parent('div').find('input').val(val);
        if (page == 'product-view') {
            return false;
        }
        cart.update(key, page);
    });

    $(document).on("click", '.setactive-js li', function () {
        $(this).closest('.setactive-js').find('li').removeClass('is-active');
        $(this).addClass('is-active');
    });

    $(document).on("keydown", 'input[name=user_username]', function (e) {
        if (e.which === 32) {
            return false;
        }
        this.value = this.value.replace(/\s/g, "");
    });

    $(document).on("change", 'input[name=user_username]', function (e) {
        this.value = this.value.replace(/\s/g, "");
    });

    $(document).on("submit", "form", function () {
        moveErrorAfterIti()
    });

    $(document).on("keyup", "form .iti input[data-intl-tel-input-id]", function () {
        moveErrorAfterIti();
    });
});

function moveErrorAfterIti() {
    if (0 < $(".iti .errorlist").length) {
        $(".iti .errorlist").detach().insertAfter('.iti');
    }
}

function isUserLogged() {
    var isUserLogged = 0;
    $.ajax({
        url: fcom.makeUrl('GuestUser', 'checkAjaxUserLoggedIn'),
        async: false,
        dataType: 'json',
    }).done(function (ans) {
        isUserLogged = parseInt(ans.isUserLogged);
    });
    return isUserLogged;
}

/* function checkisThemePreview(){
	var isThemePreview = 0;
	$.ajax({
		url: fcom.makeUrl('MyApp','checkisThemePreview'),
		async: false,
		dataType: 'json',
	}).done(function(ans) {
		isThemePreview = parseInt( ans.isThemePreview );
	});
	alert(isThemePreview);
	return isThemePreview;
} */

function loginPopUpBox(includeGuestLogin) {
    /* fcom.ajax(fcom.makeUrl('GuestUser','LogInFormPopUp'), '', function(ans){
    	$(".login-account a").click();
    }); */
    openSignInForm(includeGuestLogin);
}

function setSiteDefaultLang(langId) {
    fcom.ajax(fcom.makeUrl('Home', 'setLanguage', [langId]), '', function (res) {
        document.location.reload();
    });
}

function setSiteDefaultCurrency(currencyId) {
    var currUrl = window.location.href;
    fcom.ajax(fcom.makeUrl('Home', 'setCurrency', [currencyId]), '', function (res) {
        document.location.reload();
    });
}

function quickDetail(selprod_id) {
    $.facebox(function () {
        fcom.ajax(fcom.makeUrl('Products', 'productQuickDetail', [selprod_id]), '', function (t) {
            fcom.updateFaceboxContent(t, 'faceboxWidth productQuickView ');
        });
    });
}

function stylePhoneNumberFld(element = "input[name='user_phone']", destroy = false) {
    var inputList = document.querySelectorAll(element);
    var country = '' == langLbl.defaultCountryCode ? 'in' : langLbl.defaultCountryCode;
    inputList.forEach(function (input) {
        if (true == destroy) {
            $('.iti').replaceWith(input);
            $(input).removeAttr('style');
        } else {
            var iti = window.intlTelInput(input, {
                separateDialCode: true,
                initialCountry: country,
                // utilsScript: "/intlTelInput/intlTelInput-utils.js"
            });
            $('<input>').attr({
                type: 'hidden',
                name: 'user_dial_code',
                value: "+" + iti.getSelectedCountryData().dialCode
            }).insertAfter(input);

            $('<input>').attr({
                type: 'hidden',
                name: 'user_country_iso',
                value: iti.getSelectedCountryData().iso2
            }).insertAfter(input);

            input.addEventListener('countrychange', function (e) {
                if (typeof iti.getSelectedCountryData().dialCode !== 'undefined') {
                    input.closest('form').user_dial_code.value = "+" + iti.getSelectedCountryData().dialCode;
                    input.closest('form').user_country_iso.value = iti.getSelectedCountryData().iso2;
                }
            });
        }
    });
}

function getCountryIso2CodeFromDialCode(dialCode) {
    var countriesData = window.intlTelInputGlobals.getCountryData();
    var countryData = countriesData.filter(function (country) { return country.dialCode == dialCode });
    return countryData[0].iso2;
}


/* read more functionality [ */
$(document).on('click', '.readMore', function () {
    /* $(document).delegate('.readMore' ,'click' , function(){ */
    var $this = $(this);
    var $moreText = $this.siblings('.moreText');
    var $lessText = $this.siblings('.lessText');

    if ($this.hasClass('expanded')) {
        $moreText.hide();
        $lessText.fadeIn();
        $this.text($linkMoreText);
    } else {
        $lessText.hide();
        $moreText.slideDown(1000);
        $this.text($linkLessText);
    }
    $this.toggleClass('expanded');
});
/* ] */

/* Request a demo button [ */
$(document).on('click', '#btn-demo', function () {
    /* $(document).delegate('#btn-demo' ,'click' , function(){ */
    $.facebox(function () {
        fcom.ajax(fcom.makeUrl('Custom', 'requestDemo'), '', function (t) {
            fcom.updateFaceboxContent(t, 'faceboxWidth requestdemo');
        });
    });
});
/* ] */

// Autocomplete */
/*(function ($) {
	$.fn.autocomplete = function (option) {
		return this.each(function () {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function () {
				this.request();
			});

			// Blur
			$(this).on('blur', function () {

				setTimeout(function (object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function (event) {
				switch (event.keyCode) {
					case 27: // escape
					case 9: // tab
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function (event) {
				event.preventDefault();
				value = $(event.target).parent().attr('data-value');
				if (value && this.items[value]) {
                    $(this).siblings('ul.dropdown-menu').hide();
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function () {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function () {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function () {
				clearTimeout(this.timer);
				this.timer = setTimeout(function (object) {

					var txt_box_width = $(object).outerWidth();
					$(object).siblings('ul.dropdown-menu').width(txt_box_width + 'px');

					if ($(object).attr('name') == 'keyword') {
						// i.e header search form will enable autocomplete, if minimum characters are 3
						if ($(object).val().length < 3) {
							return;
						}
					}

					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function (json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu box--scroller"></ul>');
			$(this).siblings('ul.dropdown-menu').on('click', 'a', $.proxy(this.click, this));
		});
	}
})(window.jQuery);*/


$("document").ready(function () {
    $(document).on('click', '.add-to-cart--js', function (event) {
        events.addToCart();
        $btn = $(this);
        event.preventDefault();
        var data = fcom.frmData(document.frmBuyProduct);
        var yourArray = [];
        var selprodId = $(this).siblings('input[name="selprod_id"]').val();
        if (typeof mainSelprodId != 'undefined' && mainSelprodId == selprodId) {
            $(".cart-tbl").find("input").each(function (e) {
                if (($(this).val() > 0) && (!$(this).closest("td").siblings().hasClass("cancelled--js"))) {
                    data = data + '&' + $(this).attr('lang') + "=" + $(this).val();
                }
            });
        }

        fcom.updateWithAjax(fcom.makeUrl('cart', 'add'), data, function (ans) {
            if (ans['redirect']) {
                location = ans['redirect'];
                return false;
            }

            if ($btn.hasClass("btnBuyNow") == true) {
                setTimeout(function () {
                    window.location = fcom.makeUrl('Checkout');
                }, 300);
                return false;
            }
            if ($btn.hasClass("quickView") == true) {
                $(document).trigger('close.facebox');
            }
            if (9 < ans.total) {
                ans.total = '9+';
            }
            $('span.cartQuantity').html(ans.total);
            $('#cartSummary').load(fcom.makeUrl('cart', 'getCartSummary'));
        });
        return false;

    });
});

$(document).ready(function () {
    if ($(window).width() < 1025) {
        $('html').removeClass('sticky-demo-header');
        $("div.demo-header").hide();
    }
});

/* Scroll Hint */
$(document).ready(function () {
    new ScrollHint('.table', {
        i18n: {
            scrollable: langLbl.scrollable
        }
    });
});
$(document).ajaxComplete(function () {
    new ScrollHint('.table:not(.scroll-hint)', {
        i18n: {
            scrollable: langLbl.scrollable
        }
    });

    //Remove scrolling on table with hand icon
    if (0 < $('div.block--empty').length && 0 < $('div.scroll-hint-icon-wrap').length) {
        $('div.block--empty').siblings('.table.scroll-hint').children('div.scroll-hint-icon-wrap').remove();
    }

    //Remove Scrolling When Facebox Popup opened
    if (0 < $("#facebox").length) {
        if ($("#facebox").is(":visible")) {
            $('html').addClass('pop-on');
        } else {
            $('html').removeClass('pop-on');
        }
        $("#facebox .close.close--white").on("click", function () {
            $("html").removeClass('pop-on');
        });
    }

    $('body').click(function () {
        if ($('html').hasClass('pop-on')) {
            $('html').removeClass('pop-on');
        }
    });
});

$(document).ready(function () {
    /*
    STARTS triggers & toggles[

    data-trigger => value = target element id to be opened
    data-target-close => value = target element id to be closed
    data-close-on-click-outside => value

    */

    $('body').find('*[data-trigger]').click(function () {
        var targetElmId = $(this).data('trigger');
        var elmToggleClass = targetElmId + '--on';
        if ($('body').hasClass(elmToggleClass)) {
            $('body').removeClass(elmToggleClass);
        } else {
            $('body').addClass(elmToggleClass);
        }
    });

    $('body').find('*[data-target-close]').click(function () {
        var targetElmId = $(this).data('target-close');
        $('body').toggleClass(targetElmId + '--on');
    });

    $('body').mouseup(function (event) {

        if ($(event.target).data('trigger') != '' && typeof $(event.target).data('trigger') !== typeof undefined) {
            event.preventDefault();
            return;
        }

        $('body').find('*[data-close-on-click-outside]').each(function (idx, elm) {
            var slctr = $(elm);
            if (!slctr.is(event.target) && !$.contains(slctr[0], event.target)) {
                $('body').removeClass(slctr.data('close-on-click-outside') + '--on');
            }
        });
    });

    /*
    ] ENDS triggers & toggles
	
    */
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
});

$(document).on("change", "input[type='file']", fileSizeValidation);

function fileSizeValidation() {
    const fsize = this.files[0].size;
    if (fsize > langLbl.allowedFileSize) {
        var msg = langLbl.fileSizeExceeded;
        var msg = msg.replace("{size-limit}", bytesToSize(langLbl.allowedFileSize));
        $.mbsmessage(msg, true, 'alert--danger');
        $(this).val("");
        return false;
    }
}

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}