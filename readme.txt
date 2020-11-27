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
    => 043859 - admin>navigation> pages> edit> getting error
    => 043671 - Admin >> shipping profile >> if the selected location is under any drop down and admin update the profile then "minimum one location required" gets display .
    => 043865 - Buyer >> order details page >. when order status is "shipped" then "track button should get display with " shipped " status only.
    => 043601 - seller >> on shop inventory >> when we click on "inventory data for any language then all drop down gets expand.
    => 043673 - Seller shipping profile >> if we expand any country then there should be some scroll bar .
    => 043674 - Seller >> shipping profile >>if there are multiple zones and when we click on "add rate icon then page should get scrolled to "add rate " box. 
    => 043687 - Shipping profile >> if we click on "save " button multiple times then multiple entries gets generated.
    => 043692 - Admin/seller>> shipping profile >> rates should get display in 2 decimal values even if we edit them .
    => 043864 - Buyer << order details >> when we use wallet as payment method then "paypal wallet " gets display on order details page.
    => 043777 - Admin >> if we delete any collection and then recreate the collection with same name then duplicate entry gets display . 
    => 043776 - Collection >> banner >> when we save banner collection then "successful message should be there.
    => 043775 - Admin >> promotions>> discount coupons>> links >> "Product Not Found? Click Here To Add New Product" should be in the same row.
    => 043845 - seller> product setup> on refreshing page> there is error on top and page is not scrolling
    => 043848 - seller> products> edit> on manipulating product id from url there is error 
    => 043822 - shipping profile> saving without name getting incomplete warning
    => 043870 - Admin >> manage volume discount >> if we enter qty more then stock value then that value should get display in error message .
    => 043869 - Admin >> manage volume discount >> if there is no entry then "no record found " should get display just like others.
    => 043867 - Admin >> special price >> when we edit special price then discount percentage should get display under special price field.
    => 043584 - admin> categories> right side> product count in bubble is not listing properly 
    => 043943 - Admin >> settings >> Reward point setting>> single label being used for two different reward point fields.
    -- -------------TV-9.2.1.20201008-------------------
    => 043566 - when new language is added and any page from footer or header (cms) is accessed then language changes itself
    => 043946 - Seller >> special price >> select all entries >> search something random >> if there is no data then remove button should get disappear. 2. if we clear the search then "remove " button should not be there because all entries are not selected now.
    => 043947 - Manage coupon >> if coupon is of percentage type then admin should not be able to enter more then 100 % in discount value 
---------------TV-9.2.1.20201009----------------------
    => 043938 - on product when wrong youtube link is added then getting corrupted video on front end 
    => 044020 - Seller approval form fields are not editable. Neither can be deleted. 
    => 044021 - Validation error message incorrect on forget password page 
    => 044023 - Session issue with seller signup form.
    => 043990 - Admin >> banner collection >> 1. alignment of image file and "translate to other lang" checkbox is not right . 2. text gets display outside of button
    => 043991 - On thankyou page >> when we choose "pay at pickup " as payment gateway then error gets display on thankyou, order details page .
    => 043994 - Seller >> my subscription >> some error is there. 
    => 043996 - Seller/ admin >> some error should get display if we didn't select any slot.
    => 043995 - Seller >> pickup address >> If there is no slot selected then some error is there on edit address.
    => 043992 - On Checkout >> when we select pickup address then label should not be "select address".
    => 043988 - Seller >> on shipping profile >> products >> if we click on "save changes " without entered product then blank entry gets display there 
    => 043993 - search >> when we click on search field then blank bar gets display.
    => 043810 - Admin should not be able to delete tax category if any product is linked with that category . 
-------------------TV-9.2.1.20201012-----------------------
    => 043719 - Seller shipping profile >> If we have condition for price then max value has to be multiple of product price then only this shipping will get display at checkout.
    => 043783 - if "Product Inclusive Tax" setting is on then tax should not get display on cart and it should get display only after selecting billing address. 
    => 044047 - option setting getting hide on seller end
    
---------------------TV-9.2.1.20201013------------------------    
    => 043717 - If Weight condition match in shipping then "no shipping charges" gets display there.
    => 044048 - seller dashboard> there is order cancellation tab which containing count of order cancel by buyer and seller but displaying request of buyer only
    => 044060 - If social media links are added by admin/seller without http / https platform doesnot redirect user to social media page instead redirects user to domainname.com/socialmediaurl.com
    => 044153 - when user is on cart/checkout page then mini cart shouldn't open 
------------------TV-9.2.1.20201014-----------------------
    => 044117 - Checkout >> bank transfer is not working.
    => 043676 - Admin >> manage collection >> layout images are not there for "blog layout " and " mobile banner layout ".
    => 043778  if we add same state in two different rules then some error should get display at admin end 
    
    => 044185 - when buyer places order (multi item) with reward then on parent order detail page> there is some issue
---------------------TV-9.2.1.20201015----------------------
    => 043787 - If there is some special price and "price include tax " setting is on then product price is less then entered special price
---------------------TV-9.2.1.20201016----------------------
    => 044240 - on Checkout >> if there is only digital product then tax is not there
    => 043675 - Admin >> pickup address >> Add >> slot timings >> there should be some space between radio button and text. 
    => 044231 - Seller >> sales report page is not opening.
    => 044232 - Seller >> order details page >> comments >> if buyer request for cancellation then message at seller end is not right .
------------------TV-9.2.2.20201019-------------------------
    => 044213 - when buyer places order and returns the whole order then there is difference of $0.01
    => 039968 - EAN/UPC code field is not there on catalog level when there is no option, and also search based on this is not working.
    => 044241 - Seller >> shipping profile >> edit any rate >> delete that rate >> that rate should not be opened at right side .
    => 044236 - if admin cancel any order from buyer order page then status of that order is "payment confirmed " at buyer, seller and admin (seller order ).
    => 044250 - Admin >> Seller orders >>If we choose pickup then shipping details should not be there.
    => 044233 - when customer places pickup order then in admin> customer order> pickup details are not there
    => 044237 - when an order having multiple items and both are on pickup then on parent order detail page time slot is not listing
    => 044280 - When guest user add product in cart then there is some fatal error. 
    => 044297 - when order is on pickup then on checkout step 2 it's displaying shipping
    => 044261 - when vol. discount is applied on order then on success screen it's displaying it as rewards
    => 044260 - when physical an digital items are ordered together then on success screen> under shipping method there is another field for digital which is blank
    => 044309 - On category page >> broken image gets display if there is no image selected in admin panel.
    => 044307 - Seller >> cancel any order >> shipping method is not getting display on cancellation page .
    => 044301 - On thankyou page >> volume discount and reward points are combined and "rewards" gets display as label for that.
    => 044251 - If there are digital and physical both products in one order and we choose pickup then pickup details are not getting display on thank you screen.
    => 044237 - when an order having multiple items and both are on pickup then on parent order detail page time slot is not listing
    => 044181 - after order is placed then in admin> order detail page> shipping details are not proper
    => 044283 - buyer> parent order> table alignment issue 
    => 044296 - seller> return request> when there is no file attached still then there is attached file option and on clicking there is blank page 

---------------------TV-9.2.2.20201020----------------------    
    => 044313 - if admin disable the setting "Enable Linking Shipping Packages To Products" then fatal error is there on "shipping " tab of catalog.
    => 044316 - If we enabled "Shipped By Admin Only" setting then fatal error is there on shipping tab of catalog.
    => 044338 - Buyer >> view order return request >> submit button should not be in white color
    => 044349 - when multiple items are there in an order (pickup+digital) then on parent order> table distorts
    => 044317 - After order is placed on pickup then full pickup address is not there on order in admin and buyer
    => 044315 - when case is of pickup then on buyer end (on checkout)> name and address label is not listing on address
    => 044303 - when cart having digital item then on checkout it's displaying COD option which is not applicable
    => 044362 - user can access another user order details just by changing order id from url
    => 044228 - If we Quit the seller registration process on activation form and then re check the seller registration form then here is some error;
    => 040055 - wrong commission is getting charged from seller.
    => 044385 - Collection layout issue on view all page
    => 044380 - When guest user click on "clear cart" then "Unauthorized Request" gets display    
    -----------------------TV-9.2.2.20201021-----------------
    => 044325 - if order amount is in fraction then rewards applicable are displaying as fractional 
    => 044414 - admin> orders> withdrawal request> filters> on entering character in amount field and proceeding further getting error
    => 044234 - delivered status is not there on admin when order is on pickup
    => 044435 - when any keyword is searched then on click of cross from search bar it's not washing the keyword entered
    => 044434 - search> when there is no result found the don't display any suggestion list unless there is history
    => 044433 - only search result should get removed on click
    => 044420 - on search >>search suggestions section should get closed if we click anywhere on screen
    => 044052 - after exporting file in arabic language data is deformed in sheet unless UTF8 is selected
    
    => 044380 - When guest user click on "clear cart" then "Unauthorized Request" gets display
    => 044379 - If order status is "pay on pickup " then it stays the same even if we cancel the order.
    => 044403 - Cart >> if all products are under "save for later section " and user move them to bag then items should get display in cart and currently they gets display in cart after refreshing the page .
    => 044407 - buyer> profile> request data> gdpr popup> click here lick is not highlighted
    => 044406 - user profile> when there is no image still then remove option is there
    => 044400 - Admin >> order details page >> order payment history >> in case of bank transfer >> rejected or approved should get display .
    => 044402 - Admin >> settings >> checkout >>display time slots after orders >> there is a validation of minimum 2 hours but when we save it blank then it shows 0 hours
    => 044399 - Admin >> manage currency > "default " is not properly getting display in circle
    => 044378 - When we choose pickup option then some details are missing in pickup address on thankyou screen ;: 1. state and country code gets display instead of name . 2. mobile number is missing .
    => 044392 - On order details page >> if we choose "pay at pickup " then cod gets display . 
    => 044405 - buyer> wishlist> when there is nothing in shop wishlist then message is not in center 
    => 044395 - On Thankyou screen >> "-" sign should be there with discount amount .
    => 044424 - when we add seller's shipping on admin's catalog then all products (seller's +admin's ) starts getting display on "marketplace product" section.
    => 044451 - Home page >> FAQ collection >> Show more show less should be highlighted or underlined
    => 044438 - Home page >> Testimonial collection >> "View all " button is not working. 
    => 044443 - Admin >> add testimonials >> there should be some text limit for "testimonial text " .
    --------------TV-9.2.2.20201023-------------------------
    => 044442 - on searching item using tag there is no result found
    => 044418 - Fulfillment method is missing from Inventory setup.
    => 044411 - when seller adds inventory of admin catalog and opts shipping on own end and if admin changes shipping mode then it impacts seller inventory
--------------TV-9.2.2.20201023-------------------------
    => 044461 - TAx is not listing on buyer end in invoice
    => 044370 - on order invoice> product option is not displaying.
    => 044476 - when bank transfer payment is split in multiple amounts then getting invalid access
    => 044482 - when order is of pickup then on order detail page it's listing shipping price column and pickup
    => 044479 - Brand Collections >> view all >> identifier should get display for brand name if there is no language data added in admin panel .
    => 044481 - when same product is added by multiple sellers then in Seo area listing 3 times same item
    => 044478 - when there is no image added from user and when user tries to upload image then it's treating dummy image as uploaded
    => 044502 - when order is on pickup then on order detail page> country and state code is listing instead of name
    => 044477 - when mini cart having only item remains after deletion then getting scroll with product
    => 044468 - Seller >> inventory setup of digital products >> there is some warnings on download page . 
    => 044497 - extra categories are listing on page which are not even added
    => 044506 - If admin cancel any order from "orders" page then "cancelled " should be the status instead of "order payment status cancelled
    => 044503 - When we add product in cart which has some addons then those addons should get added with the product.
    => 044473 - when order having only digital item then on checkout step 2 it's displaying shipping
    => 044470 - when order is of pickup then in admin> customer orders> delivery field is listing which is not required
    => 044322 - Bank details are not there after order is placed and user opts payment from order detail page
    => 044562 - after clearing search> suggestion list is not disappearing
    => 043142 - "Buy together" products under promotions, items do not appear on the list after creating them if no brand
-------------------------TV-9.2.2.20201026---------------------------
    => 044542 - In case of Stripe connect>>if cancellation or refund request generated then "transfer to wallet " option should not be there at admin end 
    => 044505 - If admin disable " Linking Shipping Packages To Products" then weight fields should not get disappear from shipping tab .
    => 044499 - when we add only digital product in cart then it shows "empty cart " on checkout page .
    => 044500 - When we add one digital and one physical product in cart then it shows "no shipping available " for digital product and user is unable to proceed with order
    => 044351 - Seller >> orders >> cancel order >> Total amount should get display at seller end and discount should not be deducted from that amou
    => 044368 - Seller >> pickup address >> edit slots >> when we add multiple slots and save the changes then it shows only last slot on edit settings.
    => 044567 - hide the print option on return request on buyer end
    => 044576 - when full text search is enabled then shop is flooded with errors
    => 035803 - when sub seller have read only permission to buy together and related items then on hover there is anchor tag and tooltip displaying click to edit
    => 044475 - Checkout >> home page >> Delete products>> cart gets empty and it shows "Your shopping cart is empty!" with "0" amount and if we proceed with that then some products are still there.
    => 044474 - When we come back on home page from checkout then it shows "invalid products " on deleting for those products which were not on checkout
-------------------TV-9.2.2.20201027---------------------
    => 044605	Auto create default shipping profile and linked products
    => 044367 - seller >> pickup address >> "+" icon next to slots is not getting display in first time
    => 044371 - add money to wallet> stripe form screen> cancel button is not working
    => 044467 - pickup and payment cancel orders are coming under canceled orders on seller end
    => 044383 - if ship station is on and we try to buy admin's product then some error is there on checkout. 
    => 044373 - add money to wallet> using paypal> there is error
    => 044623 - during withdrawal restrict the numeric values only as it's getting placed with random values and on accepting from admin getting json
    => 044619 - when add product setting is disabled from admin for seller then some settings on front end are not in use
    => 043526 - when tax is combined then values field shouldn't be left blank
    => 044645 - when conversation detail page is opened then message tab is not highlighting on left side
------------------TV-9.2.2.20201028-------------------
    => 044621 - product listing> pagination button are not aligned properly
    => 044339 - admin >> cancellation request >> total reward points gets display there instead of reward points for that particular child order.
    => 044634 - on order print> default fav icon is coming
    => 042447 - Admin >> Add zone >> language data section is according to old versions .
    => 044022 - Create a label for this button on forget password page 
    => 044370 - on order invoice> product option is not displaying
    => 044686 - seller end> order invoice> invoice date don't have any data 
    => 042577 - if seller didn't add pickup address then "pickup " option for his products should not be enabled and "shipped only" should be the fulfillment method.
    => 044624 - shop> reviews> flooded with errors
    => 044591 - when seller requests for catalog then in admin> specification group field is not there
    => 044620 - buyer> share and earn> invite through email> button is not proper
--------------------------TV-9.2.2.20201029-----------------
    => 044714 - Seller >> shipping profile >> rates > if we save conditions without selecting the radio buttons then some json error is there. 
    => 043774 - when seller try to change order status to "shipped" without checking the "self shipping " checkbox then it takes forever to process
------------------------TV-9.2.2.20201102-----------------------
    => 044732 - mark disable the states while creating zones when it's already used for a country
    => 044384 -  Order summary is not getting display at guest checkout before adding the address
    => 044446 - Content block >> on home page collection of content block should be in the frame 
    => 044755 - when we buy one digital and one physical product and choose pickup option then pickup address is not getting display on thankyou screen.
    => 044684 - in search field> string entered and cross button are overlapping
    => 044702 - Mobile devices >> on manage address >> "save changes " button should be next to "cancel " button
    => 044758 - discount availed by buyer is not displaying on order detail page
    => 044752 - admin> order status management> color is not distinct able by name
--------------------TV-9.2.2.20201104--------------------------    
    => 044607 - If ship station is enabled and we add admin's product in cart then those products gets display 2 times.
    => 044886 - while adding shipping rates in profile> it warns to select condition
    => #044607 - If ship station is enabled and we add admin's product in cart then those products gets display 2 times.
    => 044913 - When fetching avalara category getting error
    => 044665 - Ipad >> on shipping profile >>text is not properly getting display in button.
    => 044671 - Ipad >> shipping package >> text in button should get display properly.
    => 044674 - user profile> paypal payout> save button is not aligned with fields
-----------------TV-9.2.2.20201105--------------------------    
    => 044915 - getting fatal on front end on enabling taxjar
    => 044909 - issue on importing category from admin
    => 044942 - admin> categories> identifier is displaying in list instead of name
    => 044944 - admin> seller orders> shipping is displaying as "awaiting shipment" for digital item order
    => 044948 - admin> subscription orders> detail page> fatal is there
    => 044946 - admin> orders> deleted orders> search button is not aligned properly
-----------------TV-9.2.2.20201106-------------------------- 
    => 044941	when tax is there for specific category still then rest of the world is getting applied
    => 044951 - admin> return requests detail page> replace back button with text
    => 044675 - seller> custom catalog request> under status column> date is wrapping
    => 044697 - ipad >> manage address >> text should get display properly in button.
    => 044702 - Mobile devices >> on manage address >> "save changes " button should be next to "cancel " button
    => 044969	when sub seller having read only permission for shipping profile then he can't see profiles created by seller and details under shipping profile
    => 044966	create button is there for shipping profile to sub seller when read only permission is granted to him
    => 044677 - seller> shipping packages> clear search button text is not in center
    => 044671 - Ipad >> shipping package >> text in button should get display properly.
    => 044950 - seller > my subscription> search button is not proper
    => 044369 - In Product inclusive tax setting is on then tax should not get deducted before selecting the address
    => 044896 - Sorting needed for items linked in collections
    => 044753 - seller dashboard> graph is not proper in RTL mode 
    => 044933 - Add multiple category with same name but different identifier 
    => 044780 - seller> while adding product> description editor> on clicking full screen from Arabic language- description field get hids from English too and editor option disappears 
    => 044953 - admin> return requests detail page> download button is coming there while no attachment is there
    => 044916 - Unable to link tax category with product 
    => 044932 - admin> requested categories> when there is no data then "No record found template is not proper"
    => 044345	Commission charges are not right if "Commission Charged Including Tax" setting is disabled and we refund some qty.
    => 044337	Seller >> credits >> commission charges are not right in case of refund and if "Commission Charged Including Shipping" is disabled.
    => 044911 - brands don't have description but in import/export there is description field
    => 044982 - seller> sub seller permissions> apply button is not aligned with drop down
    => 045008 - marketplace link on seller dashboard is redirecting to different link from the actual link
    => 044912 - Brand is getting added but getting warning along with it
    => 044884 - when banner layouts are not added from admin in collection still then they are populating on advertiser end
    => 045000 - seller> product inventory stock status> variant name needs to be there- as distinction is not there in various items
    => 045001 - when review on digital item is placed then shipping and package option are coming which are not available for digital items
    => 044068 - emails are not there when order is on bank transfer
    => 045044 - home page> faq> on clicking second category > all faq's are opening collectively while the same is not happening with first category
    => 044715	On Checkout >> if there are rates for shipping address's country then rates of "rest of the world " should not get display there.
    => 045009 - When we use tax jar then "invalid tax category " gets display on purchase of digital products
    => 036640 - while linking product with tax category> some of them having long text and not wrapping
    => 045126 - Some items not available for pickup layout and functionality
    => 036637 - when combined tax (GST) is used then in email and order detail page> getting tax(0)
    => 044750 - hide the cancel button from customer orders in admin as it's creating confusion on front end
    => 035347 - if related products (seller 2) are added through import/export by seller 1 then they are getting listed on front end
    => 045166 - on shop details >> when we save search then some json error is there.
    => 045169 - Product details page >> if there is no description then blank description box should not be there.
    => 045194 - getting error on product detail page below ratings
    => 043778 - if we add same state in two different rules then some error should get display at admin end
    => 045179 - While attaching category in catalog category coming after typing the test
-------------------TV-9.2.2.20201111----------------------
    => 045228 - cart Qty update on cart listing page 
    => 045236 - admin> seller orders> price filters are not working properly
    => 045240 - admin> subscription orders> filters> user name > on clicking user name from suggestion there is error
    => 045239 - when subscription coupon is created then on seller end it's listing - min order 0.00 which is not required as there is no such field while creating coupon
    => 045269 - Paypal: transaction issue - capture pending in case of new seller registration on payapl.
    => 045316 - Subscription checkout page - discount coupon is not visible
    => 042589 - When we place order >> on Thank you screen >> 1. order id should be clickable and user should get redirect to orders
    => 045188 - when banners are added in collection the location is not required on advertiser end
------------------TV-9.2.3.20201116----------------------------    
    => 045312 - when seller having enough money then while adding promotion there is error for budget
    => 045307 - as free shipping is not there on catalog but lies in import/export
    => 045317 - seller end> messages> name of shop is listing twice in front of text box
    => 045315 - Shipping tax code is wrong in case of avalara tax plugin
    => 043189 - some variation in price when product price is incl. tax and multiple qty are added to cart
--------------------TV-9.2.3.20201117------------------------   
    => 045217 - Price inclusive tax handling for Tax api's.
    => 045358 - while purchasing subscription plan on applying coupon> button is not proper
    => 045338 - when any product having multiple options and accessed inventories accessed through catalog then serial no. is displaying in minus
    => 045361 - error n [payment through paypal credit card
    => 045365 - when product having variants then under promotion it's listing the name without option, so unable to distinguish the item
    => 045390 - Update required in seller inventory report.
    => 045416 - on order details page >> shipping charges should be properly aligned.
    => 045418 - On Sales report >> in case of rounding off amount >> Some warning message is there 
---------------TV-9.2.3.20201118------------------------    
    => 045415 - cancellation age, return age, cod etc options are not listing on product detail page
----------------TV-9.2.3.20201119-------------------
    => 045366 - seller dashboard> sales> under sales discounted amount is displaying which is offered by admin
--------------------TV-9.2.3.20201120---------------------
    => 045241 - admin> reviews> filters> review for filter suggestion is not working
    => 042957 - Seller >> when we import any file then sometimes "error occured " gets display.
    => 045510 - Admin dashboard - Threshold products listing
    => 045517 - Price filter range and their search with options.
---------------TV-9.2.3.20201121-----------------------
    => 045577 - guest user can't add digital item to cart 
    => 045647 - on reward point sms variable coming of {reward_point_balance}
    => 045648	On new order vendor not getting sms notification
    => 045435 - android- wallet> add money> transfer bank and pay at store option are coming
    => 045448 - android- during checkout> shipping is not listing on cart review screen
    => 045426 - android- on clicking saved cart option > item removes from cart but not listing on app
    => 045413 - android-under recommended products> product image listing is jittery
    => 045670 - Export brand media files issue
    => 045533 - Discount coupons > Link with one shop >> Add products from different shops in cart >> Coupon should get applied to only that particular shops products and not on whole order amount .
--------------TV-9.2.3.20201124-------------------------
    => 045578 If there is 100% discount and we have tax(avalara) on shipping only then there is some change in round off amount if we choose product price inclusive tax 
    => 045691 - when adding product tag with special character then tags not coming
    => 045709 - When we place withdrawal request then details are missing in email. 
    => 045725 - Admin >> collections >> layout images are not right for "Shop Layout1" and " Category Layout2" .
    => 045732 - Category image cropper not coming in admin side
    => 045747 - category layout 1 is not populating on front end
    => 045709 - When we place withdrawal request then details are missing in email.
    => 045769	Admin dashboard -subscription earning stats
    => 045799 - Import export issues catalog media for seller products from admin

Enhancements:
    => Tax module upgrade
    => Test cases classes enhancements.
    => Compatiblility with php 7.4 
    => User Addresses DB changes.  
    => Displayed "product not available " on home page collections based on location. 
    => Performance updates.
    => Advanced search UI and Auto Suggestions.
    => Category Listing Page UI 
    => Notification Emails on/off conf setting. 
    ------------TV-9.2.1.20200925------------------------
    => Enhance discount module added brand and shop discounts    
    ---------------TV-9.2.3.20201121--------------------
Notes:
    
    Composer :

        => Composer should be installed on server to run the stripe connect module: composer.json on root of the project has details to download the required libraries in root's vendor folder.
        => Run command "composer update" at root of the project to update composer and fetch all dependennt libraries: 

    Stripe Connect Installation :

        => Required to configure callback url as "{domain-name}/public/index.php?url=stripe-connect/callback" inside stripe's web master's account under https://dashboard.stripe.com/settings/applications under "Integration" -> "Redirects"
        =>  Setup webhook Stripe Connect  https://dashboard.stripe.com/test/webhooks . 
                i) Add Webhook url under "Endpoints receiving events from your account" 
                    1) "Webhook Detail" > Url as "{domain-name}/stripe-connect-pay/payment-status" bind events "payment_intent.payment_failed", "payment_intent.succeeded".
   
    Default Shipping profile setup:
       
       To Bind Products and Zones To Default Shipping Profile, Open <site-url>/admin/patch-update/update-shipping-profiles
       To Bind Zero Tax category as default if "Rest Of The World" country is not bind,Open <site-url>/admin/patch-update/update-tax-rules

    Please replace tbl_countries, tbl_states from db_withdata.sql.