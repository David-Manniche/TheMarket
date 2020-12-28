Multivendor - Released Version : RV-9.3.0

New Features :
 
    => ShipStation Shipping API
        - Fetch live rates from shipping carriers configured
        - Create shipping labels & generate tracking numbers for orders from shipping carriers configured 
        - Create shipping orders from shipping carriers configured

    => AfterShip Tracking API 
        - Track orders with shipping orders created outside ShipStation API. Admin will have to add tracking number manually and share tracking url.  
        - Admin can map ShipStation carriers to AfterShip carriers, in order to track shipping orders generated via ShipStation.
        - If ShipStation API is not enabled then admin can add tracking numbers along with shipping carrier to track shipments. 

    => Stripe Connect 
        - API automatically creates customers at stripe account along with saving their cards. Cards for a user can be managed from the Saved card section on the buyer dashboard.  
        - API will split the charged order amount in real time between multiple sellers and admin.  
        - API will refund the amount to the buyer and create reverse charges for sellers & admin.  
        - Wallet will be unavailable when using stripe connect but all transaction logs will be maintained in the wallet.
        - If discount coupons or reward points are used then the system will generate a new transaction from the admin’s account to credit sellers in equal proportions. These transactions will be reversed in case of a refund/cancellation in addition to the original order transaction.  

    => Tax Jar API (https://www.taxjar.com/)
        - Real time tax rates are fetched based on categories imported and assigned to products. 
        - Nexus are not handled in the API and shall be a SDNEL customization for clients. 

    => Avalara API
        - Real time tax rates are fetched based on categories imported and assigned to products. 
        - Nexus are not handled in the API and shall be a SDNEL customization for clients.

    => Location services 
        - Manageable Geo-Location setting for the admin to turn on/off this setting.
        - Admin can set the product listing & search on the platform based on the following  
            => List/Search products by delivery address 
            => List/Search products by radius in kilometers 
            => List/Search products based on pickup/current location 
        - Products that are not available for a location will have a tag displaying “Not available for your location”. Add to cart functionality will not be available for products that are not available at a location. 

    => Pickup 
        - Admin can define their fulfillment method (Ship only, pickup only, both) in the settings module. 
        - Seller can define their fulfillment method (Ship only, pickup only, both) in the settings module.  
        - Admin/Seller can define multiple pickup addresses and time slots 

    => Invoicing Module
        - New seller tax invoice module with manageable 
            => Invoice number
            => Management to display tax categories on the invoice 
            => Management to add mandatory government information on invoices   

    => New checkout module 
        - New cart page for buyer to select fulfillment method between ‘Ship’ & ‘Pickup’
        - Buyers can also add items to the ‘Save for later’ list. These items will always stay in cart and will be available for checkout. Buyer will have to be a registered user to use this functionality. 
        - System shall show errors/warnings on products based on their fulfillment method. Buyer can resolve these warnings or continue the checkout process.   
        - All payment gateways that do not require a redirection are now accessed in the checkout module rather than navigating to a new page. 
        - New order confirmation page with order details.
 
    => New Payment Gateways 
        - EBS
        - mPesa 
        - PayNow
        - DPO 
        - PayStack


Enhancements :

        => Performance & Security Updates
            - S3 bucket compatibility 
            - CDN compatibility 
            - Security Headers for clickjacking, XSS and MIME types. 
            - SQL query optimization 
            - Improved performance with separating the labels management files.
            - PHP 7.4 compatibility  

        => Shipping Module Enhancements 
            - Admin can define shipping packages. These packages will be linked at the product level.  
            - Admin/Seller can define Order level, item level shipping or a combination of both for products.
                => Order level shipping will be defined in the system by default and all products shall belong to this profile
                => Item level profiles can be created by adding products to the profile. Any product can below to one such profile at any point of time. 
                => Seller/Admin can define profiles under which zones are created. These zones are created based on location (Country & State). Multiple rates can be defined for each zone. 
                => Rates can also have conditions set on ‘order/item weight’ & ‘order/item price’ ranges.

        => Tax Module Enhancements 
            - New enhanced tax module that can support single vs combined tax structures.  
            - Taxes can be defined based on locations.
            - Seller cannot edit tax but can view tax profiles/rates in the tax category section on the dashboard and add new product form. 

        => UI/UX Related Enhancements 
            - Manageability to use a mega menu or a hamburger menu on the navigation section in the homepage.  
            - Manageability between left & top display for search/listing filters
            - Sellers are by default redirected to the inventory management page when clicking on the products menu in the dashboard. 
            - Admin catalog & seller products have been split between two separate tabs

        => Advanced Search module 
            - Manageable setting to choose between regular or advance search module
            - Advance search module will display the following 
                => Related products with the associated categories 	
                => Related brands according to the search string 
                => Related categories according to the search string   
                => Search history 
            - System will redirect to the brand page if the search string matches a brand. 

        => Homepage collection management module 
            - Enhanced homepage collection management with sorting. Collection layouts are  
                => Product collection – 3 layouts
                => Category collection – 2 layouts
                => Shop collection – 1 layout
                => Brand collection– 1 layout 
                => Blog collection – 1 layout 
                => Banner collection – 3 layouts
                => Sponsored shops – 1 layout
                => Sponsored products – 1 layout  
                => Sponsored shops – 1 layout 
                => FAQ collection – 1 layout 
                => Testimonial collection – 1 layout  
            - Added sorting of items within a collection

        => Seller request module
            - New module that lists status for 
                => Requested brand 
                => Requested category 
                => Requested products 

        => SEO Enhancements 
            - New module for Image alt tags 
            - Schema code management  
            - Upgraded URL rewriting with canonical & 301 redirects 
            - Added Google Webmaster 
            - Added Bing Webmaster
            - URL rewriting based on language 

        => General Enhancements 
            - Added PayPal payment gateway to support pay by PayPal account or card 
            - Updated Stripe payment gateway for 2 factor authentications to support EU countries 
            - Seller/Admin can add Category from the product form 
            - Seller/Admin can add brand from the product form 
            - Append language code to slug setting  
            - Fixed product without option and adding option with inventory 
            - Hot Jar integration
            - Setting to manage product price is inclusive or exclusive of tax 
            - Import export sheet updates 
            - Enhanced the discount module to be linked with brands and sellers   
            - Removed Authorize.net payment gateway as it is deprecated.  

Fixes :

        => UI Fixes
            - Fixed google feeds categories scroll bar.
            - Fixed FAQ default category active class issue on the front end.
            - Fixed white button color on some pages
            - Fixed graph in RTL mode for seller dashboard.

        => Functional Updates & Code Fixes
            - Fixed Custom URL for shop collections
            - Fixed stats on the buyer dashboard for pending and total orders
            - Improved case sensitiveness for product type identifier as it was not accepting the same while importing catalog.
            - Fixed Order is marked delivered automatically by the system based on cancellation and return age of product/shop.
            - Fixed product temp images import.
            - Fixed Export content encoding issue with Excel.
            - Fixed JSON error on COD order when admin tries to complete that order.
            - Fixed transaction ID listing after adding money to wallet using stripe 
            Improved display for product details such as seller name and variant on special price and other such pages
            - Fixed display for "Buy together" products under promotions, items do not appear on the list after creating them if no brand is assigned to the product.
            - Removed add money to wallet using the cod option
            - Added OG tags for the Homepage
            - Fixed if admin edits any subscription package then updates are not applicable for ongoing purchased packages.
    
Known Issues and Problems :

Following is a list of known errors that don’t have a workaround. These issues will be fixed in the subsequent release. 
        => Change in minimum selling price when reconfigured by Admin
        => Safari and IE 11 do not support our CSS. More info can be found at https://developer.microsoft.com/en-us/microsoft-edge/platform/status/csslevel3attrfunction/
        => System does not support Zero decimal currency while checking out with stripe

Installation steps:
 	• Download the files and configured with your development/production environment.
 	• You can get all the files mentioned in .gitignore file from git-ignored-files directory.
 	• Renamed -.htaccess file to .htaccess from {document root} and {document root}/public directory
	• Upload Fatbit library and licence files under {document root}/library.
	• Define DB configuration under {document root}/public/settings.php
	• Update basic configuration as per your system requirements under {document root}/conf directory.

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
       To Update state code which for only state which is present in old database state table, execute update_state_codes.sql  (Mostly done when upgrading form V9.2 to 9.3 )       

    Please replace tbl_countries, tbl_states from db_withdata.sql.

    Please hit <site-url>/admin/patch-update/update-category-relations to update all parent to child level relations in case of updating db.

    s3 bucket notes for bulk media:
        => Create a Lambda function.
        => Add trigers and upload zip file from  git-ignored-files/user-uploads/lib-files/fatbit-s3-zip-extractor.zip
        => Set permission and update Resource based on function created by you.
        {
        "Version": "2012-10-17",
        "Statement": [
            {
                "Effect": "Allow",
                "Action": "logs:CreateLogGroup",
                "Resource": "arn:aws:logs:us-east-2:765751105868:*"
            },
            {
                "Effect": "Allow",
                "Action": [
                    "logs:CreateLogStream",
                    "logs:PutLogEvents"
                ],
                "Resource": "arn:aws:logs:*:*:*"
            },
            {
                "Effect": "Allow",
                "Action": [
                    "s3:PutObject",
                    "s3:GetObject",
                    "s3:DeleteObject"
                ],
                "Resource": [
                    "*"
                ]
            }
        ]
        }

