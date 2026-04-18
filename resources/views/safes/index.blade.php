@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">إدارة الصناديق والخزائن</h3>
    <a href="{{ route('safes.create') }}" id="add-new-btn" class="btn btn-primary"><i class="fa-solid fa-plus"></i> إضافة صندوق</a>
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
                    <th>اسم الصندوق</th>
                    <th>تاريخ الإضافة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($safes as $safe)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $safe->name }}</td>
                    <td>{{ $safe->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('safes.edit', $safe->id) }}" class="btn btn-sm btn-warning mb-1"><i class="fa-solid fa-edit"></i></a>
                        <form action="{{ route('safes.destroy', $safe->id) }}" method="POST" class="d-inline-block mb-1" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">لا توجد صناديق حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $safes->links() }}
    </div>
</div>
@endsection
