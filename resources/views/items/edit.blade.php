@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">تعديل بيانات الصنف</h3>
</div>

<div class="card card-glass p-4 mt-4">
    <form action="{{ route('items.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">اسم الصنف <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $item->name) }}">
                @error('name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="category_id" class="form-label">المجموعة الشجرية <span class="text-danger">*</span></label>
                <select name="category_id" id="category_id" class="form-select select2" required>
                    <option value="">اختر المجموعة...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ str_repeat('--', $cat->level) }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="barcode" class="form-label">الباركود</label>
                <input type="text" name="barcode" id="barcode" class="form-control" value="{{ old('barcode', $item->barcode) }}">
                @error('barcode')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select" required>
                    <option value="available" {{ old('status', $item->status) == 'available' ? 'selected' : '' }}>متاح</option>
                    <option value="rented" {{ old('status', $item->status) == 'rented' ? 'selected' : '' }}>مؤجر</option>
                    <option value="sold" {{ old('status', $item->status) == 'sold' ? 'selected' : '' }}>مباع</option>
                </select>
                @error('status')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="purchase_price" class="form-label">سعر الشراء <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="purchase_price" id="purchase_price" class="form-control" required value="{{ old('purchase_price', $item->purchase_price) }}">
                @error('purchase_price')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="rental_price" class="form-label">سعر التأجير الافتراضي <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="rental_price" id="rental_price" class="form-control" required value="{{ old('rental_price', $item->rental_price) }}">
                @error('rental_price')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">ملاحظات / وصف</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                @error('description')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> حفظ التعديلات</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">إلغاء</a>
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
