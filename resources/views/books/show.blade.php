@extends('layouts.app')
@section('title',$book->title)
@section('page-title','Book Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Catalogue</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($book->title,28) }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card" style="border-top:5px solid var(--green-mid)">
            <div class="card-body p-4 text-center">
                <div style="width:80px;height:100px;background:linear-gradient(160deg,var(--green-deep),var(--green-mid));
                    border-radius:8px;display:flex;align-items:center;justify-content:center;
                    margin:0 auto 16px;box-shadow:4px 4px 0 var(--gold)">
                    <i class="bi bi-book-fill text-white" style="font-size:2.5rem"></i>
                </div>
                <h5 style="font-family:'Crimson Pro',serif;font-size:1.3rem;margin-bottom:4px">{{ $book->title }}</h5>
                <p class="text-muted mb-3" style="font-size:.85rem">by <strong>{{ $book->author }}</strong></p>

                @if($book->available_copies > 0)
                    <span class="avail-badge avail-yes mb-3 d-inline-block">
                        <i class="bi bi-check-circle me-1"></i>{{ $book->available_copies }} Cop{{ $book->available_copies>1?'ies':'y' }} Available
                    </span>
                @else
                    <span class="avail-badge avail-no mb-3 d-inline-block">
                        <i class="bi bi-x-circle me-1"></i>All Copies On Loan
                    </span>
                @endif

                <div class="text-start" style="font-size:.82rem">
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px dashed var(--border)">
                        <span class="text-muted">ISBN</span><code>{{ $book->isbn }}</code>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px dashed var(--border)">
                        <span class="text-muted">Genre</span>
                        <span style="background:var(--cream);border:1px solid var(--border);border-radius:5px;padding:1px 8px;font-size:.74rem;font-weight:700;color:var(--green-deep)">{{ $book->category->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px dashed var(--border)">
                        <span class="text-muted">Publisher</span><span>{{ $book->publisher ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px dashed var(--border)">
                        <span class="text-muted">Year</span><span>{{ $book->published_year ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px dashed var(--border)">
                        <span class="text-muted">Total Copies</span><strong>{{ $book->total_copies }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">On Loan</span>
                        <span style="color:var(--amber);font-weight:700">{{ $book->total_copies - $book->available_copies }}</span>
                    </div>
                </div>
                @if($book->description)
                    <hr style="border-color:var(--border)">
                    <p class="text-muted text-start" style="font-size:.82rem;font-style:italic">{{ $book->description }}</p>
                @endif
                <hr style="border-color:var(--border)">
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('books.edit',$book) }}" class="btn btn-gold btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    @if($book->available_copies>0)
                    <a href="{{ route('issues.create') }}?book_id={{ $book->id }}" class="btn btn-emerald btn-sm">
                        <i class="bi bi-send me-1"></i>Issue
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i>Lending History
                <span class="badge-count">{{ $book->issues->count() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                    <tr><th>Member</th><th>Issued</th><th>Due</th><th>Returned</th><th>Status</th><th>Fine</th></tr>
                    </thead>
                    <tbody>
                    @forelse($book->issues->sortByDesc('created_at') as $issue)
                        <tr>
                            <td>
                                <div style="font-weight:700;font-size:.84rem">{{ $issue->user->name }}</div>
                                <div style="font-size:.74rem;color:#888">{{ $issue->user->member_id }}</div>
                            </td>
                            <td style="font-size:.82rem">{{ $issue->issue_date->format('d M Y') }}</td>
                            <td style="font-size:.82rem">{{ $issue->due_date->format('d M Y') }}</td>
                            <td style="font-size:.82rem">{{ $issue->return_date ? $issue->return_date->format('d M Y'):'—' }}</td>
                            <td><span class="status-pill pill-{{ $issue->status }}">{{ ucfirst($issue->status) }}</span></td>
                            <td style="font-size:.82rem">
                                @if($issue->fine_amount > 0)
                                    <span style="color:#dc2626;font-weight:700">₹{{ number_format($issue->fine_amount,2) }}</span>
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
