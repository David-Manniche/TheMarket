<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if(!empty($optionCombinations)){  ?>
    <table width="100%" class="table table-bordered">                             
        <thead>
            <tr>
                <th width="70%"><?php echo Labels::getLabel('LBL_Variants',$adminLangId);?></th>
                <th><?php echo Labels::getLabel('LBL_EAN/UPC_code',$adminLangId);?></th>									
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
                <td><?php echo $variant; ?></td>
                <td><input type="text" id="code<?php echo $optionValueId; ?>" name="code<?php echo $optionValueId?>" value="<?php echo (isset($upcCodeData[$key]['upc_code']))?$upcCodeData[$key]['upc_code']:'';?>" onBlur="updateUpc('<?php echo $optionValueId;?>')"></td>								
            </tr>
            <?php } ?>	
        </tbody>
    </table>
<?php } ?>
					