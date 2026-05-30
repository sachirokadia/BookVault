{{-- members/create.blade.php --}}
@extends('layouts.app')
@section('title','Add Member')
@section('page-title','Register Member')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
    <li class="breadcrumb-item active">Register</li>
@endsection
@section('content')
<div class="row justify-content-center">
<div class="col-xl-6">
<div class="card">
    <div class="card-header"><i class="bi bi-person-plus-fill"></i> Register New Member</div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('members.store') }}" id="memForm">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="member"    {{ old('role','member')==='member'    ?'selected':'' }}>Member</option>
                        <option value="librarian" {{ old('role')==='librarian'?'selected':'' }}>Librarian</option>
                        <option value="admin"     {{ old('role')==='admin'    ?'selected':'' }}>Admin</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+91 XXXXX XXXXX">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Member ID</label>
                    <input type="text" name="member_id" class="form-control @error('member_id') is-invalid @enderror" value="{{ old('member_id') }}" placeholder="Auto-generated">
                    @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                </div>
            </div>
            <hr style="border-color:var(--border);margin:20px 0">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-emerald btn-sm px-4" id="submitBtn">
                    <i class="bi bi-person-check me-1"></i>Register Member
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>$('#memForm').on('submit',function(){$('#submitBtn').prop('disabled',true).html('<span class="spinner-border spinner-border-sm me-1"></span>Registering…')});</script>
@endpush
