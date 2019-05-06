<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$arr_flds = array(
        'select_all'=>Labels::getLabel('LBL_Select_all', $siteLangId),
        'listserial'=>Labels::getLabel('LBL_Sr._no.', $siteLangId),
        'option_identifier'=>Labels::getLabel('LBL_Option_Name', $siteLangId),
        'action' => Labels::getLabel('LBL_Action', $siteLangId),
    );
$tbl = new HtmlElement(
    'table',
    array('width'=>'100%', 'class'=>'table table--orders','id'=>'options')
);

$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $key => $val) {
    if ('select_all' == $key) {
        $th->appendElement('th')->appendElement('plaintext', array(), '<label class="checkbox"><input type="checkbox" onclick="selectAll( $(this) )" class="selectAll-js"><i class="input-helper"></i>'.$val.'</label>', true);
    } else {
        $th->appendElement('th', array(), $val);
    }
}

$sr_no = $page==1?0:$pageSize*($page-1);
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr');
    $tr->setAttribute("id", $row['option_id']);

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'select_all':
                $td->appendElement('plaintext', array(), '<label class="checkbox"><input class="selectItem--js" type="checkbox" name="option_id[]" value='.$row['option_id'].'><i class="input-helper"></i></label>', true);
                break;
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
                break;
            case 'option_identifier':
                if ($row['option_name']!='') {
                    $td->appendElement('plaintext', array(), $row['option_name'], true);
                    $td->appendElement('br', array());
                    $td->appendElement('plaintext', array(), '('.$row[$key].')', true);
                } else {
                    $td->appendElement('plaintext', array(), $row[$key], true);
                }
                break;
            case 'action':
                $ul = $td->appendElement("ul", array("class"=>"actions"));

                    /* if(!in_array($row['option_type'],$ignoreOptionValues)){
                        $li = $ul->appendElement("li");
                        $li->appendElement('a',array(
                        'href'=>CommonHelper::generateUrl('OptionValues',
                        'index',array($row['option_id'])),
                        'class'=>'button small green',
                        'title'=>'Option Values'
                        ),
                        '<i class="ion-navicon-round icon"></i>', true);
                    } */

                    $li = $ul->appendElement("li");
                    $li->appendElement(
                        'a',
                        array(
                        'href'=>'javascript:void(0)',
                        'class'=>'button small green', 'title'=>Labels::getLabel('LBL_Edit', $siteLangId),
                        "onclick"=>"optionForm(".$row['option_id'].")"),
                        '<i class="fa fa-edit"></i>',
                        true
                    );

                    $li = $ul->appendElement("li");
                    $li->appendElement(
                        'a',
                        array(
                        'href'=>"javascript:void(0)", 'class'=>'button small green',
                        'title'=>Labels::getLabel('LBL_Delete', $siteLangId),"onclick"=>"deleteOptionRecord(".$row['option_id'].")"),
                        '<i class="fa fa-trash"></i>',
                        true
                    );

                break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}
if (count($arr_listing) == 0) {
    $tbl->appendElement('tr')->appendElement(
        'td',
        array(
        'colspan'=>count($arr_flds)),
        'No records found'
    );
}
?>
<form id="frmOptionListing" name="frmOptionListing" method="post" onsubmit="formAction(this); return(false);" class="form" action="<?php echo CommonHelper::generateUrl('Seller', 'bulkOptionsDelete'); ?>">
    <?php echo $tbl->getHtml(); ?>
</form>
<?php

$postedData['page']=$page;
echo FatUtility::createHiddenFormFromData($postedData, array('name' => 'frmOptionsSearchPaging'));

$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
