<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
        'dragdrop' => '',
        'select_all' => Labels::getLabel('LBL_Select_all', $adminLangId),
        'listserial' => Labels::getLabel('LBL_Sr._No', $adminLangId),
        'plugin_identifier' => Labels::getLabel('LBL_PLUGIN', $adminLangId),
        'plugin_type' => Labels::getLabel('LBL_Type', $adminLangId),
        'plugin_active' => Labels::getLabel('LBL_Status', $adminLangId),
        'action' => Labels::getLabel('LBL_Action', $adminLangId),
    );
if (!$canEdit || true === $hideDragHandle) {
    unset($arr_flds['dragdrop']);
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
                if ($row['plugin_name'] != '') {
                    $td->appendElement('plaintext', array(), $row['plugin_name'], true);
                    $td->appendElement('br', array());
                    $td->appendElement('plaintext', array(), '(' . $row[$key] . ')', true);
                } else {
                    $td->appendElement('plaintext', array(), $row[$key], true);
                }
                break;
            case 'plugin_type':
                $pluginType = isset($pluginTypes[$row[$key]]) ? $pluginTypes[$row[$key]] : Labels::getLabel('LBL_N/A', $adminLangId);
                $td->appendElement('plaintext', array(), $pluginType, true);
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
                    $li = $ul->appendElement("li", array('class' => 'droplink'));
                    $li->appendElement('a', array('href' => 'javascript:void(0)', 'class' => 'button small green','title' => Labels::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                    $innerDiv = $li->appendElement('div', array('class' => 'dropwrap'));
                    $innerUl = $innerDiv->appendElement('ul', array('class' => 'linksvertical'));

                    $innerLi = $innerUl->appendElement('li');
                    $innerLi->appendElement('a', array('href' => 'javascript:void(0)','class' => 'button small green','title' => Labels::getLabel('LBL_Edit', $adminLangId),"onclick" => "editPluginForm(" . $row['plugin_id'] . ")"), Labels::getLabel('LBL_Edit', $adminLangId), true);
                    
                    $innerLi = $innerUl->appendElement('li');
                    $innerLi->appendElement('a', array('href' => 'javascript:void(0)','class' => 'button small green','title' => Labels::getLabel('LBL_Settings', $adminLangId),"onclick" => "editSettingForm('" . $row['plugin_code'] . "')"), Labels::getLabel('LBL_Settings', $adminLangId), true);
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
$frm->setFormTagAttribute('class', 'web_form last_td_nowrap');
$frm->setFormTagAttribute('onsubmit', 'formAction(this, reloadList ); return(false);');
$frm->setFormTagAttribute('action', CommonHelper::generateUrl('plugins', 'toggleBulkStatuses'));
$frm->addHiddenField('', 'status');

echo $frm->getFormTag();
echo $frm->getFieldHtml('status');
echo $tbl->getHtml(); ?>
</form>
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