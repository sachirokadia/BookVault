{{-- issues/show.blade.php --}}
@extends('layouts.app')
@section('title','Issue Record')
@section('page-title','Issue Record')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('issues.index') }}">Transactions</a></li>
    <li class="breadcrumb-item active">#{{ $issue->id }}</li>
@endsection
@section('content')
<div class="row g-3 justify-content-center">
<div class="col-lg-6">
<div class="card" style="border-top:5px solid var(--green-mid)">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt"></i> Record #{{ $issue->id }}</span>
        <span class="status-pill pill-{{ $issue->status }}">{{ ucfirst($issue->status) }}</span>
    </div>
    <div class="card-body p-4">

        {{-- Book --}}
        <div style="background:var(--cream);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:14px;display:flex;gap:14px;align-items:center">
            <div style="width:44px;height:56px;background:linear-gradient(160deg,var(--green-deep),var(--green-mid));border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:1.2rem;flex-shrink:0">
                <i class="bi bi-book-fill"></i>
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-family:'Crimson Pro',serif;font-weight:700;font-size:1rem">{{ $issue->book->title }}</div>
                <div style="font-size:.78rem;color:#888">{{ $issue->book->author }} &bull; <code style="font-size:.75rem">{{ $issue->book->isbn }}</code></div>
            </div>
            <a href="{{ route('books.show',$issue->book) }}" class="btn-icon btn-icon-view"><i class="bi bi-arrow-right"></i></a>
        </div>

        {{-- Member --}}
        <div style="background:var(--cream);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:14px;display:flex;gap:14px;align-items:center">
            <div style="width:44px;height:44px;background:linear-gradient(135deg,#4a1a7a,#7c3aed);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.95rem;flex-shrink:0">
                {{ strtoupper(substr($issue->user->name,0,2)) }}
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-weight:700;font-size:.9rem">{{ $issue->user->name }}</div>
                <div style="font-size:.76rem;color:#888">{{ $issue->user->email }} &bull; {{ $issue->user->member_id }}</div>
            </div>
            <a href="{{ route('members.show',$issue->user) }}" class="btn-icon btn-icon-view"><i class="bi bi-arrow-right"></i></a>
        </div>

        {{-- Date row --}}
        <div class="row g-2 mb-3">
            <div class="col-4">
                <div style="background:var(--parchment);border:1px solid var(--border);border-radius:8px;padding:10px;text-align:center">
                    <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:#aaa;font-weight:800">Issued</div>
                    <div style="font-family:'Crimson Pro',serif;font-size:1.1rem;font-weight:700">{{ $issue->issue_date->format('d M') }}</div>
                    <div style="font-size:.72rem;color:#aaa">{{ $issue->issue_date->format('Y') }}</div>
                </div>
            </div>
            <div class="col-4">
                <div style="background:{{ $issue->status==='overdue'?'#fee2e2':'var(--parchment)' }};border:1px solid {{ $issue->status==='overdue'?'#fca5a5':'var(--border)' }};border-radius:8px;padding:10px;text-align:center">
                    <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:#aaa;font-weight:800">Due</div>
                    <div style="font-family:'Crimson Pro',serif;font-size:1.1rem;font-weight:700;color:{{ $issue->status==='overdue'?'#dc2626':'inherit' }}">{{ $issue->due_date->format('d M') }}</div>
                    <div style="font-size:.72rem;color:#aaa">{{ $issue->due_date->format('Y') }}</div>
                </div>
            </div>
            <div class="col-4">
                <div style="background:{{ $issue->return_date?'#dcfce7':'var(--parchment)' }};border:1px solid {{ $issue->return_date?'#6ee7b7':'var(--border)' }};border-radius:8px;padding:10px;text-align:center">
                    <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:#aaa;font-weight:800">Returned</div>
                    <div style="font-family:'Crimson Pro',serif;font-size:1.1rem;font-weight:700;color:{{ $issue->return_date?'#166534':'#ccc' }}">{{ $issue->return_date ? $issue->return_date->format('d M'):'—' }}</div>
                    <div style="font-size:.72rem;color:#aaa">{{ $issue->return_date ? $issue->return_date->format('Y'):'Pending' }}</div>
                </div>
            </div>
        </div>

        @if($issue->status==='overdue')
            <div style="background:#fee2e2;border-radius:8px;padding:10px 14px;font-size:.83rem;color:#991b1b;margin-bottom:12px;display:flex;gap:8px;align-items:center">
                <i class="bi bi-hourglass-split"></i>
                <span><strong>{{ $issue->days_overdue }} day(s) overdue.</strong> Estimated fine: <strong>₹{{ number_format($issue->calculateFine(),2) }}</strong></span>
            </div>
        @endif
        @if($issue->fine_amount > 0)
            <div style="background:#fef3c7;border-radius:8px;padding:10px 14px;font-size:.83rem;color:#92400e;margin-bottom:12px;display:flex;gap:8px;align-items:center">
                <i class="bi bi-currency-rupee"></i>
                <span>Fine collected: <strong>₹{{ number_format($issue->fine_amount,2) }}</strong></span>
            </div>
        @endif
        @if($issue->notes)
            <div style="background:var(--cream);border-radius:8px;padding:10px 14px;font-size:.82rem;color:#555;margin-bottom:12px;font-style:italic">
                <i class="bi bi-chat-quote me-1"></i>{{ $issue->notes }}
            </div>
        @endif

        <hr style="border-color:var(--border)">
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('issues.index') }}" class="btn btn-sm btn-outline-secondary">← Back</a>
            @if($issue->status!=='returned')
                <button id="retBtn" class="btn btn-sm px-4"
                        style="background:#059669;color:#fff;border:none;border-radius:8px;font-weight:700">
                    <i class="bi bi-check2 me-1"></i>Mark Returned
                </button>
            @endif
        </div>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="retModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px">
        <div class="modal-content" style="border:none;border-radius:14px;border-top:4px solid #059669">
            <div class="modal-body p-4 text-center">
                <div style="width:52px;height:52px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.3rem;color:#059669"><i class="bi bi-check2-circle"></i></div>
                <h6 style="font-family:'Crimson Pro',serif;font-size:1.1rem;margin-bottom:6px">Confirm Return</h6>
                <p style="font-size:.83rem;color:#777;margin-bottom:4px">{{ $issue->book->title }}</p>
                @if($issue->status==='overdue')
                    <div style="background:#fef3c7;border-radius:8px;padding:8px 12px;font-size:.81rem;color:#92400e;margin:10px 0">
                        Fine: <strong>₹{{ number_format($issue->calculateFine(),2) }}</strong>
                    </div>
                @endif
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:8px;padding:8px 18px">Cancel</button>
                    <form method="POST" action="{{ route('issues.return',$issue) }}">
                        @csrf @method('PATCH')
                        <button type="submit" style="background:#059669;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-weight:700;font-size:.85rem">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>$('#retBtn').on('click',function(){$('#retModal').modal('show');});</script>
@endpush
