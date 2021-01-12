<div class="demo-header no-print">
    <div class="restore-wrapper">
        <a href="javascript:void(0)" onclick="showRestorePopup()">

            <p class="restore__content">Database Restores in</p>
            <div class="restore__progress">
                <div class="restore__progress-bar" role="progressbar" style="width:25%" aria-valuenow="25"
                    aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <span class="restore__counter" id="restoreCounter">00:00:00</span>
        </a>
    </div>
    <ul class="switch-interface">
        <?php
        $admin = '';
        $mobileSite = '';
        $tabSite = '';
        $desktopSite = '';
        $adminUrl = 'admin';
        if ('SiteDemoController' == FatApp::getController()) {
            switch (FatApp::getAction()) {
                case 'mobile':
                    $mobileSite = 'is-active';
                    break;
                case 'tab':
                    $tabSite = 'is-active';
                    break;
            }
        } elseif (strpos($_SERVER ['REQUEST_URI'], rtrim(CONF_WEBROOT_BACKEND, '/')) !== false) {
            $admin = 'is-active';
            $adminUrl = '';
        } else {
            $desktopSite = 'is-active';
        }
        ?>

        <li class="<?php echo $admin; ?>">
            <a title="Admin"
                href="<?php echo UrlHelper::generateUrl($adminUrl); ?>">
                <i class="icn icn--admin">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/retina/sprite.svg#admin"
                            href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/retina/sprite.svg#admin">
                        </use>
                    </svg>
                </i>
            </a>
        </li>
        <li class="<?php echo $desktopSite; ?>">
            <a title="Marketplace" 
                href="<?php echo UrlHelper::generateUrl('', '', array(), CONF_WEBROOT_FRONTEND); ?>">
                <i class="icn icn--desktop">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/retina/sprite.svg#desktop"
                            href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/retina/sprite.svg#desktop">
                        </use>
                    </svg>
                </i>
            </a>
        </li>
       <?php /*  <li class="<?php echo $tabSite; ?>">
            <a title="Marketplace Tab View"
                href="<?php echo UrlHelper::generateUrl('SiteDemo', 'tab', array(), CONF_WEBROOT_FRONTEND); ?>">
                <i class="icn icn--tab">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/retina/sprite.svg#tab"
                            href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/retina/sprite.svg#tab">
                        </use>
                    </svg>
                </i>
            </a>
        </li> */ ?>
        <li class="<?php echo $mobileSite; ?>">
            <a title="Marketplace Mobile View"
                href="<?php echo UrlHelper::generateUrl('SiteDemo', 'mobile', array(), CONF_WEBROOT_FRONTEND); ?>">
                <i class="icn icn--mobile">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/retina/sprite.svg#mobile"
                            href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/retina/sprite.svg#mobile">
                        </use>
                    </svg>
                </i>
            </a>
        </li>
    </ul>
    <style>
     .version-num { line-height: 1; font-size: 0.85rem; margin:0 20px;} 
    .version-num span{ display:block; font-weight: 500; line-height: 1.5; }  
    .version-num a{  line-height: 1;text-decoration: underline; font-size: 16px;font-weight: 700;}    
    </style>
    
    <div class="demo-cta">
    <div class="version-num"><span><?php echo str_replace('RV-', 'V', CONF_WEB_APP_VERSION);?> is out </span> <a target="_blank" rel="noopener" href="https://www.yo-kart.com/recent-updates.html">Version Updates</a></div>
        <a target="_blank" href="https://www.yo-kart.com/multivendor-ecommerce-marketplace-platform.html"
            class=" btn btn-brand btn-sm ripplelink" rel="noopener">Start Your Marketplace</a> &nbsp;
        <a href="https://www.yo-kart.com/request-demo.html" class="request-demo btn btn-outline-brand btn-sm  ripplelink" rel="noopener">
            Request a Demo
        </a>
        <a href="javascript:void(0)" class="close-layer" id="demoBoxClose"></a>
    </div>
</div>