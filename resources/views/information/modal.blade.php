<input type="hidden" id="id">

<div class="modal fade" id="addInformationButton" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body p-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2 pb-1 title">Tambah Informarsi Baru</h3>
        </div>
        <form id="addNewCCForm" class="row g-4" onsubmit="return false">
          <div class="col-12">
              <div class="form-floating form-floating-outline">
                <select id="device_id" name="device" class="form-control list-device">
                  <option value=""></option>
                  @foreach($device as $item)
                      <option value="{{ $item->id }}">{{ $item->guid }} - {{ $item->plants }}</option>
                  @endforeach
              </select>        
                <div class="invalid-feedback"></div>
                <label for="modalAddCard">Perangkat</label>
              </div>
          </div>
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <input id="seeding_start_date" name="seeding_start_date" class="form-control credit-card-mask" type="date"/>
              <div class="invalid-feedback"></div>
              <label for="modalAddCard">Penyemaian</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <input id="harvest_date" name="harvest_date" class="form-control credit-card-mask" type="date" placeholder=""/>
              <div class="invalid-feedback"></div>
              <label for="modalAddCard">Panen</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <input id="harvest_yield" name="harvest_yield" class="form-control credit-card-mask" type="number" placeholder="hasil panen"/>
              <div class="invalid-feedback"></div>
              <label for="modalAddCard">Hasil Panen</label>
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