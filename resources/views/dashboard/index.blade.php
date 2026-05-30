@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Overview</li>
@endsection

@push('styles')
<style>
.activity-item {
    display: flex; gap: 12px; align-items: flex-start;
    padding: 10px 0;
    border-bottom: 1px dashed #ede4d3;
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 5px;
}
.rank-badge {
    font-family: 'Crimson Pro', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #d8cdb8;
    width: 30px;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')

<hr class="section-rule">

{{-- 6 Stat Tiles --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile tile-emerald">
            <div class="tile-icon"><i class="bi bi-journal-richtext"></i></div>
            <div class="tile-val">{{ $stats['total_books'] }}</div>
            <div class="tile-lbl">Total Books</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile tile-teal">
            <div class="tile-icon"><i class="bi bi-bookmark-check"></i></div>
            <div class="tile-val">{{ $stats['available_books'] }}</div>
            <div class="tile-lbl">Available</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile tile-gold">
            <div class="tile-icon"><i class="bi bi-send-check"></i></div>
            <div class="tile-val">{{ $stats['issued_books'] }}</div>
            <div class="tile-lbl">On Loan</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile tile-red">
            <div class="tile-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="tile-val">{{ $stats['overdue_books'] }}</div>
            <div class="tile-lbl">Overdue</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile tile-violet">
            <div class="tile-icon"><i class="bi bi-people-fill"></i></div>
            <div class="tile-val">{{ $stats['total_members'] }}</div>
            <div class="tile-lbl">Members</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile tile-slate">
            <div class="tile-icon"><i class="bi bi-tags-fill"></i></div>
            <div class="tile-val">{{ $stats['categories'] }}</div>
            <div class="tile-lbl">Genres</div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Recent Transactions --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-card-list"></i> Recent Transactions</span>
                <a href="{{ route('issues.index') }}" class="btn btn-sm btn-outline-emerald">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Book / Author</th>
                        <th>Member</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentIssues as $issue)
                        <tr>
                            <td>
                                <div style="font-weight:700;font-family:'Crimson Pro',serif;font-size:.95rem">
                                    {{ Str::limit($issue->book->title, 26) }}
                                </div>
                                <div style="font-size:.75rem;color:#888">{{ $issue->book->author }}</div>
                            </td>
                            <td style="font-size:.85rem;font-weight:600">{{ $issue->user->name }}</td>
                            <td style="font-size:.82rem">{{ $issue->due_date->format('d M Y') }}</td>
                            <td>
                                <span class="status-pill pill-{{ $issue->status }}">
                                    <i class="bi bi-circle-fill" style="font-size:.4rem"></i>
                                    {{ ucfirst($issue->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('issues.show', $issue) }}" class="btn-icon btn-icon-view">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted" style="font-family:'Crimson Pro',serif;font-size:1.05rem">
                                No transactions yet
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div class="col-xl-4 d-flex flex-column gap-3">

        {{-- Overdue Alerts --}}
        <div class="card">
            <div class="card-header" style="color:#991b1b">
                <i class="bi bi-exclamation-triangle-fill" style="color:#dc2626"></i>
                Overdue Alerts
                @if($overdueIssues->count()) <span class="badge-count" style="background:#dc2626">{{ $overdueIssues->count() }}</span> @endif
            </div>
            <div class="card-body p-3">
                @forelse($overdueIssues as $issue)
                    <div class="activity-item">
                        <div class="activity-dot mt-1" style="background:#dc2626;margin-top:6px"></div>
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:700;font-size:.83rem;font-family:'Crimson Pro',serif">
                                {{ Str::limit($issue->book->title, 22) }}
                            </div>
                            <div style="font-size:.74rem;color:#888">{{ $issue->user->name }}</div>
                            <div style="font-size:.72rem;color:#dc2626;font-weight:700">
                                {{ $issue->days_overdue }} day(s) late &bull; ₹{{ number_format($issue->calculateFine(), 0) }} fine
                            </div>
                        </div>
                        <a href="{{ route('issues.show', $issue) }}" class="btn-icon btn-icon-view">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-3" style="color:#22c55e;font-size:.88rem;font-family:'Crimson Pro',serif">
                        <i class="bi bi-check2-circle fs-4 d-block mb-1"></i>All books returned on time!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Borrowed --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trophy-fill" style="color:var(--gold)"></i> Top Borrowed
            </div>
            <div class="card-body p-3">
                @foreach($popularBooks as $i => $book)
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;{{ !$loop->last ? 'border-bottom:1px dashed #ede4d3' : '' }}">
                        <span class="rank-badge">#{{ $i + 1 }}</span>
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:700;font-size:.85rem;font-family:'Crimson Pro',serif">
                                {{ Str::limit($book->title, 22) }}
                            </div>
                            <div style="font-size:.73rem;color:#888">{{ $book->issues_count }} borrows</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-lightning-charge-fill" style="color:var(--gold)"></i> Quick Actions</div>
            <div class="card-body d-flex flex-wrap gap-2 p-3">
                <a href="{{ route('books.create') }}" class="btn btn-emerald btn-sm px-3">
                    <i class="bi bi-plus-lg me-1"></i>Add Book
                </a>
                <a href="{{ route('issues.create') }}" class="btn btn-gold btn-sm px-3">
                    <i class="bi bi-send me-1"></i>Issue Book
                </a>
                <a href="{{ route('members.create') }}" class="btn btn-emerald btn-sm px-3">
                    <i class="bi bi-person-plus me-1"></i>Add Member
                </a>
                <a href="{{ route('issues.overdue') }}" class="btn btn-sm px-3"
                   style="background:#fee2e2;color:#991b1b;border:none;border-radius:8px;font-weight:700">
                    <i class="bi bi-hourglass-split me-1"></i>View Overdue
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
