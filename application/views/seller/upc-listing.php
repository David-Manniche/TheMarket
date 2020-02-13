<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if(!empty($optionCombinations)){  ?>
   <div class="variants-wrap">
       <table width="100%" class="table-fixed-header">                             
        <thead>
            <tr>
                <th width="70%"><?php echo Labels::getLabel('LBL_Variants',$siteLangId);?></th>	
            </tr>
        </thead>
        <tbody>
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
            <tr>
                <td width="70%"><?php echo $variant; ?></td>	
            </tr>
            <?php } ?>	
        </tbody>
    </table></div>
<?php } ?>
