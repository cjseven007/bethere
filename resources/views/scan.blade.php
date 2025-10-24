@extends('layouts.app')
@section('title', 'Scan Attendance')
@section('page_title', 'Scan Attendance')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-auto">
                    <video id="cam" width="320" height="240" autoplay playsinline class="border rounded"></video>
                    <canvas id="canvas" width="320" height="240" class="d-none"></canvas>
                </div>
                <div class="col">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <button id="btn-start" class="btn btn-primary btn-sm"><i class="bi bi-play-fill"></i> Start</button>
                        <button id="btn-stop" class="btn btn-outline-secondary btn-sm" disabled><i
                                class="bi bi-stop-fill"></i> Stop</button>
                        <span class="text-muted small">Sends a snapshot every 2s</span>
                    </div>
                    <div id="log" class="small" style="max-height:260px; overflow:auto;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const CSRF = '{{ csrf_token() }}';
            const cam = document.getElementById('cam');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            const log = document.getElementById('log');
            const btnStart = document.getElementById('btn-start');
            const btnStop = document.getElementById('btn-stop');
            let timer = null;

            function append(msg, cls = '') {
                const div = document.createElement('div');
                if (cls) div.className = cls;
                div.textContent = `[${new Date().toLocaleTimeString()}] ${msg}`;
                log.prepend(div);
            }

            async function openCam() {
                cam.srcObject = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: 320,
                        height: 240
                    }
                });
            }

            async function sendFrame() {
                ctx.drawImage(cam, 0, 0, canvas.width, canvas.height);
                const blob = await new Promise(res => canvas.toBlob(res, 'image/jpeg', 0.9));
                const fd = new FormData();
                fd.append('_token', CSRF);
                fd.append('frame', blob, 'frame.jpg');

                try {
                    const r = await fetch('{{ route('attendance.identify') }}', {
                        method: 'POST',
                        body: fd
                    });
                    const data = await r.json();
                    if (!data.ok) {
                        append(`Error: ${data.error || 'unknown'}`, 'text-danger');
                        return;
                    }
                    if (data.user_id) {
                        append(`MATCH: ${data.name} (score ${data.score.toFixed(3)})`, 'text-success fw-semibold');
                    } else {
                        append(`No match (score ${data.score.toFixed(3)})`, 'text-muted');
                    }
                } catch (e) {
                    append(`Fetch error: ${e}`, 'text-danger');
                }
            }

            btnStart.addEventListener('click', async () => {
                btnStart.disabled = true;
                btnStop.disabled = false;
                try {
                    await openCam();
                } catch (e) {
                    append(`Camera error: ${e}`, 'text-danger');
                    return;
                }
                append('Camera started', 'text-primary');
                timer = setInterval(sendFrame, 2000);
            });

            btnStop.addEventListener('click', () => {
                btnStart.disabled = false;
                btnStop.disabled = true;
                if (timer) clearInterval(timer), timer = null;
                append('Stopped', 'text-secondary');
                const s = cam.srcObject;
                if (s) {
                    s.getTracks().forEach(t => t.stop());
                    cam.srcObject = null;
                }
            });
        })();
    </script>
@endsection
