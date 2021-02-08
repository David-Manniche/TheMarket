Multivendor - Released Version : RV-9.3.0
    => IOS/Android Buyer APP version : 2.0
    => System API version : 2.3

Fixes:
    => 047746 - Get current location not working
    => 046939 - Add product tag giving error
    => 047306 - On shop collection while linking product remove icon not coming
    => 047899 - Default lang url issue when we enable language specific url
    => 048292 - Handled case insenstive for Admin url.
    => 047929 - When uploading file for 3rd party APIs Google webmaster and Bing, file name is not saved
    => 047992 - on product search page product coming with wrong option while search
    => 048123 - On Buyer Review Status Updated Notification wrong email template passed
    => 048408 - Admin - While creating a new category catcode not updated
    => 048194 - Unable to add alt tag for image with different options
    => 048604 - Fatal error in View Order API.
    => 048534 - Design issue of blank li tag adding in header if single language and single currency 
    => 048605 - getting error while creating shipping profile IN ADMIN
    => 048634 - Users are unable to see products in the favourite list if brand is not selected for the product
    => 048649 - whenever seller/admin edits the catalog/inventory then on updating it updates the url and changes it to id
    => 048457 - on seller credit page unable to edit label BL_Add_Wallet_Credits_[ Br]
    => 049052 - Category icon not displayed in Android and iOS mobile applications
    => 049066 - when admin product is available for pickup only then on buyer end it's listing- price 0 and getting problem on further step
    => 049147 - Digital product details page >> Available for shipping and pickup should be removed.
    => 049029 - when hamburger menu is selected for categories then on web- all categories are listing while on app only categories containing data are listing
    => 049317 - Mpesa Payment Gateway argument type Issue reported with live credentials
    => 049365 - When you order more than one item with only one allowing for COD, it completes the order as so with all the items, even the ones that don’t have COD option eligible 
    => 049364 - About Us image is not getting updated in cms due to cache.
    => 049390 - Shipping is listing twice on front end when cart products from multiple sellers and admin shipping only enabled.
    => 049439 - payment status is not getting updated through paypal
    => 049425 - Seller >> shop >> pickup address tab >> if we click on "pickup address " tab before adding shop details then blank page gets displayed

Enhancements :
   => Make provision to made seller
   => At shop level  pickup interval option given
   => Tracking order with Google Analytics ecommerce 
   => W3c validator.
   => Performance optimization
    
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

    2Checkout Payment Gateway:
        To Test Sandbox Payment Refer This: https://knowledgecenter.2checkout.com/Documentation/09Test_ordering_system/01Test_payment_methods


