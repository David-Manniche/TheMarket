New Features:
    => Avalara Tax API [Execute {domainame}/admin/patch-update/update-avalaratax-cat]
    => Stripe Connect payment gateway
    => S3 bucket implementation.
    => Shipping Packages and Profile Module.
    => Configuration to on/off seller shipping.
    => Shipstation api for shiping rates.  
    => CDN handling based on configuration. 
    => Url Rewritting based on language. 
    => Security Headers for clickjacking, XSS and MIME types. 
    => After Ship API.    
Bugs:
    => 036750 - Seller Shop - Custom URL for shop collections
    => 036504 - wrong stats are displaying on buyer dashboard - pending and total orders
    => 036604 - if product having some offer or special price then on all seller screen> strike is not there on original amount
    => 036596 - Admin is unable to make payment for cod order
    => 037316 - iOS- label issue on using existing number
    => 039438 - Case sensitive product type identifier is not accepted while importing catalog.
    => 039437 - when digital item is added using import/export then there is error for shipping profile and package
    => 039426 - Category - parent identifier having case sensitive issue
    => 040872 - Mark delivered order as completed automatically based on cancellation and return age of product/shop.
    => 041047 - Handling errors during Update Inventory.
    => 042212 - Pagesize issue fixed and restricted to allowed numbers.
    => 042361 - Product temp images are not getting imported.
    => 036754 - Seller/ Admin marks an order as delivered. But Seller has an option to "cancel" after delivery
    => task-66150- Export content encoding issue with Excel.
    => 042958 - Seller >> shipping profile >> shipping to >> if there is no zone then some default image or some default text should be there.
    => 042511 - Shipping rates are not getting display properly at front end.
    => 042954 - "Add zone" should be the tool tip for "add " button
    => 042521 - Shipping profile >> add zone >> add zone name field should not accept only spaces
    => 042989 - Accept cookies functionality enhancement
    => 042991 - Edit status functionalities are different on "category request " and "brand request " listing page at admin end.
    => 042986 - Seller >> request >> when user on that request listing page and he create another request then new request should reflect before we refresh the page.
    => 042985 - Seller >> brand request listing >> Language data gets display instead of identifier name.
    => 042765 - Admin dashboard >> top product >> height of "no record found " section should be same as others.
    => 039093 - login popup having issue in maintenance mode
    => 040647 - admin> seo> image attributes> tags added are not removing 
    => 040907 - Payment is not getting added to wallet
    => 043043 - Root category should be clickable.
    => 038238 - DEMO> on product detail page> on closing time bar and scrolling there is issue
    => 038252 - remove blog short description from admin as it's not listing anywhere
    => 039092 - seller> sales report> same sheet is exporting in normal and date wise
    => 039645 - when admin set some FAQ category as default then on front end active class is not there on that category while questions of that category are listing there
    => 040000 - seller> google feeds> categories don't have scroll bar
    => 040060 - seller> catalog list> on searching item with special character> getting error
    => 041951 - on buyer dashboard> there is some error
    => 041954 - contact us page is flooded with notice

Enhancements:
    => Tax module upgrade
    => Test cases classes enhancements.
    => Compatiblility with php 7.4 
    => User Addresses DB changes.  
    => Displayed "product not available " on home page collections based on location. 
    => Performance updates.
Notes:
   ==========Stripe Connect Installation Notes[:=========
   Composer should be installed on server to run the stripe connect module: composer.json on root of the project has details to download the required libraries in root's vendor folder.

   a) Run command at root of the project to update composer to fetch all required libraries from the root of the project using terminal: composer update
   b) Required to configure callback url as "{domain-name}/public/index.php?url=stripe-connect/callback" inside stripe's web master's account under https://dashboard.stripe.com/settings/applications under "Integration" -> "Redirects"
   c) Setup webhook Stripe Connect  https://dashboard.stripe.com/test/webhooks . 
        i) Add Webhook url under "Endpoints receiving events from your account" 
            1) "Webhook Detail" > Url as "{domain-name}/stripe-connect-pay/payment-status" bind events "payment_intent.payment_failed", "payment_intent.succeeded".
   ==============]==========================
