<div class="nk-upload-form">
    <h5 class="title mb-3">Lihat Parameter</h5>
</div>
<div class="nk-upload-list">
    @csrf
    <div class="row g">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="client_id">Client ID</label>
                <div class="form-control-wrap">
                    <input name="client_id" readonly type="text" value="{{ $data_parameter->client_id }}"
                        class="form-control form-control-sm" id="client_id" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="client_secret">Secret ID</label>
                <div class="form-control-wrap">
                    <input name="client_secret" readonly type="text" value="{{ $data_parameter->client_secret }}"
                        class="form-control form-control-sm" id="client_secret" required>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="auth_url">Auth URL</label>
                <div class="form-control-wrap">
                    <input name="auth_url" readonly type="text" value="{{ $data_parameter->auth_url }}"
                        class="form-control form-control-sm" id="auth_url" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="base_url">Base URL</label>
                <div class="form-control-wrap">
                    <input name="base_url" readonly type="text" value="{{ $data_parameter->base_url }}"
                        class="form-control form-control-sm" id="base_url" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="consent_url">Consent URL</label>
                <div class="form-control-wrap">
                    <input name="consent_url" readonly type="text" value="{{ $data_parameter->consent_url }}"
                        class="form-control form-control-sm" id="consent_url" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="generate_token_url">Hit URL</label>
                <div class="form-control-wrap">
                    <input name="generate_token_url" readonly type="text"
                        value="{{ $data_parameter->generate_token_url }}" class="form-control form-control-sm"
                        id="generate_token_url" required>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="organization_id">Organization ID</label>
                <div class="form-control-wrap">
                    <input name="organization_id" readonly type="text" value="{{ $data_parameter->organization_id }}"
                        class="form-control form-control-sm" id="organization_id" required>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="access_token">Token</label>
                <div class="form-control-wrap">
                    <input name="access_token" readonly type="text" value="{{ $data_parameter->access_token }}"
                        class="form-control form-control-sm" id="access_token" required>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="expiry_token">Expiry Token</label>
                <div class="form-control-wrap">
                    <input name="expiry_token" readonly type="text" value="{{ $data_parameter->expiry_token }}"
                        class="form-control form-control-sm" id="expiry_token" required>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="username">Username (Hit)</label>
                <div class="form-control-wrap">
                    <input name="username" readonly type="text" value="{{ $data_parameter->username }}"
                        class="form-control form-control-sm" id="username" required>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="pass">Password (Hit)</label>
                <div class="form-control-wrap">
                    {{-- <a href="#" class="form-icon form-icon-right passcode-switch" data-target="pass">
                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                    </a> --}}
                    <input name="pass" readonly type="password" value="{{ $data_parameter->pass }}"
                        class="form-control form-control-sm" id="pass" required>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="farmasi_id">Farmasi ID</label>
                <div class="form-control-wrap">
                    <input name="farmasi_id" readonly type="text" value="{{ $data_parameter->farmasi_id }}"
                        class="form-control form-control-sm" id="farmasi_id" required>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="form-group">
                <button data-dismiss="modal" class="btn btn-lg btn-secondary btn-sm btn-action">Keluar</button>
            </div>
        </div>
    </div>

</div>
{{-- <div class="nk-modal-action justify-end">
    <ul class="btn-toolbar g-4 align-center">
        <li><button data-dismiss="modal" class="link link-primary">Cancel</button></li>
        <li><button class="btn btn-primary">Add Files</button></li>
    </ul>
</div> --}}