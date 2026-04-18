@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">إدارة الأصناف</h3>
    <a href="{{ route('items.create') }}" id="add-new-btn" class="btn btn-primary"><i class="fa-solid fa-plus"></i> إضافة صنف</a>
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
                    <th>الباركود</th>
                    <th>اسم الصنف</th>
                    <th>المجموعة</th>
                    <th>سعر الشراء</th>
                    <th>سعر التأجير</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->barcode ?? '---' }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category ? $item->category->name : '---' }}</td>
                    <td>{{ number_format($item->purchase_price, 2) }}</td>
                    <td>{{ number_format($item->rental_price, 2) }}</td>
                    <td>
                        @if($item->status == 'available')
                            <span class="badge bg-success">متاح</span>
                        @elseif($item->status == 'rented')
                            <span class="badge bg-warning text-dark">مؤجر</span>
                        @else
                            <span class="badge bg-danger">مباع</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning mb-1"><i class="fa-solid fa-edit"></i></a>
                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline-block mb-1" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">لا توجد أصناف حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $items->links() }}
    </div>
</div>
@endsection
