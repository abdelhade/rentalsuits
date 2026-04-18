@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">تعديل المجموعة</h3>
</div>

<div class="card card-glass p-4 mt-4">
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">اسم المجموعة <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $category->name) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="parent_id" class="form-label">المجموعة الأب (اختياري)</label>
                <select name="parent_id" id="parent_id" class="form-select select2">
                    <option value="">بدون مستوى أب (رئيسية)</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                            {{ str_repeat('--', $cat->level) }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> حفظ التعديلات</button>
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
