@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">تعديل بيانات الصندوق</h3>
</div>

<div class="card card-glass p-4 mt-4">
    <form action="{{ route('safes.update', $safe->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">اسم الصندوق <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $safe->name) }}">
                @error('name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> حفظ التعديلات</button>
            <a href="{{ route('safes.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
