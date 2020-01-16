<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?> <?php
$arr_flds = array(
    'listserial'=> Labels::getLabel('LBL_S.No.', $adminLangId),
    'user'=>Labels::getLabel('LBL_User', $adminLangId),
    'afile_physical_path'=>Labels::getLabel('LBL_Location', $adminLangId),
    'files'    => Labels::getLabel('LBL_Files_Inside', $adminLangId),
    'action'    => Labels::getLabel('LBL_Action', $adminLangId),
);
$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $key => $val) {
    $th->appendElement('th', array(), $val);
}

$sr_no = 0;
foreach ($records as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr', array( ));

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
                break;
            case 'user':
                !empty($row['credential_username']) ? $td->appendElement('a', array('href' => 'javascript:void(0)', 'onClick' => 'redirectfunc("'.CommonHelper::generateUrl('Users').'",'.$row['afile_record_id'].')'), $row['credential_username'].'( '.$row['credential_email'].' )') : $td->appendElement('plaintext', array(), 'Admin', true);
                break;
            case 'afile_physical_path':
                $path = AttachedFile::FILETYPE_BULK_IMAGES_PATH . $row['afile_physical_path'];
                $td->appendElement('plaintext', array(), $path, true);
                break;
            case 'files':
                $fullPath = CONF_UPLOADS_PATH . AttachedFile::FILETYPE_BULK_IMAGES_PATH . $row['afile_physical_path'];
                $count = Labels::getLabel('LBL_NA', $adminLangId);
                if (file_exists($fullPath)) {
                    $allFiles = scandir($fullPath);
                    $files_count = array_diff($allFiles, array( '..', '.' ));
                    $count = count($files_count);
                }
                $td->appendElement('plaintext', array(), $count, true);
                break;
            case 'action':
                $ul = $td->appendElement("ul", array("class"=>"actions actions--centered"));

                $li = $ul->appendElement("li", array('class'=>'droplink'));
                $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Delete', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                $innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
                $innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));

                $li = $innerUl->appendElement("li");
                $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 'title'=>Labels::getLabel('LBL_Delete', $adminLangId),"onclick"=>"removeDir('".base64_encode(AttachedFile::FILETYPE_BULK_IMAGES_PATH . $row['afile_physical_path'])."')"), Labels::getLabel('LBL_Delete', $adminLangId), true);
                $li = $innerUl->appendElement("li");
                $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 'title'=>Labels::getLabel('LBL_Download', $adminLangId),"onclick"=>"downloadPathsFile('".base64_encode($fullPath)."')"), Labels::getLabel('LBL_Download', $adminLangId), true);
                break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}
if (count($records) == 0) {
    $tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Labels::getLabel('LBL_No_Records_Found', $adminLangId));
}

echo $tbl->getHtml(); 