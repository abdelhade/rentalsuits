@extends('layouts.app')

@push('styles')
<style>
    .color-option { width: 40px; height: 40px; border-radius: 50%; display: inline-block; cursor: pointer; border: 3px solid transparent; transition: 0.3s; }
    .color-option:hover { transform: scale(1.1); }
    .color-option.active { border-color: #2b3a4a; outline: 2px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.2); }
    
    .font-option { cursor: pointer; padding: 15px; border-radius: 10px; border: 2px solid transparent; transition: 0.3s; background: var(--glass-bg); text-align: center; }
    .font-option:hover { border-color: var(--main-color); }
    .font-option.active { border-color: var(--main-color); background: rgba(0,0,0,0.05); font-weight: bold; }
    
    .theme-card { cursor: pointer; border: 2px solid transparent; border-radius: 15px; overflow: hidden; transition: 0.3s; }
    .theme-card:hover { border-color: var(--main-color); transform: translateY(-5px); }
    .theme-card.active { border-color: var(--main-color); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title"><i class="fa-solid fa-gear text-warning"></i> إعدادات المظهر والنظام</h3>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-glass p-4 mb-4">
            <h5 class="mb-4"><i class="fa-solid fa-palette text-primary"></i> اللون الأساسي للنظام</h5>
            <div class="d-flex gap-3" id="colorPicker">
                <div class="color-option" data-color="#e6b325" style="background: #e6b325;"></div> <!-- Gold/Yellow -->
                <div class="color-option" data-color="#0d6efd" style="background: #0d6efd;"></div> <!-- Blue -->
                <div class="color-option" data-color="#198754" style="background: #198754;"></div> <!-- Green -->
                <div class="color-option" data-color="#dc3545" style="background: #dc3545;"></div> <!-- Red -->
                <div class="color-option" data-color="#6f42c1" style="background: #6f42c1;"></div> <!-- Purple -->
                <div class="color-option" data-color="#fd7e14" style="background: #fd7e14;"></div> <!-- Orange -->
                <div class="color-option" data-color="#ff6b6b" style="background: #ff6b6b;"></div> <!-- Rose -->
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-glass p-4 mb-4 h-100">
            <h5 class="mb-4"><i class="fa-solid fa-font text-info"></i> نوع الخط (Font)</h5>
            <div class="row g-3" id="fontPicker">
                <div class="col-6">
                    <div class="font-option font-tajawal" data-font="Tajawal" style="font-family: 'Tajawal', sans-serif;">تجوال (Tajawal)</div>
                </div>
                <div class="col-6">
                    <div class="font-option font-cairo" data-font="Cairo" style="font-family: 'Cairo', sans-serif;">كايرو (Cairo)</div>
                </div>
                <div class="col-6">
                    <div class="font-option font-almarai" data-font="Almarai" style="font-family: 'Almarai', sans-serif;">المراعي (Almarai)</div>
                </div>
                <div class="col-6">
                    <div class="font-option font-readex" data-font="Readex Pro" style="font-family: 'Readex Pro', sans-serif;">ريدكس (Readex)</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-glass p-4 mb-4 h-100">
            <h5 class="mb-4"><i class="fa-solid fa-moon text-dark"></i> وضع الألوان (Theme)</h5>
            <div class="row g-3" id="themePicker">
                <div class="col-6">
                    <div class="theme-card" data-theme="light">
                        <div class="bg-light p-3 text-center text-dark" style="height: 100px;">
                            <i class="fa-solid fa-sun fs-1 mb-2"></i><br>
                            الوضع الفاتح
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="theme-card" data-theme="dark">
                        <div class="bg-dark p-3 text-center text-light" style="height: 100px;">
                            <i class="fa-solid fa-moon fs-1 mb-2"></i><br>
                            الوضع الداكن
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Load settings
        var currColor = localStorage.getItem('appMainColor') || '#e6b325';
        var currFont = localStorage.getItem('appFont') || 'Tajawal';
        var currTheme = localStorage.getItem('appTheme') || 'light';

        // Select actives
        $('.color-option[data-color="' + currColor + '"]').addClass('active');
        $('.font-option[data-font="' + currFont + '"]').addClass('active');
        $('.theme-card[data-theme="' + currTheme + '"]').addClass('active');

        // Handlers
        $('.color-option').click(function() {
            var color = $(this).data('color');
            localStorage.setItem('appMainColor', color);
            $('.color-option').removeClass('active');
            $(this).addClass('active');
            
            document.documentElement.style.setProperty('--main-color', color);
            alert('تم تغيير اللون الأساسي والنقر على F5 سيوضح جميع الواجهات بصورة طبيعية.');
            window.location.reload();
        });

        $('.font-option').click(function() {
            var font = $(this).data('font');
            localStorage.setItem('appFont', font);
            $('.font-option').removeClass('active');
            $(this).addClass('active');
            window.location.reload();
        });

        $('.theme-card').click(function() {
            var theme = $(this).data('theme');
            localStorage.setItem('appTheme', theme);
            $('.theme-card').removeClass('active');
            $(this).addClass('active');
            window.location.reload();
        });
    });
</script>
@endpush
