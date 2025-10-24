@extends('layouts.app')
@section('title', 'Users')
@section('page_title', 'Employees - ' . ($org->name ?? ''))

@section('page_actions')
    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus"></i> Add Admin/User
    </a>
@endsection

@section('content')
    @livewire('employees-table')
@endsection
