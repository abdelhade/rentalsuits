@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2 class="page-title">دفتر تسجيل التأجير اليومي</h2>
        <p class="text-muted">التقرير المشابه للدفتر الورقي لمراجعة اليوميات</p>
    </div>
    <div>
        <input type="date" class="form-control btn-glass" id="reportDate" value="{{ date('Y-m-d') }}">
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-glass border-0">
            <div class="card-body p-0 table-responsive">
                <table class="table table-custom text-center mb-0" id="ledgerTable">
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>الاسم</th>
                            <th>البلد</th>
                            <th>رقم التليفون</th>
                            <th>السعر</th>
                            <th>المدفوع</th>
                            <th>الباقي</th>
                            <th>النوع</th>
                            <th>إجراءات</th> <!-- Actions (edit/delete) -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded here via Ajax -->
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold bg-light">
                            <td colspan="4" class="text-end">الإجمالي:</td>
                            <td id="sumTotal">0</td>
                            <td id="sumPaid">0</td>
                            <td id="sumRemaining">0</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function loadLedger() {
        var date = $('#reportDate').val();
        
        $.ajax({
            url: '/api/reports/ledger',
            data: { date: date },
            success: function(res) {
                var tbody = $('#ledgerTable tbody');
                tbody.empty();
                
                var totalAmount = 0;
                var totalPaid = 0;
                var totalRemaining = 0;

                if (res.data.length === 0) {
                    tbody.append('<tr><td colspan="9">لا توجد عمليات مسجلة في هذا اليوم</td></tr>');
                } else {
                    res.data.forEach(function(row, index) {
                        var remaining = row.total_amount - row.paid_amount;
                        
                        totalAmount += parseFloat(row.total_amount);
                        totalPaid += parseFloat(row.paid_amount);
                        totalRemaining += remaining;

                        tbody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td class="fw-bold">${row.customer.name}</td>
                                <td>${row.customer.city || '-'}</td>
                                <td>${row.customer.phone || '-'}</td>
                                <td class="text-primary fw-bold">${row.total_amount}</td>
                                <td class="text-success fw-bold">${row.paid_amount}</td>
                                <td class="text-danger fw-bold">${remaining}</td>
                                <td class="text-start">${row.notes || ''}</td>
                                <td>
                                    <button class="btn btn-sm btn-light text-primary"><i class="fa fa-edit"></i></button>
                                </td>
                            </tr>
                        `);
                    });
                }

                $('#sumTotal').text(totalAmount);
                $('#sumPaid').text(totalPaid);
                $('#sumRemaining').text(totalRemaining);
            }
        });
    }

    $(document).ready(function() {
        loadLedger();
        $('#reportDate').change(loadLedger);
    });
</script>
@endpush
