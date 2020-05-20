Multivendor - Released Version : RV-9.2.0

New Features
    => New "Plugins" option under system settings in Admin console.
        - Currency Converter API
            => Real time currency conversion values are fetched from live API's.
        - Social Logins
            => Instagram Login
            => Apple Sign-In
        - PayPal Payout
            => Sellers can request payouts via PayPal accounts. Admin can approve the requests to pay via PayPal.
        - Google Feeds (Merchant Center) API
            => https://www.google.com/retail/solutions/merchant-center/
        - Twilio SMS Gateway Integration

    => MSN Azure Language Translation API
        - https://azure.microsoft.com/en-in/services/cognitive-services/translator-text-api/

    => Added 'Facebook Pixel' under 3rd party API settings
        Following events are configured in the system
        - addToCart
        - addToWishList
        - contactUs
        - customizeProduct
        - initiateCheckout
        - purchase
        - search
        - viewContent
        - newsLetterSubscription
    
    => Progressive Web App (PWA)
        - https://developers.google.com/web/ilt/pwa
    
    => Custom Push Notifications
        - Admin can create custom notification along with media and push to mobile devices

    => Abandoned Cart
        - Admin can view 'Abandoned Cart' option under 'Orders' in the admin console
        - Admin can view items in-cart that have been abandoned by users
        - Admin can view items that have been deleted from the cart by users
        - Admin can generate & share discount coupon notifications
        - Admin can view reports for the above information

    => Login/Register via Phone number
        - User can sign up/login using their phone number provided the SMS plugin is active

    => Seller Sub users
        - Sellers can create sub-users and manage permissions

    => SMS Templates Management
        - SMS templates can be managed from the Admin console

    => Elastic Search (Beta Release)
       - Document style full text search
Enhancements

Performance Updates
    => System supports 500,000 products with 250 concurrent users with a load speed of under 5 secs.
    => Added 'View More' option to Brands filter in search/category listing pages. Data is loaded partially to improve page load speed times.
    => Search filters are loaded via ajax call for better performance.

User Experience Enhancements
    => Redesigned and enhanced workflow of the Categories Module. Introduced the drag-drop

Functionality to re-order categories.
    => Redesigned and enhanced workflow of the Product Intake Form to work like a wizard
    => Redesigned and enhanced workflow of the Inventory Form so that sellers can add inventories for multiple options in one go.
    => Upgraded the Product Tags functionality. Product tags are not language specific anymore
    => Redesigned and enhanced workflow of the Buy Together and Related products modules
    => Redesigned and enhanced workflow of the SEO module on Seller Dashboard
    => Added image cropper in the system.

Orders Enhancements
    => Payment pending orders that could not be cancelled/deleted from the system previously can now be achieved. Upon achieving the order gets added to the achieved list in the admin
console. Sellers and buyers would not be able to see the order anymore.
    => Added the 'Cancellation' & 'Return' fields at the inventory level. System will auto-complete the orders once this time period is over.

Tax Related Enhancements
    => Introduced setting in the system to choose if discount is to be applied before or after-tax calculations
    => Added Consolidated/Single tax settings in the system.
    => GST compliant tax structure

UI Related Enhancements
    =>  Introduced SCSS for font-end UI enhancements
    =>  Optimized images so that load time decreases  
    =>  Advanced color management for Background & Surface color  
    =>  General improvements to workflows and user experience
    =>  Removed redundant JS code 

General Enhancements
    => Introduced setting in the Admin console for converting the Brand field as optional
    => Added Blog collection in the collections management for Web and Mobile Apps
    => Restructured and organized the Admin and Seller navigation menu
    => Clubbed the Import-Export functionality in the Admin console under one menu item
    => Single master settings to manage header & footer data for email templates.
    => Added 'Send Test Email' button to email templates so that admin can view changes
    => Existing social logins (Google & Facebook) moved under plugins
    => Upgraded Google Re-Captcha to V3
    => Allowed the use of '/' operator in URL rewriting
    => Management in system settings for Admin to choose between Wishlist/Favorite functionality
    => Library and code updated to handle 3-D secure payments through Omise Payment Gateway
    => Added the 'All' option under category filters in the search bar
    => Allowed keyword search with '&' & '%' keyword
    => Allowed special characters in import-export sheets

Fixes

UI Fixes
    => Displayed order id as invoice number for subscription orders.
    => Added space in bottom for dashboard pages.
    => Fixed User default image that was previously blurred.
    => Fixed Safari Browser layout issue.
    => Added shop name when viewing the shop report listing.
    => Fixed Innova editor full width issue
    => Hide the save search button if no record found after applying filters on shop page product search.
    => Changed the no record found design for product search listing on shop or global search.
    => Updated action buttons to icons in admin console
    => Fixed Buyer dashboard hamburger icon is clicked to expand sidebar then widgets are overlapping screen and scroll is not working.
    => Fixed display for Add review button as it should not be displayed on product detail page if that item is not bought by logged in user.

Functional Updates & Code Fixes
    => Reordered email notifications for orders and cancellations
    => Added shipping information in vendor order email.
    => Fixed the Email Received while change email request sent through Seller APP
    => Fixed social account login if email was not updated in db
    => Fixed error in email when user places a bank withdrawal request.
    => Fixed error on the withdrawing amount from affiliate account using bank pay option
    => Fixed multiple requests that are placed from my credits section. Added a limit on the wallet amount.
    => Allowed uppercase file extensions in media upload.
    => Updated new labels for IOS APP.
    => Fixed same product to be added as add-on product on the product detail page
    => Fixed deleted categories that were displaying in the home page category collections
    => Added missing labels for front end via Admin
    => Fixed multiple reporting of the same shop by the same user.
    => Fixed display for RTL supported languages.
    => Removed return option on digital products.
    => Fixed entry for zero when adding inventory.
    => Fixed shop collection page filters issue.
    => Fixed email for seller on COD orders.
    => While adding vol discount minimum quantity should be greater than minimum quantity to buy.
    => Restricted keyword searches up to 80 character.
    => Fixed price range slider to validate min/max price.

Known Issues and Problems

Following is a list of known errors that don't have a workaround. These issues will be fixed in the
subsequent release.
    => Change in minimum selling price when reconfigured by Admin
    => Safari and IE 11 do not support our CSS. More info can be found at
https://developer.microsoft.com/en-us/microsoft-edge/platform/status/csslevel3attrfunction/

Installation steps:
 	• Download the files and configured with your development/production environment.
 	• You can get all the files mentioned in .gitignore file from git-ignored-files directory.
 	• Renamed -.htaccess file to .htaccess from {document root} and {document root}/public directory
	• Upload Fatbit library and licence files under {document root}/library.
	• Define DB configuration under {document root}/public/settings.php
	• Update basic configuration as per your system requirements under {document root}/conf directory.