<section class="section">
	<div class="sectionhead">
		<h4><?php echo Labels::getLabel('LBL_TAX_STRUCTURE_SETUP', $adminLangId); ?></h4>
	</div>
	<div class="sectionbody space">      
		<div class="tabs_nav_container responsive flat">
			<ul class="tabs_nav">
				<li><a href="javascript:void(0);" onclick="structureForm(<?php echo $taxStrId ?>);"><?php echo Labels::getLabel('LBL_General',$adminLangId); ?></a></li>
				<?php 
				if ($taxStrId > 0) {
					foreach($languages as $langId => $langName){?>
						<li><a href="javascript:void(0);" onclick="addLangForm(<?php echo $taxStrId ?>, <?php echo $langId;?>);"><?php echo $langName;?></a></li>
					<?php } ?>
                    <li><a class="active" href="javascript:void(0);" onclick="options(<?php echo $taxStrId ?>);"><?php echo Labels::getLabel('LBL_Tax_Options',$adminLangId); ?></a></li>
					<?php }
				?>
			</ul>
			<div class="tabs_panel_wrap">
				<div class="tabs_panel">
                    <?php
                    $arr_flds = array(
                            'listserial' => Labels::getLabel('LBL_Sr._No', $adminLangId),
                            'taxstro_identifier' => Labels::getLabel('LBL_Sale_Tax', $adminLangId),        
                            'action' => Labels::getLabel('LBL_Action', $adminLangId),
                        );

                    $tbl = new HtmlElement('table', array('width' => '100%', 'class' => 'table table--hovered table-responsive'));
                    $th = $tbl->appendElement('thead')->appendElement('tr');
                    foreach ($arr_flds as $key => $val) {
                        if ('select_all' == $key) {
                            $th->appendElement('th')->appendElement('plaintext', array(), '<label class="checkbox"><input title="' . $val . '" type="checkbox" onclick="selectAll( $(this) )" class="selectAll-js"><i class="input-helper"></i></label>', true);
                        } else {
                            $e = $th->appendElement('th', array(), $val);
                        }
                    }

                    $sr_no = 0;
                    foreach ($listing as $sn => $row) {
                        $sr_no++;
                        $tr = $tbl->appendElement('tr', array());
                        $tr->setAttribute("id", $row['taxstr_id']);

                        foreach ($arr_flds as $key => $val) {
                            $td = $tr->appendElement('td');
                            switch ($key) {           
                                case 'listserial':
                                    $td->appendElement('plaintext', array(), $sr_no);
                                    break;
                                case 'taxstro_identifier': 
                                    if (empty($row['taxstro_name'])) {
                                        $td->appendElement('plaintext', array(), $row['taxstro_name'], true);
                                        $td->appendElement('br', array());
                                        $td->appendElement('plaintext', array(), '(' . $row[$key] . ')', true);
                                    } else {
                                        $td->appendElement('plaintext', array(), $row[$key], true);
                                    }
                                    break;            
                                case 'action':
                                    $ul = $td->appendElement("ul", array("class"=>"actions actions--centered"));
                                    if ($canEdit) {
                                        $li = $ul->appendElement("li", array('class'=>'droplink'));
                                        $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_MANAGE_TAX_STRUCTURE', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                                        $innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
                                        $innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));

                                       /*  $innerLi=$innerUl->appendElement('li');
                                        $innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Edit', $adminLangId),"onclick"=>"editOptions(".$row['taxstro_id'].")"), Labels::getLabel('LBL_Edit', $adminLangId), true); */    
                                    }
                                    break;
                                default:
                                    $td->appendElement('plaintext', array(), $row[$key], true);
                                    break;
                            }
                        }
                    }

                    if (count($listing) == 0) {
                        $tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Labels::getLabel('LBL_No_Records_Found', $adminLangId));
                    }
                    echo $tbl->getHtml(); ?>
                       </div>
			</div>						
		</div>
	</div>						
</section>
