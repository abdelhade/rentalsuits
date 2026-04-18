@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">إدارة المجموعات (الشجرية)</h3>
    <a href="{{ route('categories.create') }}" id="add-new-btn" class="btn btn-primary"><i class="fa-solid fa-plus"></i> إضافة مجموعة</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card card-glass p-4 mt-4">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>اسم المجموعة</th>
                    <th>المستوى</th>
                    <th>المجموعة الأب</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allCategories as $category)
                <tr>
                    <td style="padding-right: {{ $category->level > 0 ? ($category->level * 30 + 15) . 'px' : '15px' }}">
                        @if($category->level > 0)
                           <i class="fa-solid fa-turn-down fa-rotate-90 text-muted me-1"></i>
                        @endif
                        <strong>{{ $category->name }}</strong>
                    </td>
                    <td>{{ $category->level }}</td>
                    <td>{{ $category->parent ? $category->parent->name : '---' }}</td>
                    <td class="text-center">
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning mb-1"><i class="fa-solid fa-edit"></i></a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline-block mb-1" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">لا توجد مجموعات حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
