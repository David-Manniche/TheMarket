<div style="margin:0; padding:0;background: #ecf0f1;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ecf0f1" style="font-family:Arial; color:#333; line-height:26px;">
        <tbody>
            <tr>
                <td>
                    <!--
                    header start here
                    -->

                    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="background:#<?php echo FatApp::getConfig('CONF_EMAIL_TEMPLATE_COLOR_CODE'.$langId, FatUtility::VAR_STRING, 'FF3A59'); ?>;padding:15px;"><div style="max-width:<?php echo (FatApp::getConfig('CONF_EMAIL_TEMPLATE_LOGO_RATIO', FatUtility::VAR_INT, 1) == EmailTemplates::LOGO_RATIO_SQUARE) ? '60px' : '150px'?>;"><a href="{website_url}">{Company_Logo}</a></div></td>
                                <td style="text-align:right;">{social_media_icons}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--
                    header end here
                    -->
                       </td>
            </tr>
            <tr><td>