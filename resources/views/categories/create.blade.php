@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">إضافة مجموعة جديدة</h3>
</div>

<div class="card card-glass p-4 mt-4">
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">اسم المجموعة <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control frst" autofocus required value="{{ old('name') }}">
                @error('name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="parent_id" class="form-label">المجموعة الأب (اختياري)</label>
                <select name="parent_id" id="parent_id" class="form-select select2">
                    <option value="">بدون مستوى أب (رئيسية)</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ str_repeat('--', $cat->level) }} {{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('parent_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> حفظ</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            dir: "rtl"
        });
    });
</script>
@endpush
