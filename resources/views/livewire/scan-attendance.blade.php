<div id="scan-attendance"><!-- SINGLE ROOT -->

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="scanTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="photo-tab" data-bs-toggle="tab" data-bs-target="#tab-photo" type="button"
                role="tab" aria-controls="tab-photo" aria-selected="true">
                Photo Attendance
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="realtime-tab" data-bs-toggle="tab" data-bs-target="#tab-realtime"
                type="button" role="tab" aria-controls="tab-realtime" aria-selected="false">
                Realtime Attendance
            </button>
        </li>
    </ul>

    <div class="tab-content" id="scanTabsContent">
        {{-- PHOTO ATTENDANCE --}}
        <div class="tab-pane fade show active" id="tab-photo" role="tabpanel" aria-labelledby="photo-tab">

            {{-- Use grid: stack on mobile, side-by-side on lg+ --}}
            <div class="row g-3 att-wrap">
                {{-- LEFT (Camera) --}}
                <div class="col-12 col-lg-6" wire:ignore>
                    <div class="card h-100">
                        <div class="card-header py-2">
                            <div class="att-toolbar d-flex align-items-center gap-2 flex-wrap">
                                <button id="btn-photo-start" class="btn btn-primary btn-sm">
                                    <i class="bi bi-camera-video"></i> Start Camera
                                </button>
                                <button id="btn-photo-capture" class="btn btn-success btn-sm" disabled>
                                    <i class="bi bi-camera"></i> Capture & Identify
                                </button>
                                <button id="btn-photo-stop" class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="bi bi-stop-fill"></i> Stop
                                </button>
                                <span class="ms-auto small text-muted">Threshold:
                                    <span class="badge bg-secondary">{{ number_format($threshold, 2) }}</span>
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            {{-- Camera area: full width; fixed aspect ratio on mobile; fills height on desktop --}}
                            <div class="att-cam-area position-relative">

                                {{-- Placeholder when camera off --}}
                                <div id="cam-placeholder"
                                    class="att-placeholder text-center d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-person-bounding-box display-3 text-white"></i>
                                    <div class="mt-2 text-white">Camera is off. Tap <strong>Start Camera</strong> to
                                        begin.</div>
                                </div>

                                {{-- Video / Canvas --}}
                                <video id="cam-photo" class="att-video d-none" autoplay playsinline muted></video>
                                <canvas id="canvas-photo" class="d-none"></canvas>

                                {{-- Loading overlay during inference --}}
                                <div id="att-loading" class="att-loading d-none">
                                    <div class="att-loading-inner">
                                        <div class="spinner-border" role="status" aria-hidden="true"></div>
                                        <div class="mt-2 fw-semibold">Identifying…</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Match banner --}}
                        <div class="card-footer">
                            <div id="match-banner" class="d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle text-muted"></i>
                                <span class="text-muted">Ready. Capture a photo to identify.</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT (Logs) --}}
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header py-2">
                            <strong>Activity</strong>
                        </div>
                        <div class="card-body p-0">
                            <div id="log-photo" class="att-log p-3"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- REALTIME ATTENDANCE (placeholder) --}}
        <div class="tab-pane fade" id="tab-realtime" role="tabpanel" aria-labelledby="realtime-tab">
            <div class="alert alert-info mb-0">
                Realtime will run local face detection first, then send selected frames to the API — great for cost
                control.
            </div>
        </div>
    </div>

    {{-- Hidden config for JS --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="identify-route" content="{{ route('attendance.identify') }}">
    <meta name="identify-threshold" content="{{ $threshold }}">

    <style>
        /* ====== Responsive layout sizing ====== */

        /* Desktop: give panels tall viewport height. Mobile: natural height + stacking */
        @media (min-width: 992px) {
            .att-wrap {
                min-height: calc(100vh - 220px);
            }

            .att-log {
                height: calc(100vh - 280px);
            }

            .att-cam-area {
                height: calc(100vh - 320px);
            }
        }

        @media (max-width: 991.98px) {
            .att-wrap {
                min-height: auto;
            }

            .att-log {
                max-height: 40vh;
            }

            /* scroll only logs on mobile */
            .att-cam-area {
                aspect-ratio: 4 / 3;
            }

            /* keeps video pleasant ratio on phones */
        }

        /* Camera video fills its box */
        .att-cam-area {
            background: #0b0b0b;
            border-radius: .5rem;
            overflow: hidden;
            position: relative;
            width: 100%;
            min-height: 240px;
        }

        .att-video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: #000;
        }

        .att-placeholder {
            color: #6c757d;
        }

        /* Loading overlay */
        .att-loading {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .att-loading-inner {
            color: #fff;
            text-align: center;
        }

        /* Match banner visuals */
        .att-match-name {
            font-size: 1.5rem;
            font-weight: 800;
        }

        @media (max-width: 576px) {

            /* Bigger buttons/tap targets on small screens */
            .att-toolbar .btn {
                font-size: 1rem;
            }

            .att-match-name {
                font-size: 1.25rem;
            }
        }
    </style>
</div>

@push('scripts')
    <script>
        (function($) {
            const $log = $('#log-photo');
            const video = document.getElementById('cam-photo');
            const canvas = document.getElementById('canvas-photo');
            const ctx = canvas.getContext('2d');

            const $btnStart = $('#btn-photo-start');
            const $btnCapture = $('#btn-photo-capture');
            const $btnStop = $('#btn-photo-stop');

            const $placeholder = $('#cam-placeholder');
            const $loading = $('#att-loading');
            const $matchBanner = $('#match-banner');

            let streamPhoto = null;
            let isLoading = false;

            function logMsg(msg, cls = '') {
                const $d = $('<div/>').text(`[${new Date().toLocaleTimeString()}] ${msg}`);
                if (cls) $d.addClass(cls);
                $log.prepend($d);
            }

            function setLoading(on) {
                isLoading = !!on;
                $loading.toggleClass('d-none', !isLoading);
                $btnCapture.prop('disabled', on || !$btnStart.prop('disabled'));
            }

            function showVideo(on) {
                $('#cam-photo').toggleClass('d-none', !on);
                $placeholder.toggleClass('d-none', on);
            }

            function resizeCanvasToBox() {
                const area = $('.att-cam-area')[0];
                if (!area) return;
                const rect = area.getBoundingClientRect();
                canvas.width = Math.round(rect.width);
                canvas.height = Math.round(rect.height);
            }

            function showMatch(name, score) {
                $matchBanner.html(`
      <i class="bi bi-check-circle-fill text-success" style="font-size:1.4rem;"></i>
      <div>
        <div class="att-match-name">${name}</div>
        <div class="text-success">Matched (score ${Number(score ?? 0).toFixed(3)})</div>
      </div>
    `);
            }

            function showNoMatch(score) {
                $matchBanner.html(`
      <i class="bi bi-x-circle-fill text-danger" style="font-size:1.4rem;"></i>
      <div>No match (score ${Number(score ?? 0).toFixed(3)})</div>
    `);
            }

            function showReady() {
                $matchBanner.html(`
      <i class="bi bi-info-circle text-muted"></i>
      <span class="text-muted">Ready. Capture a photo to identify.</span>
    `);
            }

            async function startCamPhoto() {
                try {
                    const constraints = {
                        video: {
                            width: {
                                ideal: 1280
                            },
                            height: {
                                ideal: 720
                            },
                            facingMode: 'user'
                        },
                        audio: false
                    };
                    streamPhoto = await navigator.mediaDevices.getUserMedia(constraints);
                    video.srcObject = streamPhoto;

                    resizeCanvasToBox();
                    window.addEventListener('resize', resizeCanvasToBox);

                    await new Promise(res => {
                        video.onloadedmetadata = () => {
                            video.play().then(res).catch(() => res());
                        }
                    });

                    $btnStart.prop('disabled', true);
                    $btnCapture.prop('disabled', false);
                    $btnStop.prop('disabled', false);
                    showVideo(true);
                    showReady();
                    logMsg('Camera started (Photo)', 'text-primary');
                } catch (e) {
                    const name = e?.name || 'Error';
                    let hint = '';
                    if (name === 'NotAllowedError') hint = ' (permission denied — check browser permissions)';
                    if (name === 'NotFoundError' || name === 'OverconstrainedError') hint =
                        ' (no camera or bad constraints)';
                    if (name === 'NotReadableError') hint = ' (camera used by another app)';
                    logMsg(`Camera error: ${name}${hint}`, 'text-danger');
                }
            }

            function stopCamPhoto() {
                if (streamPhoto) {
                    streamPhoto.getTracks().forEach(t => t.stop());
                    video.srcObject = null;
                    streamPhoto = null;
                }
                $btnStart.prop('disabled', false);
                $btnCapture.prop('disabled', true);
                $btnStop.prop('disabled', true);
                setLoading(false);
                showVideo(false);
                showReady();
                logMsg('Camera stopped (Photo)', 'text-secondary');
            }

            async function captureAndIdentify() {
                if (!streamPhoto) return logMsg('Camera is not started', 'text-warning');

                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const blob = await new Promise(res => canvas.toBlob(res, 'image/jpeg', 0.9));

                const fd = new FormData();
                fd.append('_token', $('meta[name="csrf-token"]').attr('content'));
                fd.append('frame', blob, 'frame.jpg');

                setLoading(true);
                logMsg('Uploading snapshot…');

                $.ajax({
                    url: $('meta[name="identify-route"]').attr('content'),
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        setLoading(false);
                        if (!data || !data.ok) return logMsg(`Error: ${data?.error || 'Unknown'}`,
                            'text-danger');

                        if (data.user_id) {
                            showMatch(data.name || ('User#' + data.user_id), data.score);
                            logMsg(`MATCH: ${data.name || ('User#'+data.user_id)} (score ${Number(data.score ?? 0).toFixed(3)})`,
                                'text-success fw-semibold');
                        } else {
                            showNoMatch(data.score);
                            const s = data.score != null ? Number(data.score).toFixed(3) : '—';
                            logMsg(`No match (score ${s})`, 'text-muted');
                        }
                    },
                    error: function(xhr) {
                        setLoading(false);
                        let msg = 'Request failed';
                        try {
                            msg = xhr.responseJSON?.message || xhr.responseText || msg;
                        } catch (e) {}
                        logMsg(`Fetch error: ${msg}`, 'text-danger');
                    }
                });
            }

            // Delegated bindings
            $(document).on('click', '#btn-photo-start', startCamPhoto);
            $(document).on('click', '#btn-photo-stop', stopCamPhoto);
            $(document).on('click', '#btn-photo-capture', captureAndIdentify);

            // Stop camera when leaving Photo tab
            $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function(e) {
                if ($(e.target).attr('data-bs-target') !== '#tab-photo') stopCamPhoto();
            });

            // Cleanup on unload
            $(window).on('beforeunload', stopCamPhoto);

        })(jQuery);
    </script>
@endpush
