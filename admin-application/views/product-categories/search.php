<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
if (count($arr_listing) > 0) {
?>
<ul id="sorting-categories" class="sorting-categories">
    <?php foreach ($arr_listing as $sn => $row) {  ?>
    <li id="<?php echo $row['prodcat_id'];?>" class="sortableListsClosed <?php if($row['subcategory_count'] == 0 ) { ?>no-children<?php } ?>">
        <div>
            <div class="sorting-bar">
                <div class="sorting-title"><span><?php echo $row['prodcat_identifier']; ?></span> <a href="<?php echo commonHelper::generateUrl('Products', 'index', array($row['prodcat_id'])); ?>" class="badge badge-secondary badge-pill clickable" title="<?php echo  Labels::getLabel('LBL_Category_Products', $adminLangId); ?>"><?php echo $row['category_products']; ?></a></div>
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
                    <a href="javascript::void(0)" title="<?php echo  Labels::getLabel('LBL_Add_Product', $adminLangId); ?>" class="btn btn-clean btn-sm btn-icon"><i class="fas fa-plus clickable"></i></a>
                    <a href="<?php echo $url; ?>" title="<?php echo  Labels::getLabel('LBL_Edit', $adminLangId); ?>" class="btn btn-clean btn-sm btn-icon"><i class="far fa-edit clickable"></i></a>
                    <a href="javascript::void(0)" title="<?php echo  Labels::getLabel('LBL_Delete', $adminLangId); ?>" onclick = "deleteRecord(<?php echo $row['prodcat_id']; ?>)" class="btn btn-clean btn-sm btn-icon"><i class="fa fa-trash clickable"></i></a>
                    <?php } ?>
                </div>
            </div>
            <?php if($row['subcategory_count'] > 0 ) { ?>
            <span class="sortableListsOpener" style="float: left; display: inline-block; background-position: center center; background-repeat: no-repeat; margin-right: 0px; position: absolute; left: 10px; top: 15px; font-size: 12px; cursor:pointer;"><i class="fa fa-plus clickable" onClick="displaySubCategories(this)"></i></span>
            <?php } ?>
        </div>
    </li>
    <?php } ?>
</ul>
<?php }else{ ?>
<ul class="list-inline">
    <li><?php echo  Labels::getLabel('LBL_No_Records_Found', $adminLangId); ?></li>
</ul>
<?php } ?>

<script type="text/javascript">
 $(function() {
     var optionsPlus = {
         insertZonePlus: true,
         placeholderCss: {
             'background-color': '#e5f5ff',
         },
         hintCss: {
             'background-color': '#6dc5ff'
         },
         baseCss: {
            'list-style-type': 'none',
         },
         onChange: function(cEl)
         {
            var catId =  $( cEl ).attr('id');
            var parentCatId = $( cEl ).parent('ul').parent('li').attr('id');   
            var catOrder = [] ;               
            $($( cEl ).parent().children()).each(function(i){               
                catOrder[i+1] = $(this).attr('id');
            })       
            var data = "catId="+catId+"&parentCatId="+parentCatId+"&catOrder="+JSON.stringify(catOrder);
            fcom.updateWithAjax(fcom.makeUrl('productCategories','updateOrder'), data, function(res){ });
         },
         opener: {
             active: true,
             as: 'html', // if as is not set plugin uses background image
             close: '<i class="fa fa-minus clickable" onClick="hideItems(this)"></i>',
             open: '<i class="fa fa-plus c3 clickable" onClick="displaySubCategories(this)"></i>',
             openerCss: {
                 'display': 'inline-block',
                 'margin-right': '0',
                 'position': 'absolute',
                 'left': '10px',
                 'top': '15px',
                 'font-size': '12px',
                 'cursor':'pointer'
             }
         },
         ignoreClass: 'clickable'
     };

     $('#sorting-categories').sortableLists(optionsPlus);

 });
</script>

