<div class="modal fade c-modal-bg" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle"
    aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Edit Data Kuitansi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18">
                        </line>
                        <line x1="6" y1="6" x2="18" y2="18">
                        </line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4 row">
                        <label for="selectTypeReceipt" class="col-sm-3 col-form-label">Jenis Kuitansi</label>
                        <div class="col-sm-9">
                            <select name="type" class="form-select" id="selectTypeReceipt">
                                <option selected disabled value="">Pilih Jenis Kuitansi...</option>
                                <option value="direct">Pembayaran Langsung</option>
                                <option value="treasurer">Pembayaran Langsung (Bendahara)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <label for="selectPerjadinReceipt" class="col-sm-3 col-form-label">Perjalanan Dinas</label>
                        <div class="col-sm-9">
                            <select name="perjadin" class="form-select" id="selectPerjadinReceiptEdit">
                                <option selected disabled value="">Pilih ...</option>
                                <option value="Y">Perjalanan Dinas</option>
                                <option value="N">Non Perjalanan Dinas</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4 row" id="wrapperSpdNumberEdit">
                        <label for="inputSpdNumberEdit" class="col-sm-3 col-form-label">Nomor SPD
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="spd_number" id="inputSpdNumberEdit">
                        </div>
                    </div>

                    <div class="mb-4 row" id="wrapperSpdTujuanEdit">
                        <label for="inputSpdTujuanEdit" class="col-sm-3 col-form-label">Tujuan SPD
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="spd_tujuan" id="inputSpdTujuanEdit">
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <label for="inputDisbursementDescription" class="col-sm-3 col-form-label">Uraian
                            Pencairan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="description"
                                id="inputDisbursementDescription">
                        </div>
                    </div>
                    <div class="mb-4 row pelaksanaWrapper ">
                        <label for="selectActivityExecutor" class="col-sm-3 col-form-label">Pelaksana Kegiatan</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="activity_implementer" id="editSelectPelaksana">
                                <option selected disabled value="">Pilih Pelaksana...</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4 row pengikutWrapperEdit " id="pengikutWrapperEdit">
                        <label for="selectActivityExecutor" class="col-sm-3 col-form-label">Pengikut
                            Kegiatan</label>
                        <div class="col-sm-9">
                            <select class="form-select" style="width:100% !important" multiple="multiple"
                                name="activity_followings[]" id="createSelectPengikutEdit">
                            </select>
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <label for="inputActivityDate" class="col-sm-3 col-form-label">Tanggal Kegiatan</label>
                        <div class="col-sm-9 flatpickr">
                            <input id="inputDateEdit" name="activity_date"
                                class="form-control flatpickr flatpickr-input active text-dark" type="date"
                                placeholder="Pilih tanggal..">
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <label for="inputAmount" class="col-sm-3 col-form-label">Jumlah</label>
                        <div class="col-sm-9">
                            <input type="text" name="amount" class="form-control mask-number" id="inputAmount">
                        </div>
                    </div>
                    <div class="mb-4 row treasurerWrapper ">
                        <label for="selectActivityExecutor" class="col-sm-3 col-form-label">Bendahara</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="treasurer" id="editSelectTreasurer">
                                <option selected disabled value="">Pilih Bendahara...</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4 row ppkWrapper">
                        <label for="selectVerifier" class="col-sm-3 col-form-label">PPK</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="ppk" id="editSelectPPK">
                                <option selected disabled value="">Pilih PPK...</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <label for="inputSupplierName" class="col-sm-3 col-form-label">Penyedia PIC</label>
                        <div class="col-sm-9">
                            <input type="text" name="provider" class="form-control" id="inputSupplierName">
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <label for="inputSupplierOrganizationName" class="col-sm-3 col-form-label">Penyedia
                            Badan</label>
                        <div class="col-sm-9">
                            <input type="text" name="provider_organization" class="form-control"
                                id="inputSupplierOrganizationName">
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <label for="selectActivityCodeEdit" class="col-sm-3 col-form-label">Kode Kegiatan</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="selectActivityCodeEdit">
                                <option selected disabled value="">Pilih Kode Kegiatan...</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <label for="selectAccountCodeEdit" class="col-sm-3 col-form-label">Kode Akun</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="selectAccountCodeEdit" name="bi_id">
                                <option selected disabled value="">Pilih Kode Akun...</option>
                            </select>
                        </div>
                    </div>
                    <button id="submitFormedit"
                        class="btn btn-warning text-center align-items-center float-end py-auto" type="submit">
                        <span class="icon-name">Update</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
