$(document).ready(function(){
		/* alert(singleFeaturedProduct); */
		/* home page main slider */
		
		$('.navchild').hover(function() {
			reinitSlick();
		});
		
		reinitSlick = function() {
			$('.js-hero-slider').slick("slickPause");
			console.log('added');
		}

        $('.js-collection-corner').slick( getSlickSliderSettings(5, 1, langLbl.layoutDirection) );

		if(langLbl.layoutDirection == 'rtl'){
			var slickOptions = {
				slidesToShow: 1,
				arrows: false,
				dots: true,
				rtl:true,
				autoplay: true,
			}

			$('.js-hero-slider').slick(slickOptions);

			$('.featured-item-js').slick({

			  centerMode: true,

			  centerPadding: '26%',

			  slidesToShow: 1,

			  rtl:true,

			  responsive: [

				{

				  breakpoint: 768,

				  settings: {

					arrows: false,

					centerMode: true,

					centerPadding: '5%',

					slidesToShow: 3

				  }

				},

				{

				  breakpoint:500,

				  settings: {

					arrows: false,

					centerMode: true,

					centerPadding: '0%',

					slidesToShow: 1

				  }

				}

			  ]

			});

			$('.fashion-corner-js').slick({
			   dots: false,
				arrows:false,
				autoplay:true,
				pauseOnHover:true,
				slidesToShow:6,
				rtl:true,
				 responsive: [
				{
				  breakpoint: 1025,
				  settings: {
					arrows: false,
					slidesToShow:3,
				  }
				},
				{
				  breakpoint: 500,
				  settings: {
					arrows: false,
					slidesToShow: 1,
				  }
				}
			  ]
			});

		}else{
			var slickOptions = {
				slidesToShow: 1,
				arrows: false,
				dots: true,
                autoplay: true,
			}

			$('.js-hero-slider').slick(slickOptions);

		$('.featured-item-js').slick({
		  centerMode: true,
		  centerPadding: '26%',
		  slidesToShow: 1,
		  responsive: [
			{
			  breakpoint: 768,
			  settings: {
				arrows: false,
				centerMode: true,
				centerPadding: '5%',
				slidesToShow: 3
			  }
			},
			{
			  breakpoint:500,
			  settings: {
				arrows: false,
				centerMode: true,
				centerPadding: '0%',
				slidesToShow: 1
			  }
			}
		  ]
		});

	  $('.fashion-corner-js').slick({
	   dots: false,
		arrows:false,
		autoplay:true,
		pauseOnHover:true,
		slidesToShow:6,
		 responsive: [
		{
		  breakpoint: 1025,
		  settings: {
			arrows: false,
			slidesToShow:3,
		  }
		},
		{
		  breakpoint: 500,
		  settings: {
			arrows: false,
			slidesToShow: 1,
		  }
		}
	  ]
	});

		}

/*Tabs*/
$(".tabs-content-js").hide();
$(".tabs--flat-js li:first").addClass("is-active").show();
$(".tabs-content-js:first").show();
$(".tabs--flat-js li").click(function () {
	$(".tabs--flat-js li").removeClass("is-active");
	$(this).addClass("is-active");
	$(".tabs-content-js").hide();
	var activeTab = $(this).find("a").attr("href");
	$(activeTab).fadeIn();
	return false;
	setSlider();
});

});
resendOtp = function (userId, getOtpOnly = 0){
    $.mbsmessage(langLbl.processing, false, 'alert--process');
    fcom.ajax(fcom.makeUrl( 'GuestUser', 'resendOtp', [userId, getOtpOnly]), '', function(t) {
        t = $.parseJSON(t);
        if(1 > t.status){
            $.mbsmessage(t.msg, false, 'alert--danger');
            return false;
        }
        $.mbsmessage(t.msg, true, 'alert--success');
        startOtpInterval();
    });
    return false;
};

validateOtp = function (frm){
    if (!$(frm).validate()) return;	
    var data = fcom.frmData(frm);
    fcom.ajax(fcom.makeUrl('GuestUser', 'validateOtp'), data, function(t) {						
        t = $.parseJSON(t);
        if (1 == t.status) {
            window.location.href = t.redirectUrl;
        } else {
            invalidOtpField();
        }
    });	
    return false;
};