<div class="popup__body">
    <div class="img-container">
      <img src="<?php echo CONF_WEBROOT_URL; ?>images/about-what-we-do.jpg" alt="Picture" class="img_responsive cropper-hidden">
    </div>
    <span class="gap"></span>
    <div class="align--center rotator-actions" id="actions" >
        <div class="docs-buttons">
            <div class="btn-group">
              <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(-45)">
                  <span class="fa fa-undo-alt"></span>
                </span>
              </button>
              <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(45)">
                  <span class="fa fa-redo-alt"></span>
                </span>
              </button>
            </div>
            <div class="btn-group">
              <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleX(-1)">
                  <span class="fa fa-arrows-alt-h"></span>
                </span>
              </button>
              <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleY(-1)">
                  <span class="fa fa-arrows-alt-v"></span>
                </span>
              </button>
            </div>
            <div class="btn-group">
              <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
                <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
                <span class="docs-tooltip" data-toggle="tooltip" title="Import image">
                  <span class="fa fa-upload"></span>
                </span>
              </label>
              <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
                <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleY(-1)">
                  <span class="fa fa-arrows-alt-v"></span>
                </span>
              </button>
            </div>
        </div>
    </div>
</div>