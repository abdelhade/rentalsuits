@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">إضافة عميل جديد</h3>
</div>

<div class="card card-glass p-4 mt-4">
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control frst" autofocus required value="{{ old('name') }}">
                @error('name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">رقم الهاتف 1</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                @error('phone')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="phone_2" class="form-label">رقم الهاتف 2</label>
                <input type="text" name="phone_2" id="phone_2" class="form-control" value="{{ old('phone_2') }}">
                @error('phone_2')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="city" class="form-label">المدينة</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">
                @error('city')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="address" class="form-label">العنوان</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
                @error('address')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="national_id" class="form-label">الرقم القومي <span class="text-danger">*</span></label>
                <input type="text" name="national_id" id="national_id" class="form-control" value="{{ old('national_id') }}" required>
                @error('national_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                @error('address')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12 mb-3">
                <label for="referred_by" class="form-label">العميل المرشح</label>
                <select name="referred_by" id="referred_by" class="form-select select2">
                    <option value="">-- بدون ترشيح --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('referred_by') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('referred_by')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> حفظ</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({ dir: "rtl" });
    });
</script>
@endpush
