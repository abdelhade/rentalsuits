@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">إدارة العملاء</h3>
    <a href="{{ route('customers.create') }}" id="add-new-btn" class="btn btn-primary"><i class="fa-solid fa-plus"></i> إضافة عميل</a>
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
                @forelse($customers as $customer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>
                        {{ $customer->phone ?? '---' }}
                        @if($customer->phone_2)
                            <br><small class="text-muted">{{ $customer->phone_2 }}</small>
                        @endif
                    </td>
                    <td>{{ $customer->city ?? '---' }}</td>
                    <td>{{ $customer->address ?? '---' }}</td>
                    <td>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning mb-1"><i class="fa-solid fa-edit"></i></a>
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline-block mb-1" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">لا يوجد عملاء حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $customers->links() }}
    </div>
</div>
@endsection
