@extends('layouts.app')
@section('title', 'Settings')
@section('page_title', 'Settings')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('settings.update') }}" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Cosine Threshold</label>
                    <input type="number" step="0.01" min="0" max="1" name="threshold" class="form-control"
                        value="{{ old('threshold', $settings->threshold ?? 0.4) }}">
                    <div class="form-text">Higher = stricter match (fewer false accepts, more false rejects).</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Snapshot Interval (seconds)</label>
                    <input type="number" min="1" name="interval" class="form-control"
                        value="{{ old('interval', $settings->interval ?? 2) }}">
                </div>

                <div class="col-12">
                    <button class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
