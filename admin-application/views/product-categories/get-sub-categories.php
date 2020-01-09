<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
if (count($childCategories) > 0) {
?>
<ul>
    <?php foreach ($childCategories as $sn => $row) { ?>
    <li id="<?php echo $row['prodcat_id'];?>" class="sortableListsClosed" parent-id="<?php echo $row['prodcat_parent'];?>">
        <div>
            <div class="sorting-bar">
                <div class="sorting-title"><span class="clickable" onClick="displaySubCategories(this);"><?php echo $row['prodcat_identifier']; ?></span> <a href="<?php echo commonHelper::generateUrl('Products', 'index', array($row['prodcat_id'])); ?>" class="badge badge-secondary badge-pill clickable"><?php echo $row['category_products']; ?></a></div>
                <div class="sorting-actions">
                    <?php
                    $active = "";
                    if ($row['prodcat_active']) {
                        $active = 'checked';
                    }
                    $statusAct = ($canEdit === true) ? 'toggleStatus(event,this,' .applicationConstants::YES. ')' : 'toggleStatus(event,this,' .applicationConstants::NO. ')';
                    $statusClass = ($canEdit === false) ? 'disabled' : '';
                    ?>
                    <label class="statustab statustab-sm">
                        <input <?php echo $active; ?> type="checkbox" id="switch<?php echo $row['prodcat_id'];?>" value="<?php echo $row['prodcat_id'];?>" onclick="<?php echo $statusAct;?>" class="switch-labels"/>
                        <i class="switch-handles <?php echo $statusClass;?> clickable"></i>
                    </label>
                    <?php 
                    if ($canEdit) { 
                        $url = commonHelper::generateUrl('ProductCategories', 'form', array($row['prodcat_id']));
                        if($row['prodcat_parent'] > 0){
                            $url = commonHelper::generateUrl('ProductCategories', 'form', array($row['prodcat_id'], $row['prodcat_parent']));
                        }
                    ?>
                    <a href="javascript::void(0)" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash clickable"></i></a>
                    <a href="<?php echo $url; ?>" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash clickable"></i></a>
                    <a href="javascript::void(0)" onclick = "deleteRecord(<?php echo $row['prodcat_id']; ?>)" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash clickable"></i></a>
                    <?php } ?>
                </div>
            </div>   
            <?php if($row['subcategory_count'] > 0 ) { ?>
            <span class="sortableListsOpener" style="float: left; display: inline-block; background-position: center center; background-repeat: no-repeat; margin-right: 0px; position: absolute; left: 10px; top: 15px; font-size: 12px;"><i class="fa fa-plus clickable" onClick="displaySubCategories(this)"></i></span>
            <?php } ?>
        </div>
    </li>
    <?php } ?>
</ul>
<?php } ?>