@extends('layouts.app')
@section('title', 'Users')
@section('page_title', 'Employees - ' . ($org->name ?? ''))

@section('page_actions')
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
        <i class="bi bi-person-plus"></i> Add Employee
    </button>
@endsection

@section('content')
    @livewire('employees-table')
    {{-- Add Employee Modal --}}
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="addEmployeeForm" class="modal-content" onsubmit="return AddEmployee.submit(event)">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="AddEmployee.reset()"></button>
                </div>
                <div class="modal-body">
                    <div id="aeAlert" class="alert d-none" role="alert"></div>

                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input name="name" type="text" class="form-control" required maxlength="255"
                            placeholder="Jane Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input name="email" type="email" class="form-control" required maxlength="255"
                            placeholder="jane@example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal"
                        onclick="AddEmployee.reset()">Cancel</button>
                    <button id="aeSubmitBtn" class="btn btn-primary" type="submit">
                        <i class="bi bi-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const AddEmployee = (function() {
            const form = document.getElementById('addEmployeeForm');
            const modalEl = document.getElementById('addEmployeeModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            const alert = document.getElementById('aeAlert');
            const submit = document.getElementById('aeSubmitBtn');

            function showAlert(type, msg) {
                alert.className = 'alert alert-' + type;
                alert.textContent = msg;
                alert.classList.remove('d-none');
            }

            function clearAlert() {
                alert.className = 'alert d-none';
                alert.textContent = '';
            }

            function disable(on) {
                submit.disabled = !!on;
                submit.innerHTML = on ?
                    '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...' :
                    '<i class="bi bi-save me-1"></i> Save';
            }

            function reset() {
                clearAlert();
                form.reset();
                disable(false);
            }

            async function submitHandler(e) {
                e.preventDefault();
                clearAlert();
                disable(true);

                const fd = new FormData(form);
                try {
                    const res = await fetch(@json(route('employees.store')), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': @json(csrf_token())
                        },
                        body: fd
                    });

                    const data = await res.json().catch(() => ({}));

                    if (!res.ok || !data.ok) {
                        let msg = 'Failed to create employee.';
                        if (res.status === 422 && data?.errors) {
                            // Build validation message
                            msg = Object.values(data.errors).flat().join(' ');
                        } else if (data?.error) {
                            msg = data.error;
                        }
                        showAlert('danger', msg);
                        disable(false);
                        return;
                    }

                    // Success
                    // Livewire v3: dispatch; v2: emit — try both
                    if (window.Livewire?.dispatch) {
                        Livewire.dispatch('employeeCreated');
                    } else if (window.Livewire?.emit) {
                        Livewire.emit('employeeCreated');
                    }

                    reset();
                    modal.hide();
                } catch (err) {
                    showAlert('danger', 'Network error: ' + err);
                    disable(false);
                }
            }
            modalEl.addEventListener('hidden.bs.modal', () => {
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            });


            return {
                submit: submitHandler,
                reset
            };
        })();
    </script>
@endpush
