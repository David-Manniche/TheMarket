<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if(!empty($optionCombinations)){  ?>
 <div class="row">
     <div class="col-6"><h6 class="my-3"><?php echo Labels::getLabel('LBL_Variants',$siteLangId);?></h6></div>
     <div class="col-6"></div>
 </div>
  
    <div>
         <div class="variants scroll-y" data-simplebar>
          <ul class="list-group">
           <?php     
                foreach($optionCombinations as $optionValueId=>$optionValue){
                    $arr = explode('|',$optionValue);
                    $key = str_replace('|',',',$optionValueId); 
                    $variant = '';
                    foreach($arr as $key2=>$val){	
                        if($key2 == 0){
                            $variant = $val;
                        }else{
                            $variant = $variant." / ".$val;
                        }						
                    } 
                ?>
                <li class="list-group-item"><?php echo $variant; ?></li>
                <?php } ?>	
           </ul>
       </div>
    </div>
<?php } ?>
					