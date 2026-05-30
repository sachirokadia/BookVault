@extends('layouts.app')
@section('title',$user->name)
@section('page-title','Member Profile')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card" style="border-top:5px solid var(--green-mid)">
            <div class="card-body p-4 text-center">
                <div style="width:72px;height:72px;background:linear-gradient(135deg,var(--green-mid),var(--green-deep));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:#fff;font-family:'Crimson Pro',serif;font-size:1.8rem;font-weight:700">
                    {{ strtoupper(substr($user->name,0,2)) }}
                </div>
                <h5 style="font-family:'Crimson Pro',serif;font-size:1.3rem;margin-bottom:2px">{{ $user->name }}</h5>
                <p class="text-muted mb-2" style="font-size:.82rem">{{ $user->email }}</p>
                @php $rc=['admin'=>'#dc2626','librarian'=>'#d97706','member'=>'#166534'][$user->role]??'#555'; @endphp
                <span style="background:{{ $rc }}18;color:{{ $rc }};border:1px solid {{ $rc }}44;border-radius:6px;padding:3px 12px;font-size:.74rem;font-weight:800;text-transform:uppercase">{{ $user->role }}</span>
                &nbsp;
                <span class="status-pill {{ $user->is_active?'pill-active':'pill-inactive' }}" style="font-size:.74rem">{{ $user->is_active?'Active':'Inactive' }}</span>

                <div class="text-start mt-4" style="font-size:.82rem">
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px dashed var(--border)">
                        <span class="text-muted">Member ID</span><code>{{ $user->member_id??'—' }}</code>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px dashed var(--border)">
                        <span class="text-muted">Phone</span><span>{{ $user->phone??'—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Joined</span><span>{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                @php
                    $total=$user->issues->count();
                    $active=$user->issues->whereIn('status',['issued','overdue'])->count();
                    $returned=$user->issues->where('status','returned')->count();
                    $overdue=$user->issues->where('status','overdue')->count();
                @endphp
                <div class="row g-2 mt-3">
                    <div class="col-6"><div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:10px;text-align:center">
                        <div style="font-family:'Crimson Pro',serif;font-size:1.4rem;font-weight:700;color:var(--green-deep)">{{ $total }}</div>
                        <div style="font-size:.68rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Total</div>
                    </div></div>
                    <div class="col-6"><div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:10px;text-align:center">
                        <div style="font-family:'Crimson Pro',serif;font-size:1.4rem;font-weight:700;color:#92400e">{{ $active }}</div>
                        <div style="font-size:.68rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.5px">On Loan</div>
                    </div></div>
                    <div class="col-6"><div style="background:#dcfce7;border:1px solid #6ee7b7;border-radius:8px;padding:10px;text-align:center">
                        <div style="font-family:'Crimson Pro',serif;font-size:1.4rem;font-weight:700;color:#166534">{{ $returned }}</div>
                        <div style="font-size:.68rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Returned</div>
                    </div></div>
                    <div class="col-6"><div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:10px;text-align:center">
                        <div style="font-family:'Crimson Pro',serif;font-size:1.4rem;font-weight:700;color:#dc2626">{{ $overdue }}</div>
                        <div style="font-size:.68rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Overdue</div>
                    </div></div>
                </div>

                <hr style="border-color:var(--border)">
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('members.edit',$user) }}" class="btn btn-gold btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
                    <a href="{{ route('issues.create') }}?user_id={{ $user->id }}" class="btn btn-emerald btn-sm"><i class="bi bi-send me-1"></i>Issue Book</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-journal-richtext"></i>Lending History <span class="badge-count">{{ $user->issues->count() }}</span></div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Book</th><th>Issued</th><th>Due</th><th>Returned</th><th>Status</th><th>Fine</th></tr></thead>
                    <tbody>
                    @forelse($user->issues->sortByDesc('created_at') as $issue)
                        <tr>
                            <td>
                                <div style="font-family:'Crimson Pro',serif;font-weight:700;font-size:.9rem">{{ Str::limit($issue->book->title,26) }}</div>
                                <div style="font-size:.73rem;color:#888">{{ $issue->book->isbn }}</div>
                            </td>
                            <td style="font-size:.82rem">{{ $issue->issue_date->format('d M Y') }}</td>
                            <td style="font-size:.82rem">{{ $issue->due_date->format('d M Y') }}</td>
                            <td style="font-size:.82rem">{{ $issue->return_date ? $issue->return_date->format('d M Y'):'—' }}</td>
                            <td><span class="status-pill pill-{{ $issue->status }}">{{ ucfirst($issue->status) }}</span></td>
                            <td style="font-size:.82rem">
                                @if($issue->fine_amount>0)<span style="color:#dc2626;font-weight:700">₹{{ number_format($issue->fine_amount,2) }}</span>
                                @else — @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted" style="font-family:'Crimson Pro',serif">No lending history</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
