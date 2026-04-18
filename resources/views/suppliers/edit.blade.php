@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">تعديل بيانات المورد</h3>
</div>

<div class="card card-glass p-4 mt-4">
    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control frst" autofocus required value="{{ old('name', $supplier->name) }}">
                @error('name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
                @error('phone')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="city" class="form-label">المدينة</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $supplier->city) }}">
                @error('city')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="address" class="form-label">العنوان</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $supplier->address) }}">
                @error('address')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> حفظ التعديلات</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
