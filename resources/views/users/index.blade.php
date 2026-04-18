@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">إدارة المستخدمين والصلاحيات</h3>
    <a href="{{ route('users.create') }}" id="add-new-btn" class="btn btn-primary"><i class="fa-solid fa-plus"></i> إضافة مستخدم</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card card-glass p-4 mt-4">
    <div class="table-responsive">
        <table class="table table-custom text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>صلاحية الوصول</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->hasRole('admin'))
                            <span class="badge bg-danger">مدير نظام كامل</span>
                        @else
                            <span class="badge bg-info text-dark">مخصص ({{ $user->permissions->count() }} صلاحية)</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning mb-1"><i class="fa-solid fa-edit"></i></a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block mb-1" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">لا يوجد مستخدمين.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection
