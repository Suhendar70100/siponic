<input type="hidden" id="id">

<div class="modal fade" id="addDeviceButton" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body p-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2 pb-1 title">Tambah Device Baru</h3>
        </div>
        <form id="addNewCCForm" class="row g-4" onsubmit="return false">
          <div class="col-12">
              <div class="form-floating form-floating-outline">
                <select id="garden_id" name="garden" class="form-control list-garden">
                  <option value=""></option>
                  @foreach($garden as $item)
                      <option value="{{ $item->id }}">{{ $item->name }}</option>
                  @endforeach
              </select>        
                <div class="invalid-feedback"></div>
                <label for="modalAddCard">Nama Perkebunan</label>
              </div>
          </div>
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <input id="max_ppm" name="max_ppm" class="form-control credit-card-mask" type="number" placeholder="maksimal nutrisi"/>
              <div class="invalid-feedback"></div>
              <label for="modalAddCard">Maksimal Nutrisi</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <input id="min_ppm" name="min_ppm" class="form-control credit-card-mask" type="number" placeholder="minimal nutrisi"/>
              <div class="invalid-feedback"></div>
              <label for="modalAddCard">Minimal Nutrisi</label>
            </div>
          </div>
          <div class="col-12">
              <div class="form-floating form-floating-outline">
                <p class="invalid-feedback"></p>
                <input id="plants" name="plants" class="form-control credit-card-mask" type="text" placeholder="Tanaman"/>
                <div class="invalid-feedback"></div>
                <label for="modalAddCard">Tanaman</label>
              </div>
          </div>
          <div class="col-12 text-center">
            <button type="submit" id="submit-button" class="btn btn-primary me-sm-3 me-1">Submit</button>
            <button type="reset" class="btn btn-outline-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>