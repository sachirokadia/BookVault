@extends('layouts.app')
@section('title','Issue Book')
@section('page-title','Issue a Book')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('issues.index') }}">Transactions</a></li>
    <li class="breadcrumb-item active">Issue Book</li>
@endsection
@section('content')
<div class="row justify-content-center">
<div class="col-xl-6">
<div class="card">
    <div class="card-header"><i class="bi bi-send-fill"></i> Issue Book to Member</div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('issues.store') }}" id="issueForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Select Book *</label>
                <select name="book_id" class="form-select @error('book_id') is-invalid @enderror"
                        id="bookSel" required>
                    <option value="">— Choose a book —</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}"
                                data-copies="{{ $book->available_copies }}"
                                {{ (old('book_id')==$book->id || request('book_id')==$book->id) ?'selected':'' }}>
                            {{ $book->title }} — {{ $book->author }} ({{ $book->available_copies }} available)
                        </option>
                    @endforeach
                </select>
                @error('book_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div id="bookInfo" class="mt-1"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Select Member *</label>
                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                    <option value="">— Choose a member —</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ old('user_id')==$m->id?'selected':'' }}>
                            {{ $m->name }} ({{ $m->member_id ?? $m->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Issue Date</label>
                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::today()->format('d M Y') }}" disabled
                           style="background:var(--cream)">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Return Due Date *</label>
                    <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                           value="{{ old('due_date',\Carbon\Carbon::today()->addDays(14)->format('Y-m-d')) }}"
                           min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes (Optional)</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Any remarks…">{{ old('notes') }}</textarea>
            </div>

            <div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:11px 14px;font-size:.8rem;color:#666;margin-bottom:16px">
                <i class="bi bi-info-circle me-1" style="color:var(--green-mid)"></i>
                Late return fine: <strong style="color:var(--green-deep)">₹{{ \App\Models\Issue::FINE_PER_DAY }}/day</strong>
            </div>

            <hr style="border-color:var(--border)">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('issues.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-emerald btn-sm px-4" id="submitBtn">
                    <i class="bi bi-send me-1"></i>Issue Book
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function(){
    $('#bookSel').on('change',function(){
        const opt=$(this).find('option:selected'), copies=parseInt(opt.data('copies')), div=$('#bookInfo');
        if(!$(this).val()){div.html('');return;}
        div.html(copies>0
            ? `<span style="background:#dcfce7;color:#166534;border-radius:6px;padding:2px 10px;font-size:.76rem;font-weight:700"><i class="bi bi-check-circle me-1"></i>${copies} cop${copies>1?'ies':'y'} available</span>`
            : `<span style="background:#fee2e2;color:#991b1b;border-radius:6px;padding:2px 10px;font-size:.76rem;font-weight:700"><i class="bi bi-x-circle me-1"></i>No copies available</span>`
        );
    }).trigger('change');
    $('#issueForm').on('submit',function(){$('#submitBtn').prop('disabled',true).html('<span class="spinner-border spinner-border-sm me-1"></span>Issuing…')});
});
</script>
@endpush
