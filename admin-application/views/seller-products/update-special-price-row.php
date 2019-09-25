<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
    $editListingFrm = new Form('editListingFrm-'.$splPriceId, array('id'=>'editListingFrm-'.$splPriceId));
?>
<tr id='row-<?php echo $splPriceId; ?>'>
    <td>
        <label class="checkbox">
            <input class="selectItem--js" type="checkbox" name="selprod_ids[<?php echo $splPriceId; ?>]" value="<?php echo $data['splprice_selprod_id']; ?>"><i class="input-helper"></i></label>
    </td>
    <td>
        <?php echo html_entity_decode($data['product_name']); ?>
    </td>
    <td>
        <?php echo $data['credential_username']; ?>
    </td>
    <td>
        <?php $startDate = date('Y-m-d', strtotime($data['splprice_start_date'])); ?>
        <div class="js--editCol edit-hover"><?php echo $startDate; ?></div>
        <?php
        $lbl = Labels::getLabel('LBL_Start_Date', $adminLangId);
        $attr = array(
            'readonly' => 'readonly',
            'placeholder' => $lbl,
            'data-selprodid' => $data['splprice_selprod_id'],
            'data-id' => $splPriceId,
            'data-oldval' => $startDate,
            'id' => 'splprice_start_date-'.$splPriceId,
            'class' => 'date_js js--splPriceCol hide sp-input',
        );
        $editListingFrm->addDateField($lbl, 'splprice_start_date', $startDate, $attr);
        echo $editListingFrm->getFieldHtml('splprice_start_date');
        ?>
    </td>
    <td>
        <?php $endDate = date('Y-m-d', strtotime($data['splprice_end_date'])); ?>
        <div class="js--editCol edit-hover"><?php echo $endDate; ?></div>
        <?php
        $lbl = Labels::getLabel('LBL_End_Date', $adminLangId);
        $attr = array(
            'readonly' => 'readonly',
            'placeholder' => $lbl,
            'data-selprodid' => $data['splprice_selprod_id'],
            'data-id' => $splPriceId,
            'data-oldval' => $endDate,
            'id' => 'splprice_end_date-'.$splPriceId,
            'class' => 'date_js js--splPriceCol hide sp-input',
        );
        $editListingFrm->addDateField($lbl, 'splprice_end_date', $endDate, $attr);
        echo $editListingFrm->getFieldHtml('splprice_end_date');
        ?>
    </td>
    <td>
        <div class="js--editCol edit-hover"><?php echo CommonHelper::displayMoneyFormat($data['splprice_price']); ?></div>
        <input type="text" data-id="<?php echo $splPriceId; ?>" value="<?php echo $data['splprice_price']; ?>" data-selprodid="<?php echo $data['splprice_selprod_id']; ?>" data-oldval="<?php echo $data['splprice_price']; ?>" name="splprice_price" class="js--splPriceCol hide sp-input"/>
    </td>
    <td>
        <ul class="actions actions--centered">
            <li class="droplink">
                <a href="javascript:void(0)" class="button small green" title="<?php echo Labels::getLabel('LBL_Edit', $adminLangId); ?>">
                    <i class="ion-android-more-horizontal icon"></i>
                </a>
                <div class="dropwrap">
                    <ul class="linksvertical">
                        <li>
                            <a href="javascript:void(0)" title="<?php echo Labels::getLabel('LBL_Delete', $adminLangId); ?>" onclick="deleteSellerProductSpecialPrice(<?php echo $splPriceId; ?>)"><?php echo Labels::getLabel('LBL_Delete', $adminLangId); ?></a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </td>
</tr>
