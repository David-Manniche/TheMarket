<div class="sectionhead">
	<h4><?php echo Labels::getLabel('LBL_Image_Attributes', $adminLangId); ?> </h4>
	<?php
	if ($canEdit) {
		$data = [
			'adminLangId' => $adminLangId,
			'statusButtons' => false,
			'otherButtons' => [
				[
					'attr' => [
						'href' => 'javascript:void(0)',
						'onclick' => 'urlForm(0)',
						'title' => Labels::getLabel('LBL_Add_New', $adminLangId)
					],
					'label' => '<i class="fas fa-plus"></i>'
				],
			]
		];

		$this->includeTemplate('_partial/action-buttons.php', $data, false);
	}
	?>
</div>
<div class="sectionbody">
	<div class="tablewrap">
		<div id="listing"> <?php echo Labels::getLabel('LBL_Processing...', $adminLangId); ?></div>
	</div>
</div>