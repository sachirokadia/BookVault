@extends('layouts.app')
@section('title','Edit Book')
@section('page-title','Edit Book')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Catalogue</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="row justify-content-center">
<div class="col-xl-7">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil-fill"></i> Editing: <em>{{ $book->title }}</em></div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('books.update',$book) }}" id="bookForm">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Book Title *</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title',$book->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">ISBN *</label>
                    <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                           value="{{ old('isbn',$book->isbn) }}" required>
                    @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Author *</label>
                    <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                           value="{{ old('author',$book->author) }}" required>
                    @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Genre / Category *</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">— Select Genre —</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id',$book->category_id)==$c->id?'selected':'' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Publisher</label>
                    <input type="text" name="publisher" class="form-control" value="{{ old('publisher',$book->publisher) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Year Published</label>
                    <input type="number" name="published_year" class="form-control"
                           value="{{ old('published_year',$book->published_year) }}" min="1" max="{{ date('Y') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Total Copies *</label>
                    <input type="number" name="total_copies" class="form-control @error('total_copies') is-invalid @enderror"
                           value="{{ old('total_copies',$book->total_copies) }}" min="1" required>
                    @error('total_copies')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Synopsis</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description',$book->description) }}</textarea>
                </div>
            </div>
            <div class="alert" style="background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:10px 14px;font-size:.82rem;margin-top:16px">
                <i class="bi bi-info-circle me-1" style="color:#d97706"></i>
                <strong>{{ $book->available_copies }}</strong> of <strong>{{ $book->total_copies }}</strong> copies currently available.
                <strong>{{ $book->total_copies - $book->available_copies }}</strong> on loan.
            </div>
            <hr style="border-color:var(--border);margin:20px 0">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-gold btn-sm px-4" id="submitBtn">
                    <i class="bi bi-save me-1"></i>Update Book
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>$('#bookForm').on('submit',function(){$('#submitBtn').prop('disabled',true).html('<span class="spinner-border spinner-border-sm me-1"></span>Updating…')});</script>
@endpush
