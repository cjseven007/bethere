@extends('layouts.app')

@section('title', 'Scan Attendance')
@section('page_title', 'Scan Attendance')

@section('content')
    {{-- You can add page header, breadcrumb, etc. here --}}
    @livewire('scan-attendance')
@endsection
