@extends('layouts.app')
@section('title','Catalogue')
@section('page-title','Book Catalogue')
@section('breadcrumb')
    <li class="breadcrumb-item active">Catalogue</li>
@endsection

@push('styles')
<style>
.book-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 18px;
    border-top: 4px solid var(--green-mid);
    transition: transform .2s, box-shadow .2s;
    height: 100%;
}
.book-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
.book-spine {
    width: 42px; height: 56px;
    border-radius: 6px;
    background: linear-gradient(160deg, var(--green-deep), var(--green-mid));
    display: flex; align-items: center; justify-content: center;
    color: var(--gold); font-size: 1.1rem; flex-shrink: 0;
}
.avail-badge {
    font-size: .7rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .6px;
    padding: 3px 8px; border-radius: 6px;
}
.avail-yes { background: #dcfce7; color: #166534; }
.avail-no  { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')

{{-- Toolbar --}}
<div class="card mb-3">
    <div class="card-body py-2 px-3">
        <form method="GET" action="{{ route('books.index') }}" id="filterForm">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Title, author or ISBN…" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select" id="catFilter">
                        <option value="">All Genres</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected':'' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="availability" class="form-select" id="availFilter">
                        <option value="">All</option>
                        <option value="available"   {{ request('availability')==='available'   ?'selected':'' }}>Available</option>
                        <option value="unavailable" {{ request('availability')==='unavailable' ?'selected':'' }}>On Loan</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-emerald btn-sm flex-fill">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary" title="Clear">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    <a href="{{ route('books.create') }}" class="btn btn-gold btn-sm px-3" title="Add Book">
                        <i class="bi bi-plus-lg me-1"></i>Add
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- View toggle + count --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div style="font-size:.85rem;color:#777">
        Showing <strong>{{ $books->total() }}</strong> book(s)
    </div>
    <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-secondary active" id="viewTable" title="Table">
            <i class="bi bi-list-ul"></i>
        </button>
        <button class="btn btn-outline-secondary" id="viewGrid" title="Grid">
            <i class="bi bi-grid-3x3-gap"></i>
        </button>
    </div>
</div>

{{-- Table View --}}
<div id="tableView">
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-richtext"></i> All Books <span class="badge-count">{{ $books->total() }}</span></span>
        <a href="{{ route('books.create') }}" class="btn btn-gold btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Add Book
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Title & Author</th>
                <th>ISBN</th>
                <th>Genre</th>
                <th>Copies</th>
                <th>Available</th>
                <th>Status</th>
                <th class="text-end" style="padding-right:20px">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($books as $i => $book)
                <tr>
                    <td class="text-muted" style="font-size:.78rem">{{ $books->firstItem()+$i }}</td>
                    <td>
                        <div style="font-family:'Crimson Pro',serif;font-weight:700;font-size:.95rem">{{ $book->title }}</div>
                        <div style="font-size:.76rem;color:#888">{{ $book->author }}</div>
                    </td>
                    <td><code style="font-size:.78rem">{{ $book->isbn }}</code></td>
                    <td>
                        <span style="background:var(--cream);border:1px solid var(--border);border-radius:6px;
                            padding:2px 9px;font-size:.74rem;font-weight:700;color:var(--green-deep)">
                            {{ $book->category->name }}
                        </span>
                    </td>
                    <td style="font-size:.875rem;font-weight:600">{{ $book->total_copies }}</td>
                    <td>
                        <span style="font-weight:700;color:{{ $book->available_copies > 0 ? '#166534':'#991b1b' }};font-size:.875rem">
                            {{ $book->available_copies }}
                        </span>
                    </td>
                    <td>
                        <span class="avail-badge {{ $book->available_copies > 0 ? 'avail-yes':'avail-no' }}">
                            {{ $book->available_copies > 0 ? 'Available':'On Loan' }}
                        </span>
                    </td>
                    <td class="text-end" style="padding-right:20px">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('books.show',$book) }}" class="btn-icon btn-icon-view" title="View"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('books.edit',$book) }}" class="btn-icon btn-icon-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn-icon btn-icon-del btn-del" title="Delete"
                                    data-id="{{ $book->id }}" data-name="{{ $book->title }}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="font-family:'Crimson Pro',serif;font-size:1.1rem;color:#aaa">
                        <i class="bi bi-journal-x d-block fs-3 mb-2"></i>No books found in the catalogue
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($books->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center"
         style="background:var(--cream);border-top:1px solid var(--border)">
        <small class="text-muted">{{ $books->firstItem() }}–{{ $books->lastItem() }} of {{ $books->total() }}</small>
        {{ $books->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
</div>

{{-- Grid View (hidden by default) --}}
<div id="gridView" style="display:none">
<div class="row g-3">
    @forelse($books as $book)
    <div class="col-sm-6 col-md-4 col-xl-3">
        <div class="book-card">
            <div class="d-flex gap-3 mb-3">
                <div class="book-spine"><i class="bi bi-book-fill"></i></div>
                <div style="flex:1;min-width:0">
                    <div style="font-family:'Crimson Pro',serif;font-weight:700;font-size:.95rem;line-height:1.2">
                        {{ Str::limit($book->title, 30) }}
                    </div>
                    <div style="font-size:.75rem;color:#888;margin-top:2px">{{ $book->author }}</div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span style="background:var(--cream);border:1px solid var(--border);border-radius:6px;
                    padding:2px 8px;font-size:.7rem;font-weight:700;color:var(--green-deep)">
                    {{ $book->category->name }}
                </span>
                <span class="avail-badge {{ $book->available_copies > 0 ? 'avail-yes':'avail-no' }}">
                    {{ $book->available_copies }}/{{ $book->total_copies }}
                </span>
            </div>
            <div style="font-size:.74rem;color:#aaa;margin-bottom:10px"><code>{{ $book->isbn }}</code></div>
            <div class="d-flex gap-1">
                <a href="{{ route('books.show',$book) }}" class="btn-icon btn-icon-view" title="View"><i class="bi bi-eye"></i></a>
                <a href="{{ route('books.edit',$book) }}" class="btn-icon btn-icon-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                <button class="btn-icon btn-icon-del btn-del ms-auto"
                        data-id="{{ $book->id }}" data-name="{{ $book->title }}">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted" style="font-family:'Crimson Pro',serif;font-size:1.1rem">
        No books found
    </div>
    @endforelse
</div>
@if($books->hasPages())
    <div class="mt-3">{{ $books->links('pagination::bootstrap-5') }}</div>
@endif
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="delModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content" style="border:none;border-radius:14px;border-top:4px solid #dc2626">
            <div class="modal-body p-4 text-center">
                <div style="width:52px;height:52px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;
                    justify-content:center;margin:0 auto 14px;font-size:1.3rem;color:#dc2626">
                    <i class="bi bi-trash3-fill"></i>
                </div>
                <h6 style="font-family:'Crimson Pro',serif;font-size:1.15rem;margin-bottom:6px">Delete Book?</h6>
                <p style="font-size:.85rem;color:#777;margin-bottom:20px">
                    <strong id="delBookName"></strong> will be permanently removed.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:8px;padding:8px 20px">Cancel</button>
                    <form id="delForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius:8px;padding:8px 20px;font-weight:700">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // View toggle
    $('#viewTable').on('click', function () {
        $('#tableView').show(); $('#gridView').hide();
        $(this).addClass('active'); $('#viewGrid').removeClass('active');
    });
    $('#viewGrid').on('click', function () {
        $('#gridView').show(); $('#tableView').hide();
        $(this).addClass('active'); $('#viewTable').removeClass('active');
    });

    // Delete
    $('.btn-del').on('click', function () {
        $('#delBookName').text($(this).data('name'));
        $('#delForm').attr('action', '/books/' + $(this).data('id'));
        $('#delModal').modal('show');
    });

    // Auto-filter on select change
    $('#catFilter, #availFilter').on('change', function () { $('#filterForm').submit(); });
});
</script>
@endpush
