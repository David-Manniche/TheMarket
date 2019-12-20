<div class="popup__body">
    <div class="img-container">
      <img src="<?php echo $userImage; ?>" alt="Picture" id="new-img" class="img_responsive cropper-hidden">
    </div>
    <span class="gap"></span>
    <div class="align--center rotator-actions" id="actions" >
        <div class="docs-buttons">
            <div class="align--center rotator-actions">
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-option="-90" data-method="rotate">Rotate Left</a>
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-option="90" data-method="rotate">Rotate Right</a>
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-option="-1" data-method="scaleX">Flip Horizontal</a>
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-option="-1" data-method="scaleY">Flip Vertical</a>
                <label class="btn btn-primary btn--sm" for="inputImage" title="Upload image file">
                  <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
                  Upload image file
                </label>
                <a href="javascript:void(0)" class="btn btn--primary btn--sm" data-method="getCroppedCanvas">Update</a>
            </div>
        </div>
    </div>
</div>
