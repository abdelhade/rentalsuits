@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">إدارة الموردين</h3>
    <a href="{{ route('suppliers.create') }}" id="add-new-btn" class="btn btn-primary"><i class="fa-solid fa-plus"></i> إضافة مورد</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card card-glass p-4 mt-4">
    <div class="table-responsive">
        <table class="table table-custom text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>رقم الهاتف</th>
                    <th>المدينة</th>
                    <th>العنوان</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->phone ?? '---' }}</td>
                    <td>{{ $supplier->city ?? '---' }}</td>
                    <td>{{ $supplier->address ?? '---' }}</td>
                    <td>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning mb-1"><i class="fa-solid fa-edit"></i></a>
                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline-block mb-1" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">لا يوجد موردين حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
