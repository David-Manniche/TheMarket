<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="tabs_nav_container">
<?php 
$variables = array('adminLangId'=>$adminLangId,'action'=>$action);
$this->includeTemplate('import-export/_partial/top-navigation.php',$variables,false); ?>
    <div class="tabs_panel_wrap">
        <?php
            if( !empty($pageData['epage_content']) ){
                ?>
                <h3 class="mb-4"><?php echo $pageData['epage_label'];?></h3>
                <?php
                echo FatUtility::decodeHtmlEntities( $pageData['epage_content'] );
            }else{
                echo Labels::getLabel('LBL_Sorry!_No_Instructions', $adminLangId);
            }
        ?>
    </div>
</div>