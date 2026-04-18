@extends('layouts.app')

@push('styles')
<style>
.perm-group { background: rgba(0,0,0,0.03); padding: 15px; border-radius: 8px; height: 100%; border: 1px solid rgba(0,0,0,0.05); }
.perm-title { font-weight: bold; border-bottom: 2px solid #e6b325; padding-bottom: 8px; margin-bottom: 12px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h3 class="page-title">إضافة مستخدم جديد</h3>
</div>

<div class="card card-glass p-4 mt-4">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control frst" autofocus required value="{{ old('name') }}">
                @error('name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                @error('email')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">رقم الهاتف (للدخول)</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                @error('phone')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control" required>
                @error('password')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <hr class="my-4">
        <h4 class="mb-3"><i class="fa-solid fa-shield-halved text-warning"></i> الصلاحيات والأدوار</h4>

        <div class="mb-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="roleAdmin" name="roles[]" value="admin">
                <label class="form-check-label fw-bold text-danger ms-2" for="roleAdmin">منح صلاحية "مدير نظام كامل" (Admin)</label>
            </div>
            <small class="text-muted">هذا الخيار يُعطي المستخدم كافة الصلاحيات بدون استثناء ويتجاهل التحديد الفردي بالأسفل.</small>
        </div>

        <div class="row g-3" id="permissionsContainer">
            @php
                $grouped = $permissions->groupBy(function($p) {
                    $pParts = explode(' ', $p->name);
                    return $pParts[1] ?? 'أخرى';
                });

                $translation = [
                    'customers' => 'العملاء',
                    'suppliers' => 'الموردين',
                    'safes'     => 'الصناديق/الخزائن',
                    'categories'=> 'المجموعات الشجرية',
                    'items'     => 'الأصناف',
                    'أخرى'      => 'أخرى'
                ];
                
                $actionTranslation = [
                    'view' => 'عرض',
                    'create' => 'إضافة',
                    'edit' => 'تعديل',
                    'delete' => 'حذف',
                ];
            @endphp
            
            @foreach($grouped as $groupName => $perms)
            <div class="col-md-4">
                <div class="perm-group">
                    <div class="perm-title">{{ $translation[$groupName] ?? $groupName }}</div>
                    @foreach($perms as $perm)
                        @php 
                            $act = explode(' ', $perm->name)[0]; 
                        @endphp
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->name }}" id="perm_{{ $perm->id }}">
                            <label class="form-check-label" for="perm_{{ $perm->id }}">
                                {{ $actionTranslation[$act] ?? $act }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> حفظ</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#roleAdmin').change(function() {
            if($(this).is(':checked')) {
                $('#permissionsContainer').css('opacity', '0.5');
                $('#permissionsContainer input').prop('disabled', true);
            } else {
                $('#permissionsContainer').css('opacity', '1');
                $('#permissionsContainer input').prop('disabled', false);
            }
        });
        
        if($('#roleAdmin').is(':checked')) {
            $('#permissionsContainer input').prop('disabled', true);
        }
    });
</script>
@endpush
