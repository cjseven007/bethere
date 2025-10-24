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
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Embedding (buffalo)</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $e)
                        <tr>
                            <td>{{ $e->id }}</td>
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
                                    <button class="btn btn-sm btn-primary" data-employee-id="{{ $e->id }}"
                                        onclick="FaceEmbed.openCapture(this)">
                                        <i class="bi bi-camera-video me-1"></i> Capture Face
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-check2-circle me-1"></i> Embedded
                                    </button>
                                @endif
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

    {{-- Capture Modal --}}
    <div class="modal fade" id="captureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Capture Employee Face</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="FaceEmbed.close()"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <video id="capVideo" width="320" height="240" autoplay playsinline
                            class="border rounded"></video>
                        <canvas id="capCanvas" width="320" height="240" class="d-none"></canvas>
                    </div>
                    <div id="capStatus" class="small text-muted mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" onclick="FaceEmbed.close()">Close</button>
                    <button class="btn btn-primary" type="button" onclick="FaceEmbed.captureAndUpload()">
                        <i class="bi bi-upload me-1"></i> Capture & Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.FaceEmbed = (function() {
            const modalEl = document.getElementById('captureModal');
            const video = document.getElementById('capVideo');
            const canvas = document.getElementById('capCanvas');
            const status = document.getElementById('capStatus');
            let modal, employeeId, stream;

            function setStatus(msg, cls = 'text-muted') {
                status.className = 'small ' + cls;
                status.textContent = msg;
            }

            async function openCapture(btn) {
                employeeId = btn.getAttribute('data-employee-id');
                modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                setStatus('Requesting camera...');
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: 320,
                            height: 240
                        }
                    });
                    video.srcObject = stream;
                    modal.show();
                    setStatus('Camera ready. Click "Capture & Upload".', 'text-success');
                } catch (e) {
                    setStatus('Camera error: ' + e, 'text-danger');
                }
            }

            function close() {
                if (stream) {
                    stream.getTracks().forEach(t => t.stop());
                    stream = null;
                }
                if (modal) modal.hide();
            }

            async function captureAndUpload() {
                if (!stream) return setStatus('No camera stream.', 'text-danger');
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                setStatus('Capturing...');
                const blob = await new Promise(res => canvas.toBlob(res, 'image/jpeg', 0.95));

                setStatus('Uploading...');
                const fd = new FormData();
                fd.append('image', blob, 'face.jpg');

                try {
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
                    if (!data.ok) {
                        setStatus('Server error: ' + (data.error || 'unknown'), 'text-danger');
                        return;
                    }
                    setStatus('Embedding saved!', 'text-success');
                    // notify Livewire to refresh the table
                    Livewire.emit('embeddingSaved');
                    setTimeout(close, 600);
                } catch (e) {
                    setStatus('Upload error: ' + e, 'text-danger');
                }
            }

            return {
                openCapture,
                close,
                captureAndUpload
            };
        })();
    </script>
@endpush
