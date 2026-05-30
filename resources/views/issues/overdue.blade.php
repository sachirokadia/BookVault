@extends('layouts.app')
@section('title','Overdue')
@section('page-title','Overdue Books')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('issues.index') }}">Transactions</a></li>
    <li class="breadcrumb-item active">Overdue</li>
@endsection
@section('content')

@if($overdueIssues->total() > 0)
<div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:13px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:center;font-size:.875rem;color:#991b1b">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <span><strong>{{ $overdueIssues->total() }} book(s)</strong> are overdue. Fine rate: <strong>₹{{ \App\Models\Issue::FINE_PER_DAY }}/day</strong>.</span>
</div>
@else
<div style="background:#dcfce7;border:1px solid #6ee7b7;border-radius:10px;padding:13px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:center;font-size:.875rem;color:#166534">
    <i class="bi bi-check2-circle fs-5"></i>
    <strong>All clear — no overdue books!</strong>
</div>
@endif

<div class="card">
    <div class="card-header" style="color:#991b1b">
        <i class="bi bi-hourglass-split" style="color:#dc2626"></i>
        Overdue Records <span class="badge-count" style="background:#dc2626">{{ $overdueIssues->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr><th>#</th><th>Book</th><th>Member</th><th>Due Date</th><th>Days Overdue</th><th>Est. Fine</th><th class="text-end" style="padding-right:20px">Action</th></tr>
            </thead>
            <tbody>
            @forelse($overdueIssues as $i => $issue)
                <tr>
                    <td class="text-muted" style="font-size:.78rem">{{ $overdueIssues->firstItem()+$i }}</td>
                    <td>
                        <div style="font-family:'Crimson Pro',serif;font-weight:700;font-size:.95rem">{{ Str::limit($issue->book->title,26) }}</div>
                        <div style="font-size:.74rem;color:#888">{{ $issue->book->author }}</div>
                    </td>
                    <td>
                        <div style="font-weight:700;font-size:.84rem">{{ $issue->user->name }}</div>
                        <div style="font-size:.73rem;color:#888">{{ $issue->user->phone ?? $issue->user->email }}</div>
                    </td>
                    <td style="color:#dc2626;font-weight:700;font-size:.85rem">{{ $issue->due_date->format('d M Y') }}</td>
                    <td>
                        @php $d=$issue->days_overdue; @endphp
                        <span style="background:{{ $d>14?'#991b1b':($d>7?'#d97706':'#dc2626') }};color:#fff;border-radius:6px;padding:3px 10px;font-size:.76rem;font-weight:700">
                            {{ $d }} day(s)
                        </span>
                    </td>
                    <td style="color:#dc2626;font-weight:700;font-size:.875rem">₹{{ number_format($issue->calculateFine(),2) }}</td>
                    <td class="text-end" style="padding-right:20px">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('issues.show',$issue) }}" class="btn-icon btn-icon-view"><i class="bi bi-eye"></i></a>
                            <button class="btn-icon btn-icon-return btn-return"
                                    data-id="{{ $issue->id }}" data-book="{{ $issue->book->title }}"
                                    data-fine="{{ $issue->calculateFine() }}">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-5 text-muted" style="font-family:'Crimson Pro',serif;font-size:1.1rem">No overdue books!</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($overdueIssues->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center" style="background:var(--cream);border-top:1px solid var(--border)">
        <small class="text-muted">{{ $overdueIssues->firstItem() }}–{{ $overdueIssues->lastItem() }} of {{ $overdueIssues->total() }}</small>
        {{ $overdueIssues->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content" style="border:none;border-radius:14px;border-top:4px solid #059669">
            <div class="modal-body p-4 text-center">
                <div style="width:52px;height:52px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.3rem;color:#059669"><i class="bi bi-check2-circle"></i></div>
                <h6 style="font-family:'Crimson Pro',serif;font-size:1.15rem;margin-bottom:6px">Return Book</h6>
                <p style="font-size:.84rem;color:#777;margin-bottom:4px"><strong id="retBookName"></strong></p>
                <div style="background:#fef3c7;border-radius:8px;padding:8px 12px;font-size:.82rem;color:#92400e;margin:10px 0">
                    <i class="bi bi-currency-rupee me-1"></i>Fine to collect: <strong id="retFine"></strong>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:8px;padding:8px 18px">Cancel</button>
                    <form id="retForm" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" style="background:#059669;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-weight:700;font-size:.85rem">Confirm Return</button>
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
        $('#retBookName').text($(this).data('book'));
        $('#retFine').text('₹'+parseFloat($(this).data('fine')).toFixed(2));
        $('#retForm').attr('action','/issues/'+$(this).data('id')+'/return');
        $('#returnModal').modal('show');
    });
});
</script>
@endpush
