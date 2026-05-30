{{-- issues/index.blade.php --}}
@extends('layouts.app')
@section('title','Transactions')
@section('page-title','Lending Transactions')
@section('breadcrumb')
    <li class="breadcrumb-item active">Transactions</li>
@endsection
@section('content')

<div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
    <a href="{{ route('issues.index') }}"
       class="btn btn-sm {{ !request('status') ? 'btn-emerald':'btn-outline-secondary' }}">All</a>
    <a href="{{ route('issues.index',['status'=>'issued']) }}"
       class="btn btn-sm {{ request('status')==='issued' ? 'btn-emerald':'btn-outline-secondary' }}">
       <i class="bi bi-send me-1"></i>Issued</a>
    <a href="{{ route('issues.index',['status'=>'overdue']) }}"
       class="btn btn-sm {{ request('status')==='overdue' ? 'btn-danger':'btn-outline-danger' }}">
       <i class="bi bi-hourglass-split me-1"></i>Overdue</a>
    <a href="{{ route('issues.index',['status'=>'returned']) }}"
       class="btn btn-sm {{ request('status')==='returned' ? '' :'btn-outline-success' }}"
       style="{{ request('status')==='returned'?'background:#166534;color:#fff;border:none;border-radius:8px;font-weight:700':'' }}">
       <i class="bi bi-check2-circle me-1"></i>Returned</a>
    <div class="ms-auto">
        <a href="{{ route('issues.create') }}" class="btn btn-gold btn-sm px-3">
            <i class="bi bi-send me-1"></i>Issue Book
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2 px-3">
        <form method="GET" action="{{ route('issues.index') }}" style="max-width:420px">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control"
                       placeholder="Search book title or member…" value="{{ request('search') }}">
                <button type="submit" class="btn btn-emerald">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-card-list"></i>
            {{ request('status') ? ucfirst(request('status')).' Transactions':'All Transactions' }}
            <span class="badge-count">{{ $issues->total() }}</span>
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>#</th><th>Book</th><th>Member</th><th>Issued</th><th>Due Date</th><th>Return Date</th><th>Status</th><th>Fine</th><th class="text-end" style="padding-right:20px">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($issues as $i => $issue)
                <tr>
                    <td class="text-muted" style="font-size:.78rem">{{ $issues->firstItem()+$i }}</td>
                    <td>
                        <div style="font-family:'Crimson Pro',serif;font-weight:700;font-size:.95rem">{{ Str::limit($issue->book->title,24) }}</div>
                        <div style="font-size:.74rem;color:#888">{{ $issue->book->author }}</div>
                    </td>
                    <td>
                        <div style="font-weight:700;font-size:.84rem">{{ $issue->user->name }}</div>
                        <div style="font-size:.73rem;color:#888">{{ $issue->user->member_id }}</div>
                    </td>
                    <td style="font-size:.82rem">{{ $issue->issue_date->format('d M Y') }}</td>
                    <td style="font-size:.82rem;{{ $issue->status==='overdue'?'color:#dc2626;font-weight:700':'' }}">
                        {{ $issue->due_date->format('d M Y') }}
                        @if($issue->status==='overdue')
                            <div style="font-size:.7rem">({{ $issue->days_overdue }}d late)</div>
                        @endif
                    </td>
                    <td style="font-size:.82rem">{{ $issue->return_date ? $issue->return_date->format('d M Y'):'—' }}</td>
                    <td><span class="status-pill pill-{{ $issue->status }}"><i class="bi bi-circle-fill" style="font-size:.35rem"></i> {{ ucfirst($issue->status) }}</span></td>
                    <td style="font-size:.82rem">
                        @if($issue->fine_amount > 0)
                            <span style="color:#dc2626;font-weight:700">₹{{ number_format($issue->fine_amount,2) }}</span>
                        @elseif($issue->status==='overdue')
                            <span style="color:#d97706;font-weight:700">₹{{ number_format($issue->calculateFine(),2) }}</span>
                        @else — @endif
                    </td>
                    <td class="text-end" style="padding-right:20px">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('issues.show',$issue) }}" class="btn-icon btn-icon-view"><i class="bi bi-eye"></i></a>
                            @if($issue->status!=='returned')
                                <button class="btn-icon btn-icon-return btn-return"
                                        data-id="{{ $issue->id }}" data-book="{{ $issue->book->title }}"
                                        data-fine="{{ $issue->calculateFine() }}">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center py-5 text-muted" style="font-family:'Crimson Pro',serif;font-size:1.1rem">No transactions found</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($issues->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center"
         style="background:var(--cream);border-top:1px solid var(--border)">
        <small class="text-muted">{{ $issues->firstItem() }}–{{ $issues->lastItem() }} of {{ $issues->total() }}</small>
        {{ $issues->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Return Modal --}}
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content" style="border:none;border-radius:14px;border-top:4px solid #059669">
            <div class="modal-body p-4 text-center">
                <div style="width:52px;height:52px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;
                    justify-content:center;margin:0 auto 14px;font-size:1.3rem;color:#059669">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <h6 style="font-family:'Crimson Pro',serif;font-size:1.15rem;margin-bottom:6px">Mark as Returned?</h6>
                <p style="font-size:.85rem;color:#777;margin-bottom:4px"><strong id="retBookName"></strong></p>
                <div id="fineNote" class="d-none mb-3" style="background:#fef3c7;border-radius:8px;padding:8px 12px;font-size:.82rem;color:#92400e">
                    <i class="bi bi-currency-rupee me-1"></i>Fine applicable: <strong id="fineAmt"></strong>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:8px;padding:8px 20px">Cancel</button>
                    <form id="retForm" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" style="background:#059669;color:#fff;border:none;border-radius:8px;padding:8px 20px;font-weight:700;font-size:.85rem">Confirm Return</button>
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
    $('.btn-return').on('click',function(){
        const fine=parseFloat($(this).data('fine'));
        $('#retBookName').text($(this).data('book'));
        $('#retForm').attr('action','/issues/'+$(this).data('id')+'/return');
        fine>0 ? $('#fineNote').removeClass('d-none') && $('#fineAmt').text('₹'+fine.toFixed(2)) : $('#fineNote').addClass('d-none');
        $('#returnModal').modal('show');
    });
});
</script>
@endpush
