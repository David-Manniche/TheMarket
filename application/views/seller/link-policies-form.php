<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box__head">
   <h4><?php echo Labels::getLabel('LBL_Product_Listing',$siteLangId); ?></h4>										
</div>
<div class="box__body">	
	<div class="tabs tabs--small tabs--offset tabs--scroll clearfix">
		<?php require_once('sellerCatalogProductTop.php');?>
	</div>
	<div class="tabs__content form">
		
		<div class="form__content">
			<div class="col-md-12">
				<div class="container container--fluid">
					<div class="tabs--inline tabs--scroll clearfix">
						<ul>
							<li><a href="javascript:void(0)" onClick="sellerProductForm(<?php echo $product_id,',',$selprod_id ?>)" ><?php echo Labels::getLabel('LBL_Basic',$siteLangId); ?></a></li>
							<?php $inactive = ($selprod_id==0)?'fat-inactive':'';		
							foreach($language as $langId => $langName){?>	
							<li class="<?php echo $inactive ; ?>"><a href="javascript:void(0)" <?php if($selprod_id>0){?> onClick="sellerProductLangForm(<?php echo $langId;?>,<?php echo $selprod_id;?>)" <?php }?>>
							<?php echo $langName;?></a></li>
							<?php }?>
							<li class="<?php echo $inactive; echo ($ppoint_type == PolicyPoint::PPOINT_TYPE_WARRANTY)?'is-active':''; ?>"><a href="javascript:void(0)" <?php if($selprod_id>0){?>  onClick="linkPoliciesForm(<?php echo $product_id,',',$selprod_id,',',PolicyPoint::PPOINT_TYPE_WARRANTY ; ?>)" <?php }?>><?php echo Labels::getLabel('LBL_Link_Warranty_Policies',$siteLangId); ?></a></li>
							<li class="<?php echo $inactive; echo ($ppoint_type == PolicyPoint::PPOINT_TYPE_RETURN)?'is-active':''; ?>"><a href="javascript:void(0)" <?php if($selprod_id>0){?>  onClick="linkPoliciesForm(<?php echo $product_id,',',$selprod_id,',',PolicyPoint::PPOINT_TYPE_RETURN ; ?>)" <?php }?>><?php echo Labels::getLabel('LBL_Link_Return_Policies',$siteLangId); ?></a></li>
						</ul>
					</div>
				</div>
				<div class="form__subcontent">
					<?php echo $frm->getFormHtml(); ?>
					<div id="listPolicies" class="col-md-12">
					</div>
				</div>	
			</div>	
		</div>
	</div>
</div>