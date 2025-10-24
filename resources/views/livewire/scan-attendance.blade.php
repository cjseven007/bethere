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
            <div class="row g-3 att-wrap">
                {{-- LEFT (Camera) --}}
                <div class="col-12 col-lg-6" wire:ignore>
                    <div class="card h-100">
                        <div class="card-header py-2">
                            <div class="att-toolbar d-flex align-items-center gap-2 flex-wrap">
                                <button id="btn-rt-start" class="btn btn-primary btn-sm">
                                    <i class="bi bi-camera-video"></i> Start Camera
                                </button>
                                <button id="btn-rt-stop" class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="bi bi-stop-fill"></i> Stop
                                </button>
                                <span class="ms-auto small text-muted">Auto snapshot every 2s</span>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="att-cam-area position-relative">
                                <div id="rt-cam-placeholder"
                                    class="att-placeholder text-center d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="bi bi-person-bounding-box display-3 text-white"></i>
                                    <div class="mt-2 text-white">Camera is off. Tap <strong>Start Camera</strong> to
                                        begin.</div>
                                </div>

                                <video id="rt-cam" class="att-video d-none" autoplay playsinline muted></video>
                                <canvas id="rt-canvas" class="d-none"></canvas>

                                <div id="rt-loading" class="att-loading d-none">
                                    <div class="att-loading-inner">
                                        <div class="spinner-border" role="status" aria-hidden="true"></div>
                                        <div class="mt-2 fw-semibold">Identifying…</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div id="rt-match-banner" class="d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle text-muted"></i>
                                <span class="text-muted">Ready. Start camera to begin realtime identification.</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT (Logs) --}}
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header py-2"><strong>Activity</strong></div>
                        <div class="card-body p-0">
                            <div id="log-rt" class="att-log p-3"></div>
                        </div>
                    </div>
                </div>
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
                height: 100%;
                max-height: calc(100vh - 280px);
                /* adjust based on header/footer */
                overflow-y: auto;
                overflow-x: hidden;
                font-size: 0.9rem;
                line-height: 1.4;
                background: #fff;
                scroll-behavior: smooth;
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
            /* ================== SHARED HELPERS ================== */
            const IDENTIFY_URL = $('meta[name="identify-route"]').attr('content');
            const CSRF = $('meta[name="csrf-token"]').attr('content');

            function formatScore(s) {
                return s != null ? Number(s).toFixed(3) : '—';
            }

            function logMsg($el, msg, cls = '') {
                const $d = $('<div/>').text(`[${new Date().toLocaleTimeString()}] ${msg}`);
                if (cls) $d.addClass(cls);
                $el.prepend($d);
            }

            function bannerReady($banner, text) {
                $banner.html(`<i class="bi bi-info-circle text-muted"></i><span class="text-muted">${text}</span>`);
            }

            function bannerMatch($banner, name, score) {
                $banner.html(`
      <i class="bi bi-check-circle-fill text-success" style="font-size:1.4rem;"></i>
      <div>
        <div class="att-match-name">${name}</div>
        <div class="text-success">Matched (score ${formatScore(score)})</div>
      </div>
    `);
            }

            function bannerNoMatch($banner, score) {
                $banner.html(`
      <i class="bi bi-x-circle-fill text-danger" style="font-size:1.4rem;"></i>
      <div>No match (score ${formatScore(score)})</div>
    `);
            }

            /* ================== PHOTO MODE ================== */
            const $logP = $('#log-photo');
            const $bannerP = $('#match-banner');
            const $loadP = $('#att-loading');
            const videoP = document.getElementById('cam-photo');
            const canvasP = document.getElementById('canvas-photo');
            const ctxP = canvasP.getContext('2d');

            function resizeCanvasToBox($area, canvas) {
                const el = $area[0];
                if (!el) return;
                const r = el.getBoundingClientRect();
                canvas.width = Math.round(r.width);
                canvas.height = Math.round(r.height);
            }

            function setLoadingP(on) {
                $loadP.toggleClass('d-none', !on);
                $('#btn-photo-capture').prop('disabled', on || !$('#btn-photo-start').prop('disabled'));
            }

            function showVideoP(on) {
                $('#cam-photo').toggleClass('d-none', !on);
                $('#cam-placeholder').toggleClass('d-none', on);
            }

            async function startPhoto() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
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
                    });
                    videoP.srcObject = stream;
                    resizeCanvasToBox($('.att-cam-area').first(), canvasP);
                    window.addEventListener('resize', () => resizeCanvasToBox($('.att-cam-area').first(), canvasP));
                    await new Promise(res => videoP.onloadedmetadata = () => videoP.play().then(res).catch(() =>
                        res()));
                    $('#btn-photo-start').prop('disabled', true);
                    $('#btn-photo-capture').prop('disabled', false);
                    $('#btn-photo-stop').prop('disabled', false);
                    showVideoP(true);
                    bannerReady($bannerP, 'Ready. Capture a photo to identify.');
                    logMsg($logP, 'Camera started (Photo)', 'text-primary');
                } catch (e) {
                    logMsg($logP, `Camera error: ${e?.name||e}`, 'text-danger');
                }
            }

            function stopPhoto() {
                const s = videoP.srcObject;
                if (s) {
                    s.getTracks().forEach(t => t.stop());
                    videoP.srcObject = null;
                }
                $('#btn-photo-start').prop('disabled', false);
                $('#btn-photo-capture').prop('disabled', true);
                $('#btn-photo-stop').prop('disabled', true);
                setLoadingP(false);
                showVideoP(false);
                bannerReady($bannerP, 'Ready. Capture a photo to identify.');
                logMsg($logP, 'Camera stopped (Photo)', 'text-secondary');
            }
            async function captureIdentifyOnce() {
                if (!videoP.srcObject) return logMsg($logP, 'Camera is not started', 'text-warning');
                ctxP.drawImage(videoP, 0, 0, canvasP.width, canvasP.height);
                const blob = await new Promise(res => canvasP.toBlob(res, 'image/jpeg', 0.9));
                const fd = new FormData();
                fd.append('_token', CSRF);
                fd.append('frame', blob, 'frame.jpg');
                setLoadingP(true);
                logMsg($logP, 'Uploading snapshot…');
                $.ajax({
                    url: IDENTIFY_URL,
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        setLoadingP(false);
                        if (!data || !data.ok) {
                            logMsg($logP, `Error: ${data?.error||'Unknown'}`, 'text-danger');
                            return;
                        }
                        if (data.user_id) {
                            bannerMatch($bannerP, data.name || ('User#' + data.user_id), data.score);
                            logMsg($logP,
                                `MATCH: ${data.name||('User#'+data.user_id)} (score ${formatScore(data.score)})`,
                                'text-success fw-semibold');
                        } else {
                            bannerNoMatch($bannerP, data.score);
                            logMsg($logP, `No match (score ${formatScore(data.score)})`, 'text-muted');
                        }
                    },
                    error: function(xhr) {
                        setLoadingP(false);
                        let msg = 'Request failed';
                        try {
                            msg = xhr.responseJSON?.message || xhr.responseText || msg;
                        } catch {}
                        logMsg($logP, `Fetch error: ${msg}`, 'text-danger');
                    }
                });
            }

            $(document).on('click', '#btn-photo-start', startPhoto);
            $(document).on('click', '#btn-photo-stop', stopPhoto);
            $(document).on('click', '#btn-photo-capture', captureIdentifyOnce);

            /* Stop Photo cam when switching away */
            $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function(e) {
                if ($(e.target).attr('data-bs-target') !== '#tab-photo') stopPhoto();
            });

            /* ================== REALTIME MODE ================== */
            const $logR = $('#log-rt');
            const $bannerR = $('#rt-match-banner');
            const $loadR = $('#rt-loading');
            const videoR = document.getElementById('rt-cam');
            const canvasR = document.getElementById('rt-canvas');
            const ctxR = canvasR.getContext('2d');

            let rtTimer = null;
            let rtBusy = false;

            function setLoadingR(on) {
                $loadR.toggleClass('d-none', !on);
            }

            function showVideoR(on) {
                $('#rt-cam').toggleClass('d-none', !on);
                $('#rt-cam-placeholder').toggleClass('d-none', on);
            }

            async function startRealtime() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
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
                    });
                    videoR.srcObject = stream;
                    resizeCanvasToBox($('#tab-realtime .att-cam-area'), canvasR);
                    window.addEventListener('resize', () => resizeCanvasToBox($('#tab-realtime .att-cam-area'),
                        canvasR));
                    await new Promise(res => videoR.onloadedmetadata = () => videoR.play().then(res).catch(() =>
                        res()));
                    $('#btn-rt-start').prop('disabled', true);
                    $('#btn-rt-stop').prop('disabled', false);
                    showVideoR(true);
                    bannerReady($bannerR, 'Running realtime. Taking a snapshot every 2s…');
                    logMsg($logR, 'Camera started (Realtime)', 'text-primary');

                    // Start 2s interval
                    rtTimer = setInterval(tickRealtime, 2000);
                } catch (e) {
                    logMsg($logR, `Camera error: ${e?.name||e}`, 'text-danger');
                }
            }

            function stopRealtime() {
                if (rtTimer) {
                    clearInterval(rtTimer);
                    rtTimer = null;
                }
                const s = videoR.srcObject;
                if (s) {
                    s.getTracks().forEach(t => t.stop());
                    videoR.srcObject = null;
                }
                $('#btn-rt-start').prop('disabled', false);
                $('#btn-rt-stop').prop('disabled', true);
                setLoadingR(false);
                showVideoR(false);
                bannerReady($bannerR, 'Ready. Start camera to begin realtime identification.');
                rtBusy = false;
                logMsg($logR, 'Camera stopped (Realtime)', 'text-secondary');
            }

            async function tickRealtime() {
                if (rtBusy) return; // skip if previous request still running
                if (!videoR.srcObject) return; // camera not running
                rtBusy = true;
                setLoadingR(true);

                try {
                    ctxR.drawImage(videoR, 0, 0, canvasR.width, canvasR.height);
                    const blob = await new Promise(res => canvasR.toBlob(res, 'image/jpeg', 0.9));
                    const fd = new FormData();
                    fd.append('_token', CSRF);
                    fd.append('frame', blob, 'frame.jpg');

                    $.ajax({
                        url: IDENTIFY_URL,
                        method: 'POST',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (!data || !data.ok) {
                                logMsg($logR, `Error: ${data?.error||'Unknown'}`, 'text-danger');
                                return;
                            }
                            if (data.user_id) {
                                bannerMatch($bannerR, data.name || ('User#' + data.user_id), data
                                    .score);
                                logMsg($logR,
                                    `MATCH: ${data.name||('User#'+data.user_id)} (score ${formatScore(data.score)})`,
                                    'text-success fw-semibold');
                            } else {
                                bannerNoMatch($bannerR, data.score);
                                logMsg($logR, `No match (score ${formatScore(data.score)})`,
                                    'text-muted');
                            }
                        },
                        error: function(xhr) {
                            let msg = 'Request failed';
                            try {
                                msg = xhr.responseJSON?.message || xhr.responseText || msg;
                            } catch {}
                            logMsg($logR, `Fetch error: ${msg}`, 'text-danger');
                        },
                        complete: function() {
                            rtBusy = false;
                            setLoadingR(false);
                        }
                    });
                } catch (e) {
                    logMsg($logR, `Realtime tick error: ${e}`, 'text-danger');
                    rtBusy = false;
                    setLoadingR(false);
                }
            }

            $(document).on('click', '#btn-rt-start', startRealtime);
            $(document).on('click', '#btn-rt-stop', stopRealtime);

            /* Stop Realtime cam when switching away */
            $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function(e) {
                if ($(e.target).attr('data-bs-target') !== '#tab-realtime') stopRealtime();
            });

            /* Cleanup on page unload */
            $(window).on('beforeunload', function() {
                stopPhoto();
                stopRealtime();
            });

        })(jQuery);
    </script>
@endpush
