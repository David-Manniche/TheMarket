<div class="popup__body">
    <div class="img-container">
      <img src="<?php echo (isset($image)) ? $image : ''; ?>" alt="Picture" id="new-img" class="img_responsive cropper-hidden">
    </div>
    <span class="gap"></span>
    <div class="align--center rotator-actions" id="actions" >
        <div class="docs-buttons">
            <div class="align--center rotator-actions">
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-option="-90" data-method="rotate"><?php echo Labels::getLabel('LBL_Rotate_Left', $siteLangId); ?></a>
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-option="90" data-method="rotate"><?php echo Labels::getLabel('LBL_Rotate_Right', $siteLangId); ?></a>
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-option="-1" data-method="scaleX"><?php echo Labels::getLabel('LBL_Flip_Horizontal', $siteLangId); ?></a>
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-option="-1" data-method="scaleY"><?php echo Labels::getLabel('LBL_Flip_Vertical', $siteLangId); ?></a>
                <label class="btn btn-primary btn--sm" for="inputImage" title="Upload image file">
                  <input type="file" class="sr-only" id="inputImage" onchange="testing()" name="file" accept="image/*"> <?php echo Labels::getLabel('LBL_Upload_New_Image', $siteLangId); ?>
                </label>
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-method="getCroppedCanvas"><?php echo Labels::getLabel('LBL_Update', $siteLangId); ?></a>
            </div>
        </div>
    </div>
</div>
