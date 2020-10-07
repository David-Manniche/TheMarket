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
    => Design Manageability on homepage   
    
    ------------TV-9.2.1.20200925------------------------     
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
	=> 043126 - On language data tab of blog post>>Fatal error is there.
    => 043186 - Admin >> Add rules >> Blank fields should not get display if admin save some blank entries for "tax component"
    => 043182 - Seller >> on view tax category >> "tax category" identifier gets display instead of tax category name in english language .
    => 043180 - Admin >>Tax structure >> add >> Error is there.
    => 042768 - Seller >> shop inventory >>there should be some space between all three buttons and title bar
    => 043100 - buyer> add money to wallet> on blank submitting form there is processing which shouldn't be
    => 043101 - After amount has been added to wallet then on success page there is message order has been placed instead of transaction
    => 042455 - Seller >> add shipping profile >> place holder is missing.
    => 042520 - Shipping profile >> manage rates >> cancel button is not working. 
    => 042576 - Shipping summary >> Rates should be in max 2 decimals.
    => 042597 - On order details page >> when we use wallet then "+" sign gets display in payment method. 
    => 042637 - Add rules >> if admin select country then by default "all states" should be selected.
    => 037364 - when seller adds inventory data - selects stock maintain level, track inventory to YES and set alert quantity then data is not getting uploaded in bulk import/expor
    => 042960 - Admin >> brands >> if we delete any brand and then recreate that same brand again then duplicate entry error is there.
    => 042517 - Seller >> manage permissions for subsellers >> permission for "manage shipping " and " google shopping feed" are missing.
    => 043202 - when seller submits catalog request then there is 404 error 
    => 037364 - when seller adds inventory data - selects stock maintain level, track inventory to YES and set alert quantity then data is not getting uploaded in bulk import/export
    => 040190 - when user signup through email id (verification pending) and then uses the same email id from social login then account gets login and email id should get verified autimatically
    => 037325 - when buyer registers through phone number and then requests to become seller then on seller request from there should be email field to complete the process
    => 040623 - when user sign up through social (phone number) then it lands on configure email page and from there existing email is allowing
    => 042341 - admin> import /export> option> getting option value in drop down
    => 043114 - when product is not available for shipping then there is blank warning.
    => 043183 - Seller >> marketplace products >> there is some error on add seller shipping page .
    => 043127 - Seller should not be able to import special price file with price less then minimum selling price .
    => 043050 - On Shipping profile >> manage rates section still gets display there even if we delete "zone "
    => 043266 - Display parent category if products bound to any of child category 
    => 043050 - On Shipping profile >> manage rates section still gets display there even if we delete "zone " 
    => 042767 - Import / export >> settings >> Some settings needs to be removed . 
    => 043134 - Seller can add one extra inventory using import even if all inventories are already existed.
    => 043245 - Admin >> Add first rule >> All tax structures gets display under "rule name " box even if admin didn't selected any tax.
    ------------TV-9.2.1.20200925------------------------

    => 043493 - Meta title is not working
    => 043497 - Colour of Subscribe section on blog page doesn't update
    => 043109 - while adding pickup details> slots> there is some issue with time
    => 042650 - On cart >> when there are products in shipped and pickup both then amount should be of those products only which are selected under " ship my order " or " pickup in store"
    => 042602 -  If we come back on home page from checkout and there are only shipped and only pickup products then products on checkout are the only products which gets display on cart popup
    => 042595 - On Thankyou >> if we choose pickup then shipping method section shows a blank bar

    ----------------- TV-9.2.1.20200930------------------------

    => 043678 - Home page >> brand collection >> default image should be there if there is no media file selected for brand instead of some text 
    => 043530 - add money to wallet> listing cod option
    => 043531 - while purchasing subscription there is error
    => 043596 - When we place any order with pickup then there are some errors on checkout page .
    => 043594 - Order details page >> invalid access gets display if admin or seller try to change order status .
    => 041966 - seller> dashboard> there are errors
    => 043517 - when language other than EN, AR is selected then getting error on header
    => 043662 - tax category added by admin is listing twice on seller end
    => 043528 - seller> sales report> detail page> back button alignment is not proper
    => 043582 - Seller >> meta tags >>> seller is unable to add data in "other meta tags " field.
    => 043595 - Seller >> manage permission for sub seller >> "request " tab should be there under "shop" on permission page .
    => 043521 - on login to buyer account> getting error on logo
    => 043585 - when category is requested then message needs to be different
    => 043602 - Blank print preview is there for order on detail page
    => 043603 - seller invoice print> only logo is listing
    => 043610 - Buyer >> when we try to become a seller then some error is there.
    => 043597 - On product >> If we inactive any product then that product should not get display in "add-ons ".
    => 043599 - Seller >> shipping profile >> images should be in the center of box. 
    => 043643 - getting error while adding brand
    => 043651 - while adding digital item> downloads> save button is not proper
    => 043672 - seller registration >> login button is not proper. 
    => 043586 - when same shipping package is added again then message needs to be corrected
    => 043640 - wrong time slots are getting created in admin
    => 043638 - admin> pickup address> slots> when time is selected for all days then on editing it's displays the same time for individual days selected
    => 043702 - when product is not available for selected location then from all seller screen it's available for user and getting added to cart
    => 043710 - during checkout> getting error in rewards
    => 043711 - when admin approves cancellation request then there is error
    => 043593 - When we add amount in wallet then some changes needs to be done on "thank you" screen 
    => 043523 - buyer> orders> cancellation requests> filters> dates> date is not visible on 1366x768 resolution
    => 043600 - Admin >> add brand >> when we add brand then admin is unable to access "language data " and " media " tabs 
    => 043606 - While checkout if we select "pay on pickup then status should be "pay on pickup " instead of " cash on delivery .
    => 043777 - Admin >> if we delete any collection and then recreate the collection with same name then duplicate entry gets display . 
    => 043776 - Collection >> banner >> when we save banner collection then "successful message should be there.
    => 043775 - Admin >> promotions>> discount coupons>> links >> "Product Not Found? Click Here To Add New Product" should be in the same row.
    => 043845 - seller> product setup> on refreshing page> there is error on top and page is not scrolling
    => 043848 - seller> products> edit> on manipulating product id from url there is error 
    => 043822 - shipping profile> saving without name getting incomplete warning
    => 043870 - Admin >> manage volume discount >> if we enter qty more then stock value then that value should get display in error message .
    => 043869 - Admin >> manage volume discount >> if there is no entry then "no record found " should get display just like others.

Enhancements:
    => Tax module upgrade
    => Test cases classes enhancements.
    => Compatiblility with php 7.4 
    => User Addresses DB changes.  
    => Displayed "product not available " on home page collections based on location. 
    => Performance updates.
    => Advanced search UI and Auto Suggestions.    
    ------------TV-9.2.1.20200925------------------------     
Notes:
   ==========Stripe Connect Installation Notes[:=========
   Composer should be installed on server to run the stripe connect module: composer.json on root of the project has details to download the required libraries in root's vendor folder.

   a) Run command at root of the project to update composer to fetch all required libraries from the root of the project using terminal: composer update
   b) Required to configure callback url as "{domain-name}/public/index.php?url=stripe-connect/callback" inside stripe's web master's account under https://dashboard.stripe.com/settings/applications under "Integration" -> "Redirects"
   c) Setup webhook Stripe Connect  https://dashboard.stripe.com/test/webhooks . 
        i) Add Webhook url under "Endpoints receiving events from your account" 
            1) "Webhook Detail" > Url as "{domain-name}/stripe-connect-pay/payment-status" bind events "payment_intent.payment_failed", "payment_intent.succeeded".
   ==============]==========================
