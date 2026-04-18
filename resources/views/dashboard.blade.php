@extends('layouts.app')

@section('content')
<div class="page-header">
    <h2 class="page-title">التقويم وجدولة التأجير</h2>
    <p class="text-muted">قم بإدارة الحجوزات والعمليات اليومية بسهولة</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-glass border-0">
            <div class="card-body p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for New Transaction -->
<div class="modal fade" id="transactionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card-glass">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="modalDateTitle">إضافة عملية تسجيل - <span id="selectedDate"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="transactionForm">
                    <input type="hidden" id="entryDate" name="date">
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>نوع العملية</label>
                            <select class="form-control" name="type" id="operationType">
                                <option value="rent">تأجير بدلة</option>
                                <option value="receipt">سند قبض (دفعة)</option>
                                <option value="sale">بيع بدلة</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label>اسم العميل / المستلم</label>
                            <select class="form-control select2" name="customer_id" id="customerSelect" style="width: 100%;">
                                <!-- Loaded via Ajax -->
                                <option value="">-- اختر عميل أو أضف جديد --</option>
                            </select>
                        </div>
                    </div>

                    <div id="rentalFields">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>النوع (تفاصيل البدلة والمقاس)</label>
                                <textarea class="form-control" name="details" rows="2" placeholder="بدلة رقم (38) مقاس 54 + قميص أبيض..."></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>السعر الإجمالي</label>
                                <input type="number" class="form-control" name="total_amount" id="totalAmount" value="0">
                            </div>
                            <div class="col-md-6">
                                <label>المدفوع</label>
                                <input type="number" class="form-control" name="paid_amount" id="paidAmount" value="0">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>الباقي</label>
                                <input type="number" class="form-control bg-light" id="remainingAmount" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" id="saveTransactionBtn">حفظ العملية</button>
            </div>
        </div>
    </div>
</div>

@endsection

@stack('styles')
<style>
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: rgba(0,0,0,0.05) !important;
    }
    .fc-day-today {
        background-color: rgba(230, 179, 37, 0.1) !important; /* Secondary color light */
    }
    .fc-event {
        border: none;
        border-radius: 4px;
        padding: 3px;
        cursor: pointer;
    }
    .fc-event-rent {
        background-color: var(--primary) !important;
    }
    .fc-event-receipt {
        background-color: #28a745 !important;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'ar',
            direction: 'rtl',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            selectable: true,
            select: function(info) {
                $('#selectedDate').text(info.startStr);
                $('#entryDate').val(info.startStr);
                $('#transactionModal').modal('show');
            },
            events: '/api/calendar/events', // endpoint for events
        });
        
        calendar.render();

        $('.select2').select2({
            dropdownParent: $('#transactionModal')
        });

        // Calculate remaining
        $('#totalAmount, #paidAmount').on('input', function() {
            var total = parseFloat($('#totalAmount').val()) || 0;
            var paid = parseFloat($('#paidAmount').val()) || 0;
            $('#remainingAmount').val(total - paid);
        });

        $('#saveTransactionBtn').click(function() {
            var data = $('#transactionForm').serialize();
            $.ajax({
                url: '/api/transactions/store',
                type: 'POST',
                data: data,
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الحفظ',
                        text: 'تمت إضافة العملية بنجاح',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#transactionModal').modal('hide');
                    $('#transactionForm')[0].reset();
                    calendar.refetchEvents();
                },
                error: function(err) {
                    Swal.fire('خطأ', 'حدث خطأ أثناء الحفظ', 'error');
                }
            });
        });
    });
</script>
@endpush
