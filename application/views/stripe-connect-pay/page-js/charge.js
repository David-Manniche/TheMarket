connectStripeCheckout = function(stripe, sessionId){
	stripe.redirectToCheckout({
	  // Make the id field from the Checkout Session creation API response
	  // available to this file, so you can provide it as parameter here
	  sessionId: sessionId
	}).then(function (result) {
	  // If `redirectToCheckout` fails due to a browser or network
	  // error, display the localized error message to your customer
	  // using `result.error.message`.
	  console.log(result);
	});
};