<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div id="body" class="body">
	<div class="top-space">
	<div class="container container--fixed">
       <!-- <div class="row"> -->
           <div class="container container--fluid">
               <div class="panel panel--centered">
                   <div class="box box--white">
                       <div class="message message--success align--center">
                           <i class="fa fa-check-circle"></i>
                           <h2><?php echo Labels::getLabel('LBL_Congratulations',$siteLangId);?></h2>
						   <?php if(!CommonHelper::isAppUser()){ ?>
                           <h6><?php echo CommonHelper::renderHtml($textMessage);?></h6>
						   <?php }?>
                           <span class="gap"></span>
                       </div>
                   </div>
               </div>
            </div>
        <!-- </div> -->
    </div></div>
	<div class="gap"></div>
</div>
