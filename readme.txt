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
    => 043085- If special character present in category page url and then on other page by using pagination - records not found.
    => 042991 - Edit status functionalities are different on "category request " and "brand request " listing page at admin end.
    => 042986 - Seller >> request >> when user on that request listing page and he create another request then new request should reflect before we refresh the page.
    => 042985 - Seller >> brand request listing >> Language data gets display instead of identifier name.
    => 042765 - Admin dashboard >> top product >> height of "no record found " section should be same as others.
    => 039093 - login popup having issue in maintenance mode
    => 040647 - admin> seo> image attributes> tags added are not removing 
    => 040907 - Payment is not getting added to wallet
	=> 043056 - If admin make some changes in email footer then those changes are not reflecting in emails 
	=> 042763 - Tax category >> view rates >> currency symbol is missing.
	=> 036806 - Same label is calling for expiry date in stripe checkout.
	=> 036867 - Place an COD order >> when admin try to complete that order then some json error gets display and it gets completed when we try second time.
	=> 040000 - seller> google feeds> categories don't have scroll bar.
	=> 041950 - user account (post sign up)> my profile> getting warning
    => 043043 - Root category should be clickable.
    => 038238 - DEMO> on product detail page> on closing time bar and scrolling there is issue
    => 038252 - remove blog short description from admin as it's not listing anywhere
    => 039092 - seller> sales report> same sheet is exporting in normal and date wise.
    => 043041 - Fatal error is there when we hit cron url.
    => 039092 - seller> sales report> same sheet is exporting in normal and date wise
    => 039645 - when admin set some FAQ category as default then on front end active class is not there on that category while questions of that category are listing there
    => 040000 - seller> google feeds> categories don't have scroll bar
    => 040060 - seller> catalog list> on searching item with special character> getting error
    => 041951 - on buyer dashboard> there is some error
    => 041954 - contact us page is flooded with notice
    => 041964 - seller sign up form> getting error
    => 041966 - seller> dashboard> there are errors
    => 041967 - seller > manage shop> error is there
    => 041969 - error while product setup
    ----------TV-9.2.1.20200916----------------
    => 042441 - php 7.4 Compatiblility issues.
    => 042450 - Seller >> shop details >> when we select india then states are not getting display in drop down even if there are states in cms section.
    => 042508 - When we signup as buyer then "invalid request parameters" gets display in error message
    => 042573 - On Checkout >> when we click on "available balance" checkbox in order to pay using wallet then request takes forever to process.
    => 042644 - Admin >> manage seller orders >> Some errors are there in search section.
    => 042645 - Admin >> Custom products >> there are some errors on add and edit .
    => 042646 - Admin >> options >> Add >> There is some error
    => 041432 - Social media icons in footer 
    => 042737 - when buyer places order then on order detail page> country name is not listing on buyer and seller end
    => 042736 - after placing order> on success screen> country code is listing instead of country name
    => 042647 - On cart >> if we click on save for later " on last product in cart then instead of showing empty cart user should be able to see save for later section.
    => 043107 - Registration shows an error message but proceeds with the new registration
    => 042732 - seller> on accessing tax categories> tab is not highlighting in side bar
    => 043102 - after adding money to wallet using stripe there is no transaction id in transaction list
    => 043110 - admin> shipping mgmt> order level> on searching product there is no scroll
    => 043113 - when seller tries to add shipping for admin catalog then there is error
    => 042445 - On Shipping profile and some other pages >> back button should be proper.
    => 042638 - Affiliate dashboard >> Some errors are there.
    => 042591 - On shipping profile >> there should be some character limit for product name.
    => 042451 - View shop >> logo of shop is not getting display there.
    => 042456 - Admin >> add tax rude >> "cancel " button is not working.
    => 042490 - On Shipping profile >> when we delete any product then that product is not getting disappear from shipping profile product section.
    => 042509 - On Special price, volume discount, related products and buy together products page >> when we enter product name then product's variants should also get display there with product name.
    => 043142 - "Buy together" products under promotions, items do not appear on the list after creating them if no brand
    => 042514 - Admin >> zone > admin is unable to save zone name in English or "Arabic " language. 
    => 042522 - Shipping profile >> add product >> product identifier gets display instead of product name in selected product listing. 
    => 042567 - Name and label of pickup address are not getting display on buyer end while selecting pickup address.
    => 042588 - Seller > marketplace product > view any product >> only one image gets display there even if there are more .
    => 042596 - Button is in white color on some pages 
    => 042643 - Admin >> manage order returns >> Some warning gets display in search section.
    => 042761 - Admin >> order cancellation request >> when admin approve any cancellation request then some confirmation message should get display.
    => 043173 - Options appear on the drop down when adding speical price, volume discount, buy together products, related products.
    => 042514 - Admin >> zone > admin is unable to save zone name in English or "Arabic " language.
    => 042768 - Seller >> shop inventory >>there should be some space between all three buttons and title bar
    => 043100 - buyer> add money to wallet> on blank submitting form there is processing which shouldn't be

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
