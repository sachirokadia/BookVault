@extends('layouts.app')
@section('title','Edit Member')
@section('page-title','Edit Member')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="row justify-content-center">
<div class="col-xl-6">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil-fill"></i> Editing: <em>{{ $user->name }}</em></div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('members.update',$user) }}" id="memForm">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email',$user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="member"    {{ old('role',$user->role)==='member'    ?'selected':'' }}>Member</option>
                        <option value="librarian" {{ old('role',$user->role)==='librarian' ?'selected':'' }}>Librarian</option>
                        <option value="admin"     {{ old('role',$user->role)==='admin'     ?'selected':'' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Member ID</label>
                    <input type="text" name="member_id" class="form-control" value="{{ old('member_id',$user->member_id) }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address',$user->address) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                               style="accent-color:var(--green-mid)"
                               {{ old('is_active',$user->is_active)?'checked':'' }}>
                        <label class="form-check-label" for="isActive" style="font-size:.85rem">Active Account</label>
                    </div>
                </div>
            </div>
            <hr style="border-color:var(--border);margin:20px 0">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-gold btn-sm px-4" id="submitBtn">
                    <i class="bi bi-save me-1"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>$('#memForm').on('submit',function(){$('#submitBtn').prop('disabled',true).html('<span class="spinner-border spinner-border-sm me-1"></span>Updating…')});</script>
@endpush
