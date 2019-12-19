<div class="row">
  <div class="col-md-9">
    <!-- <h3>Demo:</h3> -->
    <div class="img-container">
      <img src="<?php echo CONF_WEBROOT_URL; ?>images/about-what-we-do.jpg" alt="Picture">
    </div>
  </div>
  <div class="col-md-3">
    <!-- <h3>Preview:</h3> -->
    <div class="docs-preview clearfix">
      <div class="img-preview preview-lg"></div>
      <div class="img-preview preview-md"></div>
      <div class="img-preview preview-sm"></div>
      <div class="img-preview preview-xs"></div>
    </div>
  </div>
</div>
<div class="row" id="actions">
  <div class="col-md-9 docs-buttons">
    <div class="btn-group">
      <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoom(0.1)">
          <span class="fa fa-search-plus"></span>
        </span>
      </button>
      <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoom(-0.1)">
          <span class="fa fa-search-minus"></span>
        </span>
      </button>
    </div>

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
      <button type="button" class="btn btn-primary" data-method="crop" title="Crop">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.crop()">
          <span class="fa fa-check"></span>
        </span>
      </button>
      <button type="button" class="btn btn-primary" data-method="clear" title="Clear">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.clear()">
          <span class="fa fa-times"></span>
        </span>
      </button>
    </div>

    <div class="btn-group">
      <button type="button" class="btn btn-primary" data-method="disable" title="Disable">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.disable()">
          <span class="fa fa-lock"></span>
        </span>
      </button>
      <button type="button" class="btn btn-primary" data-method="enable" title="Enable">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.enable()">
          <span class="fa fa-unlock"></span>
        </span>
      </button>
    </div>

    <div class="btn-group">
      <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.reset()">
          <span class="fa fa-sync-alt"></span>
        </span>
      </button>
      <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
        <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
        <span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs">
          <span class="fa fa-upload"></span>
        </span>
      </label>
      <button type="button" class="btn btn-primary" data-method="destroy" title="Destroy">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.destroy()">
          <span class="fa fa-power-off"></span>
        </span>
      </button>
    </div>

    <div class="btn-group btn-group-crop">
      <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ maxWidth: 4096, maxHeight: 4096 })">
          Get Cropped Canvas
        </span>
      </button>
      <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 160, &quot;height&quot;: 90 }">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 160, height: 90 })">
          160&times;90
        </span>
      </button>
      <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 320, &quot;height&quot;: 180 }">
        <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 320, height: 180 })">
          320&times;180
        </span>
      </button>
    </div>

    <!-- Show the cropped image in modal -->
    <div class="modal fade docs-cropped" id="getCroppedCanvasModal" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="getCroppedCanvasTitle">Cropped</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.jpg">Download</a>
          </div>
        </div>
      </div>
    </div><!-- /.modal -->

    <button type="button" class="btn btn-secondary" data-method="getData" data-option data-target="#putData">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getData()">
        Get Data
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="setData" data-target="#putData">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setData(data)">
        Set Data
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="getContainerData" data-option data-target="#putData">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getContainerData()">
        Get Container Data
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="getImageData" data-option data-target="#putData">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getImageData()">
        Get Image Data
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="getCanvasData" data-option data-target="#putData">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCanvasData()">
        Get Canvas Data
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="setCanvasData" data-target="#putData">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setCanvasData(data)">
        Set Canvas Data
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="getCropBoxData" data-option data-target="#putData">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCropBoxData()">
        Get Crop Box Data
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="setCropBoxData" data-target="#putData">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setCropBoxData(data)">
        Set Crop Box Data
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="moveTo" data-option="0">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.moveTo(0)">
        Move to [0,0]
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="zoomTo" data-option="1">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoomTo(1)">
        Zoom to 100%
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="rotateTo" data-option="180">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotateTo(180)">
        Rotate 180°
      </span>
    </button>
    <button type="button" class="btn btn-secondary" data-method="scale" data-option="-2" data-second-option="-1">
      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scale(-2, -1)">
        Scale (-2, -1)
      </span>
    </button>
    <textarea class="form-control" id="putData" placeholder="Get data to here or set data with this value"></textarea>
  </div><!-- /.docs-buttons -->
</div>