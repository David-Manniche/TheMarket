<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = [
    'dragdrop' => '',
    'select_all' => Labels::getLabel('LBL_Select_all', $adminLangId),
    'listserial' => '#',
    'plugin_identifier' => Labels::getLabel('LBL_PLUGIN', $adminLangId),
    'plugin_active' => Labels::getLabel('LBL_Status', $adminLangId),
    'action' => '',
];
$allPlugins = $arr_listing;
$pluginType = (array_shift($allPlugins))['plugin_type'];
if (!$canEdit || 2 > count($arr_listing) || in_array($pluginType, Plugin::HAVING_KINGPIN)) {
    unset($arr_flds['dragdrop']);
    if (!$canEdit || in_array($pluginType, Plugin::HAVING_KINGPIN)) {
        unset($arr_flds['select_all']);
    }
}
$tbl = new HtmlElement('table', array('width' => '100%', 'class' => 'table table--hovered table-responsive','id' => 'plugin'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $key => $val) {
    if ('select_all' == $key) {
        $th->appendElement('th')->appendElement('plaintext', array(), '<label class="checkbox"><input title="' . $val . '" type="checkbox" onclick="selectAll( $(this) )" class="selectAll-js"><i class="input-helper"></i></label>', true);
    } else {
        $e = $th->appendElement('th', array(), $val);
    }
}

$sr_no = 0;
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr', array( 'id' => $row['plugin_id'], 'class' => '' ));
    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'dragdrop':
                if ($row['plugin_active'] == applicationConstants::ACTIVE) {
                    $td->appendElement('i', array('class' => 'ion-arrow-move icon'));
                    $td->setAttribute("class", 'dragHandle');
                }
                break;
            case 'select_all':
                $td->appendElement('plaintext', array(), '<label class="checkbox"><input class="selectItem--js" type="checkbox" name="plugin_ids[]" value=' . $row['plugin_id'] . '><i class="input-helper"></i></label>', true);
                break;
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
                break;
            case 'plugin_identifier':
                $defaultCurrConvAPI = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . $row['plugin_type'], FatUtility::VAR_INT, 0);
                $htm = '';
                if (!empty($defaultCurrConvAPI) && $row['plugin_id'] == $defaultCurrConvAPI) {
                    $htm = ' <span class="badge badge-info">'  . Labels::getLabel('LBL_DEFAULT', $adminLangId) . '</span>';
                }
                if ($row['plugin_name'] != '') {
                    $td->appendElement('plaintext', array(), $row['plugin_name'] . $htm, true);
                    $td->appendElement('br', array());
                    $td->appendElement('plaintext', array(), '(' . $row[$key] . ')', true);
                } else {
                    $td->appendElement('plaintext', array(), $row[$key] . $htm, true);
                }
                break;
            case 'plugin_active':
                $active = "active";
                if (!$row['plugin_active']) {
                    $active = '';
                }
                $statucAct = ($canEdit === true) ? 'toggleStatus(this)' : '';
                $str = '<label id="' . $row['plugin_id'] . '" class="statustab ' . $active . '" onclick="' . $statucAct . '">
                <span data-off="' . Labels::getLabel('LBL_Active', $adminLangId) . '" data-on="' . Labels::getLabel('LBL_Inactive', $adminLangId) . '" class="switch-labels"></span>
                <span class="switch-handles"></span>
                </label>';
                $td->appendElement('plaintext', array(), $str, true);
                break;
            case 'action':
                $ul = $td->appendElement("ul", array("class" => "actions actions--centered"));
                if ($canEdit) {
                    $td->appendElement('a', array('href' => 'javascript:void(0)','class' => 'btn btn-clean btn-sm btn-icon','title' => Labels::getLabel('LBL_Edit', $adminLangId),"onclick" => "editPluginForm(" . $type . ", " . $row['plugin_id'] . ")"), '<i class="far fa-edit icon"></i>', true);
                    $td->appendElement('a', array('href' => 'javascript:void(0)','class' => 'btn btn-clean btn-sm btn-icon','title' => Labels::getLabel('LBL_Settings', $adminLangId),"onclick" => "editSettingForm('" . $row['plugin_code'] . "')"), '<i class="fas fa-cog icon"></i>', true);
                }
                break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}
if (count($arr_listing) == 0) {
    $tbl->appendElement('tr')->appendElement('td', array('colspan' => count($arr_flds)), Labels::getLabel('LBL_No_Records_Found', $adminLangId));
}

$frm = new Form('frmPluginListing', array('id' => 'frmPluginListing'));
$frm->setFormTagAttribute('class', 'web_form last_td_nowrap actionButtons-js');
$frm->setFormTagAttribute('onsubmit', 'formAction(this, reloadList ); return(false);');
$frm->setFormTagAttribute('action', CommonHelper::generateUrl('plugins', 'toggleBulkStatuses'));
$frm->addHiddenField('', 'status');
$frm->addHiddenField('', 'plugin_type', $pluginType);?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo CommonHelper::replaceStringData(Labels::getLabel('LBL_{PLUGINNAME}_PLUGINS', $adminLangId), ['{PLUGINNAME}' =>  $pluginTypes[$type]]); ?> </h4>
        <?php
        if ($canEdit && !in_array($pluginType, Plugin::HAVING_KINGPIN)) {
            $data = [
                'adminLangId' => $adminLangId,
                'deleteButton' => false
            ];

            $this->includeTemplate('_partial/action-buttons.php', $data, false);
        }
        ?>
    </div>
    <div class="sectionbody">
        <div class="tablewrap">
            <?php
            echo $frm->getFormTag();
            echo $frm->getFieldHtml('status');
            echo $frm->getFieldHtml('plugin_type');
            echo $tbl->getHtml(); ?>
            </form>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#plugin').tableDnD({
            onDrop: function(table, row) {
                fcom.displayProcessing();
                var order = $.tableDnD.serialize('id');
                fcom.ajax(fcom.makeUrl('plugins', 'updateOrder'), order, function(res) {
                    var ans = $.parseJSON(res);
                    if (ans.status == 1) {
                        fcom.displaySuccessMessage(ans.msg);
                    } else {
                        fcom.displayErrorMessage(ans.msg);
                    }
                });
            },
            dragHandle: ".dragHandle",
        });
    });
</script>