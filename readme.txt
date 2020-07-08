New Features:
    => Avalara Tax API [Execute {domainame}/admin/patch-update/update-avalaratax-cat]
    => Stripe Connect payment gateway
    => S3 bucket implementation.
    => Shipping Packages and Profile Module.
    => Configuration to on/off seller shipping.
    => Shipstation api for shiping rates.    
Bugs:
    => 036750 - Seller Shop - Custom URL for shop collections
    => 036504 - wrong stats are displaying on buyer dashboard - pending and total orders
    => 036604 - if product having some offer or special price then on all seller screen> strike is not there on original amount
    => 036596 - Admin is unable to make payment for cod order
    => 037316 - iOS- label issue on using existing number
    => 039438 - Case sensitive product type identifier is not accepted while importing catalog.

Enhancements:
    => Tax module upgrade
    => Test cases classes enhancements.
Notes:
   ==========Stripe Connect Installation Notes[:=========
   Composer should be installed on server to run the stripe connect module: composer.json on root of the project has details to download the required libraries in root's vendor folder.

   a) Run command at root of the project to update composer to fetch all required libraries from the root of the project using terminal: composer update
   b) Required to configure callback url as "{domain-name}/public/index.php?url=stripe-connect/callback" inside stripe's web master's account under https://dashboard.stripe.com/settings/applications under "Integration" -> "Redirects"
   c) Setup webhook Stripe Connect  https://dashboard.stripe.com/test/webhooks . 
        i) Add Webhook url under "Endpoints receiving events from your account" 
            1) "Webhook Detail" > Url as "{domain-name}/stripe-connect-pay/payment-status" bind events "payment_intent.payment_failed", "payment_intent.succeeded".
   ==============]==========================
