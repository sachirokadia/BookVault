{{-- members/index.blade.php --}}
@extends('layouts.app')
@section('title','Members')
@section('page-title','Member Registry')
@section('breadcrumb')
    <li class="breadcrumb-item active">Members</li>
@endsection
@section('content')

<div class="card mb-3">
    <div class="card-body py-2 px-3">
        <form method="GET" action="{{ route('members.index') }}" id="filterForm">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Name, email or member ID…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select" id="roleFilter">
                        <option value="">All Roles</option>
                        <option value="member"    {{ request('role')==='member'    ?'selected':'' }}>Member</option>
                        <option value="librarian" {{ request('role')==='librarian' ?'selected':'' }}>Librarian</option>
                        <option value="admin"     {{ request('role')==='admin'     ?'selected':'' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-emerald btn-sm flex-fill">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    <a href="{{ route('members.create') }}" class="btn btn-gold btn-sm px-3"><i class="bi bi-person-plus me-1"></i>Add</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person-lines-fill"></i> All Members <span class="badge-count">{{ $users->total() }}</span></span>
        <a href="{{ route('members.create') }}" class="btn btn-gold btn-sm"><i class="bi bi-person-plus me-1"></i>Add Member</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr><th>#</th><th>Member</th><th>Member ID</th><th>Role</th><th>Active Issues</th><th>Status</th><th class="text-end" style="padding-right:20px">Actions</th></tr>
            </thead>
            <tbody>
            @forelse($users as $i => $user)
                <tr>
                    <td class="text-muted" style="font-size:.78rem">{{ $users->firstItem()+$i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;background:linear-gradient(135deg,var(--green-mid),var(--green-deep));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.78rem;flex-shrink:0">
                                {{ strtoupper(substr($user->name,0,2)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:.875rem">{{ $user->name }}</div>
                                <div style="font-size:.74rem;color:#888">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><code style="font-size:.78rem">{{ $user->member_id ?? '—' }}</code></td>
                    <td>
                        @php $rc=['admin'=>'#dc2626','librarian'=>'#d97706','member'=>'#166534'][$user->role]??'#555'; @endphp
                        <span style="background:{{ $rc }}18;color:{{ $rc }};border:1px solid {{ $rc }}44;border-radius:6px;padding:2px 9px;font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.5px">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td>
                        @if($user->active_issues_count > 0)
                            <span style="background:#fef3c7;color:#92400e;border-radius:6px;padding:2px 9px;font-size:.78rem;font-weight:700">
                                {{ $user->active_issues_count }}
                            </span>
                        @else
                            <span class="text-muted" style="font-size:.82rem">0</span>
                        @endif
                    </td>
                    <td><span class="status-pill {{ $user->is_active ? 'pill-active':'pill-inactive' }}">{{ $user->is_active ? 'Active':'Inactive' }}</span></td>
                    <td class="text-end" style="padding-right:20px">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('members.show',$user) }}" class="btn-icon btn-icon-view"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('members.edit',$user) }}" class="btn-icon btn-icon-edit"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('members.toggle-status',$user) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-icon" title="{{ $user->is_active?'Deactivate':'Activate' }}"
                                        style="{{ $user->is_active ? 'border-color:#fca5a5;color:#dc2626;background:#fff1f2':'border-color:#6ee7b7;color:#059669;background:#ecfdf5' }}border:1.5px solid;">
                                    <i class="bi bi-{{ $user->is_active?'toggle-on':'toggle-off' }}"></i>
                                </button>
                            </form>
                            @if($user->active_issues_count===0)
                                <button class="btn-icon btn-icon-del btn-del" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-5 text-muted" style="font-family:'Crimson Pro',serif;font-size:1.1rem">No members found</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center" style="background:var(--cream);border-top:1px solid var(--border)">
        <small class="text-muted">{{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</small>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<div class="modal fade" id="delModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
        <div class="modal-content" style="border:none;border-radius:14px;border-top:4px solid #dc2626">
            <div class="modal-body p-4 text-center">
                <div style="width:52px;height:52px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.3rem;color:#dc2626"><i class="bi bi-person-x-fill"></i></div>
                <h6 style="font-family:'Crimson Pro',serif;font-size:1.1rem;margin-bottom:6px">Remove Member?</h6>
                <p style="font-size:.84rem;color:#777;margin-bottom:20px"><strong id="delName"></strong> will be permanently deleted.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:8px;padding:8px 18px">Cancel</button>
                    <form id="delForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius:8px;padding:8px 18px;font-weight:700">Remove</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function(){
    $('.btn-del').on('click',function(){
        $('#delName').text($(this).data('name'));
        $('#delForm').attr('action','/members/'+$(this).data('id'));
        $('#delModal').modal('show');
    });
    $('#roleFilter').on('change',function(){$('#filterForm').submit();});
});
</script>
@endpush
