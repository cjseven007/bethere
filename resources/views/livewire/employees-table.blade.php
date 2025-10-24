{{-- resources/views/livewire/employees-table.blade.php --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <input type="text" class="form-control w-auto" style="min-width: 240px;" placeholder="Search employees..."
            wire:model.live.debounce.300ms="search" />
        <div wire:loading class="text-muted small">
            <i class="bi bi-arrow-repeat me-1"></i> Loading...
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Face Embedded</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $index=>$e)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $e->name }}</td>
                            <td>{{ $e->email }}</td>
                            <td>
                                @if ($e->embedding)
                                    <span class="badge bg-success">Present</span>
                                @else
                                    <span class="badge bg-secondary">Missing</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if (!$e->embedding)
                                    <button class="btn btn-sm btn-primary me-1" data-employee-id="{{ $e->id }}"
                                        data-employee-name="{{ $e->name }}" onclick="FaceEmbed.open(this)">
                                        <i class="bi bi-camera-video"></i> Capture Face
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-primary me-1"
                                        data-employee-id="{{ $e->id }}" data-employee-name="{{ $e->name }}"
                                        onclick="FaceEmbed.open(this)">
                                        <i class="bi bi-pencil-square"></i> Edit Face
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="tooltip"
                                    title="Edit Info" data-employee-id="{{ $e->id }}"
                                    data-employee-name="{{ $e->name }}" data-employee-email="{{ $e->email }}"
                                    onclick="EmpActions.openEdit(this)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                {{-- Delete --}}
                                <button class="btn btn-sm btn-outline-secondary text-danger" data-bs-toggle="tooltip"
                                    title="Delete" data-employee-id="{{ $e->id }}"
                                    data-employee-name="{{ $e->name }}" onclick="EmpActions.openDelete(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($employees->hasPages())
            <div class="card-footer">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

    {{-- Modal --}}
    {{-- Embed Face Modal --}}
    <div class="modal fade" id="embedModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="embedModalTitle" class="modal-title">Embed Face</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="FaceEmbed.close()"></button>
                </div>

                <div class="modal-body">
                    {{-- Tabs: Upload | Camera --}}
                    <ul class="nav nav-tabs mb-3" id="embedTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-upload" data-bs-toggle="tab"
                                data-bs-target="#pane-upload" type="button" role="tab" aria-controls="pane-upload"
                                aria-selected="true">
                                <i class="bi bi-upload me-1"></i> Upload Image
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-camera" data-bs-toggle="tab" data-bs-target="#pane-camera"
                                type="button" role="tab" aria-controls="pane-camera" aria-selected="false">
                                <i class="bi bi-camera-video me-1"></i> Take Photo
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Upload pane --}}
                        <div class="tab-pane fade show active" id="pane-upload" role="tabpanel"
                            aria-labelledby="tab-upload">
                            <div class="border rounded p-3">
                                <label class="form-label">Choose a face image (JPEG/PNG)</label>
                                <input type="file" class="form-control" id="uploadInput"
                                    accept="image/png,image/jpeg">
                                <div class="form-text">Max 6MB. Clear face preferred.</div>
                            </div>
                        </div>

                        {{-- Camera pane --}}
                        <div class="tab-pane fade" id="pane-camera" role="tabpanel" aria-labelledby="tab-camera">
                            <div class="text-center">
                                <div class="mb-2">
                                    <button class="btn btn-outline-primary btn-sm me-2" type="button"
                                        onclick="FaceEmbed.startCamera()">
                                        <i class="bi bi-play-fill"></i> Start
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                        onclick="FaceEmbed.stopCamera()">
                                        <i class="bi bi-stop-fill"></i> Stop
                                    </button>
                                </div>
                                <video id="camVideo" width="480" height="360" autoplay playsinline
                                    class="border rounded d-none"></video>
                                <div id="camPlaceholder" class="text-muted">Camera off</div>
                                <canvas id="camCanvas" width="480" height="360" class="d-none"></canvas>
                            </div>
                        </div>
                    </div>

                    <div id="embedStatus" class="small text-muted mt-3"></div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button"
                        onclick="FaceEmbed.close()">Close</button>
                    <button id="btnEmbedSave" class="btn btn-primary" type="button" onclick="FaceEmbed.submit()">
                        <i class="bi bi-cloud-upload me-1"></i> Save Embedding
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== Edit Modal ========== --}}
    <div class="modal fade" id="editEmpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="editEmpForm" class="modal-content" onsubmit="return EmpActions.submitEdit(event)">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="EmpActions.resetEdit()"></button>
                </div>
                <div class="modal-body">
                    <div id="eeAlert" class="alert d-none" role="alert"></div>
                    <input type="hidden" name="employee_id" id="eeId">

                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input name="name" id="eeName" type="text" class="form-control" required
                            maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input name="email" id="eeEmail" type="email" class="form-control" required
                            maxlength="255">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal"
                        onclick="EmpActions.resetEdit()">Cancel</button>
                    <button id="eeSubmitBtn" class="btn btn-primary" type="submit">
                        <i class="bi bi-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== Delete Confirm Modal ========== --}}
    <div class="modal fade" id="delEmpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="delTitle" class="modal-title">Delete Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="EmpActions.resetDelete()"></button>
                </div>
                <div class="modal-body">
                    <div id="deAlert" class="alert d-none" role="alert"></div>
                    <p class="mb-1">Are you sure you want to delete <strong id="delName">this employee</strong>?
                    </p>
                    <p class="mb-0 text-muted small">This will also remove their embeddings (if any).</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal"
                        onclick="EmpActions.resetDelete()">Cancel</button>
                    <button id="deSubmitBtn" class="btn btn-danger" type="button"
                        onclick="EmpActions.submitDelete()">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.FaceEmbed = (function() {
            const modalEl = document.getElementById('embedModal');
            const titleEl = document.getElementById('embedModalTitle');
            const uploadInp = document.getElementById('uploadInput');

            // Camera elements
            const video = document.getElementById('camVideo');
            const canvas = document.getElementById('camCanvas');
            const placeholder = document.getElementById('camPlaceholder');

            const statusEl = document.getElementById('embedStatus');
            const btnSave = document.getElementById('btnEmbedSave');

            let modal, stream = null,
                employeeId = null,
                employeeName = null;

            function setStatus(msg, cls = 'text-muted') {
                statusEl.className = 'small ' + cls;
                statusEl.textContent = msg;
            }

            function open(btn) {
                employeeId = btn.getAttribute('data-employee-id');
                employeeName = btn.getAttribute('data-employee-name') || ('#' + employeeId);
                titleEl.textContent = 'Embed Face for ' + employeeName;
                setStatus('Choose an image to upload, or use the camera.', 'text-muted');

                uploadInp.value = '';
                stopCamera();
                showVideo(false);

                modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }

            function close() {
                stopCamera();
                modal && modal.hide();
                modal = null;
            }

            function showVideo(on) {
                video.classList.toggle('d-none', !on);
                placeholder.classList.toggle('d-none', on);
            }

            async function startCamera() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: {
                                ideal: 640
                            },
                            height: {
                                ideal: 480
                            },
                            facingMode: 'user'
                        },
                        audio: false
                    });
                    video.srcObject = stream;
                    await new Promise(res => video.onloadedmetadata = () => {
                        video.play().then(res).catch(() => res());
                    });
                    showVideo(true);
                    setStatus('Camera ready. Click Save Embedding to capture & upload.', 'text-success');
                } catch (e) {
                    setStatus('Camera error: ' + (e?.name || e), 'text-danger');
                }
            }

            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(t => t.stop());
                    stream = null;
                }
                showVideo(false);
            }

            function getBlobFromCamera() {
                if (!stream) return null;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                return new Promise(res => canvas.toBlob(res, 'image/jpeg', 0.95));
            }

            async function submit() {
                try {
                    btnSave.disabled = true;
                    setStatus('Preparing image…');

                    let blob = null;

                    // Priority: uploaded file; otherwise camera
                    if (uploadInp.files && uploadInp.files[0]) {
                        blob = uploadInp.files[0];
                    } else if (stream) {
                        blob = await getBlobFromCamera();
                    } else {
                        setStatus('Please upload an image or start the camera.', 'text-danger');
                        btnSave.disabled = false;
                        return;
                    }

                    // Build multipart for our Laravel endpoint (which calls FastAPI /embed, then UPSERTs)
                    const fd = new FormData();
                    fd.append('image', blob, 'face.jpg');

                    setStatus('Contacting embed service…');
                    const url = @json(route('employees.embed', ['employee' => 'EMP_ID_PLACEHOLDER']));
                    const postUrl = url.replace('EMP_ID_PLACEHOLDER', employeeId);

                    const r = await fetch(postUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token())
                        },
                        body: fd
                    });
                    const data = await r.json();

                    if (!r.ok || !data.ok) {
                        setStatus('Server error: ' + (data.error || r.statusText), 'text-danger');
                        btnSave.disabled = false;
                        return;
                    }

                    setStatus('Embedding saved!', 'text-success');
                    // Notify Livewire to refresh the table
                    if (window.Livewire?.dispatch) {
                        Livewire.dispatch('embeddingSaved');
                    }
                    setTimeout(close, 600);
                } catch (e) {
                    setStatus('Upload error: ' + e, 'text-danger');
                } finally {
                    btnSave.disabled = false;
                }
            }

            return {
                open,
                close,
                startCamera,
                stopCamera,
                submit
            };
        })();

        (function() {
            // Bootstrap tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el);
            });
        })();

        function lwEvent(name, payload = {}) {
            if (window.Livewire?.dispatch) return Livewire.dispatch(name, payload); // v3
            if (window.Livewire?.emit) return Livewire.emit(name, payload); // v2
        }

        window.EmpActions = (function() {
            // ==== Edit wiring ====
            const editModalEl = document.getElementById('editEmpModal');
            const editModal = bootstrap.Modal.getOrCreateInstance(editModalEl);
            const eeAlert = document.getElementById('eeAlert');
            const eeForm = document.getElementById('editEmpForm');
            const eeId = document.getElementById('eeId');
            const eeName = document.getElementById('eeName');
            const eeEmail = document.getElementById('eeEmail');
            const eeBtn = document.getElementById('eeSubmitBtn');

            function eeShowAlert(type, msg) {
                eeAlert.className = 'alert alert-' + type;
                eeAlert.textContent = msg;
                eeAlert.classList.remove('d-none');
            }

            function eeClear() {
                eeAlert.className = 'alert d-none';
                eeAlert.textContent = '';
            }

            function eeDisable(on) {
                eeBtn.disabled = !!on;
                eeBtn.innerHTML = on ?
                    '<span class="spinner-border spinner-border-sm me-1"></span> Saving...' :
                    '<i class="bi bi-save me-1"></i> Save';
            }

            function openEdit(btn) {
                eeClear();
                eeId.value = btn.getAttribute('data-employee-id');
                eeName.value = btn.getAttribute('data-employee-name');
                eeEmail.value = btn.getAttribute('data-employee-email');
                editModal.show();
            }

            function resetEdit() {
                eeClear();
                eeForm.reset();
                eeDisable(false);
            }

            async function submitEdit(e) {
                e.preventDefault();
                eeClear();
                eeDisable(true);

                const id = eeId.value;
                const fd = new FormData(eeForm);

                try {
                    const res = await fetch(@json(route('employees.update', '__ID__')).replace('__ID__', id), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token()),
                            'X-HTTP-Method-Override': 'PUT'
                        },
                        body: fd
                    });
                    const data = await res.json().catch(() => ({}));

                    if (!res.ok || !data.ok) {
                        let msg = 'Failed to update.';
                        if (res.status === 422 && data?.errors) msg = Object.values(data.errors).flat().join(
                            ' ');
                        else if (data?.error) msg = data.error;
                        eeShowAlert('danger', msg);
                        eeDisable(false);
                        return;
                    }

                    lwEvent('employeeUpdated');
                    resetEdit();
                    editModal.hide();
                } catch (err) {
                    eeShowAlert('danger', 'Network error: ' + err);
                    eeDisable(false);
                }
            }

            editModalEl.addEventListener('hidden.bs.modal', () => {
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            });

            // ==== Delete wiring ====
            const delModalEl = document.getElementById('delEmpModal');
            const delModal = bootstrap.Modal.getOrCreateInstance(delModalEl);
            const deAlert = document.getElementById('deAlert');
            const deBtn = document.getElementById('deSubmitBtn');
            const delName = document.getElementById('delName');
            let deleteId = null;

            function deShowAlert(type, msg) {
                deAlert.className = 'alert alert-' + type;
                deAlert.textContent = msg;
                deAlert.classList.remove('d-none');
            }

            function deClear() {
                deAlert.className = 'alert d-none';
                deAlert.textContent = '';
            }

            function deDisable(on) {
                deBtn.disabled = !!on;
                deBtn.innerHTML = on ?
                    '<span class="spinner-border spinner-border-sm me-1"></span> Deleting...' :
                    '<i class="bi bi-trash me-1"></i> Delete';
            }

            function openDelete(btn) {
                deleteId = btn.getAttribute('data-employee-id');
                delName.textContent = btn.getAttribute('data-employee-name') || 'this employee';
                deClear();
                delModal.show();
            }

            function resetDelete() {
                deleteId = null;
                deClear();
                deDisable(false);
            }

            async function submitDelete() {
                if (!deleteId) return;
                deClear();
                deDisable(true);
                try {
                    const res = await fetch(@json(route('employees.destroy', '__ID__')).replace('__ID__', deleteId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token()),
                            'X-HTTP-Method-Override': 'DELETE'
                        },
                    });
                    const data = await res.json().catch(() => ({}));

                    if (!res.ok || !data.ok) {
                        deShowAlert('danger', data?.error || 'Failed to delete.');
                        deDisable(false);
                        return;
                    }

                    lwEvent('employeeDeleted');
                    resetDelete();
                    delModal.hide();
                } catch (err) {
                    deShowAlert('danger', 'Network error: ' + err);
                    deDisable(false);
                }
            }

            delModalEl.addEventListener('hidden.bs.modal', () => {
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            });

            // Expose
            return {
                openEdit,
                resetEdit,
                submitEdit,
                openDelete,
                resetDelete,
                submitDelete
            };
        })();
    </script>
@endpush
