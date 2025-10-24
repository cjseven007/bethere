@props([
    'id' => 'panel',
    'showCapture' => true, // photo mode
    'threshold' => 0.4,
])

<div id="{{ $id }}-wrap" class="row g-3 att-wrap"><!-- GRID: camera left, logs right -->

    {{-- LEFT: Camera --}}
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header py-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button id="{{ $id }}-start" class="btn btn-primary btn-sm">
                        <i class="bi bi-camera-video"></i> Start Camera
                    </button>

                    @if ($showCapture)
                        <button id="{{ $id }}-capture" class="btn btn-success btn-sm" disabled>
                            <i class="bi bi-camera"></i> Capture & Identify
                        </button>
                    @endif

                    <button id="{{ $id }}-stop" class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="bi bi-stop-fill"></i> Stop
                    </button>

                    <span class="ms-auto small text-muted">
                        Threshold: <span class="badge bg-secondary">{{ number_format($threshold, 2) }}</span>
                    </span>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="att-cam-area position-relative" wire:ignore>
                    <div id="{{ $id }}-placeholder"
                        class="att-placeholder text-center d-flex flex-column align-items-center justify-content-center h-100">
                        <i class="bi bi-person-bounding-box display-3 text-muted"></i>
                        <div class="mt-2 text-muted">
                            Camera is off.
                            {{ $showCapture ? 'Start, then Capture to identify.' : 'Start to stream every 2s.' }}
                        </div>
                    </div>

                    <video id="{{ $id }}-video" class="att-video d-none" autoplay playsinline muted></video>
                    <canvas id="{{ $id }}-canvas" class="d-none"></canvas>

                    <div id="{{ $id }}-loading" class="att-loading d-none">
                        <div class="att-loading-inner">
                            <div class="spinner-border" role="status" aria-hidden="true"></div>
                            <div class="mt-2 fw-semibold">Identifying…</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div id="{{ $id }}-banner" class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle text-muted"></i>
                    <span
                        class="text-muted">{{ $showCapture ? 'Ready. Capture a photo to identify.' : 'Ready. Streaming every 2s when started.' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Logs --}}
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header py-2"><strong>Activity</strong></div>
            <div class="card-body p-0">
                <div id="{{ $id }}-log" class="att-log p-3"></div>
            </div>
        </div>
    </div>

    {{-- Local styles for this panel --}}
    <style>
        /* Desktop: full-height feel; Mobile: stack naturally */
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
            .att-log {
                max-height: 40vh;
            }

            .att-cam-area {
                aspect-ratio: 4 / 3;
            }
        }

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

        .att-match-name {
            font-size: 1.5rem;
            font-weight: 800;
        }

        @media (max-width: 576px) {
            .att-match-name {
                font-size: 1.25rem;
            }
        }
    </style>
</div>
