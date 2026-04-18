@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">إضافـة إيجـار بـدلة</h3>
</div>

<form action="{{ route('rentals.store') }}" method="POST" id="rentalForm">
    @csrf

    <!-- رأس الفاتورة (Header) -->
    <div class="card card-glass p-4 mb-4">
        <h5 class="mb-3 text-primary"><i class="fa-solid fa-address-card"></i> بيانات العميل (الهيد)</h5>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">اسم العميل <span class="text-danger">*</span></label>
                <div class="input-group">
                    <select name="customer_id" id="customer_id" class="form-select select2" required>
                        <option value="">-- اختر العميل --</option>
                        @foreach($customers as $cust)
                            <option value="{{ $cust->id }}">{{ $cust->name }} - {{ $cust->phone }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary px-3" type="button" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
                        <i class="fa-solid fa-plus"></i> إضافة
                    </button>
                </div>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">تاريخ الحجز <span class="text-danger">*</span></label>
                <input type="date" name="date" class="form-control fw-bold text-center" value="{{ $date }}" required>
            </div>
        </div>
    </div>

    <!-- التفاصيل (Details) -->
    <div class="card card-glass p-4 mb-4">
        <h5 class="mb-3 text-primary"><i class="fa-solid fa-tags"></i> تفاصيل الفاتورة (جسم الفاتورة)</h5>
        
        <div class="table-responsive">
            <table class="table table-custom text-center" id="itemsTable">
                <thead>
                    <tr>
                        <th width="40%">الصنف (البدلة) <span class="text-danger">*</span></th>
                        <th width="15%">الكمية</th>
                        <th width="20%">السعر</th>
                        <th width="20%">القيمة (الإجمالي)</th>
                        <th width="5%">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="items[0][item_id]" class="form-select select-item" required>
                                <option value="">-- اختر الصنف --</option>
                                @foreach($items as $i)
                                    <option value="{{ $i->id }}" data-price="{{ $i->rental_price }}">{{ $i->name }} {{ $i->barcode ? '('.$i->barcode.')' : '' }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][qty]" class="form-control text-center input-qty" value="1" min="1" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][price]" class="form-control text-center input-price" value="0" min="0" step="0.01" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][total]" class="form-control text-center input-total bg-light fw-bold" value="0" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-row" disabled><i class="fa-solid fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm" id="addItemRow"><i class="fa-solid fa-plus"></i> إضافة صنف آخر</button>
    </div>

    <!-- الفوتر (Footer) -->
    <div class="card card-glass p-4 mb-4 bg-light">
        <h5 class="mb-3 text-primary"><i class="fa-solid fa-calculator"></i> الحسابات والإجماليات (الفوتر)</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">الإجمالي</label>
                <div class="input-group">
                    <input type="number" name="total_amount" id="final_total" class="form-control fs-4 fw-bold text-danger text-center bg-white" value="0" readonly required>
                    <span class="input-group-text">جنيه</span>
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="form-label fw-bold">المدفوع</label>
                <div class="input-group">
                    <input type="number" name="paid_amount" id="paid_amount" class="form-control fs-4 fw-bold text-success text-center" value="0" min="0" step="0.01" required>
                    <span class="input-group-text">جنيه</span>
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="form-label fw-bold">الباقي</label>
                <div class="input-group">
                    <input type="number" name="remaining_amount" id="remaining_amount" class="form-control fs-4 fw-bold text-secondary text-center bg-white" value="0" readonly>
                    <span class="input-group-text">جنيه</span>
                </div>
            </div>
        </div>
    </div>

    <div class="text-start mb-5">
        <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fa-solid fa-save"></i> حفظ الفاتورة (F12)</button>
        <a href="/" class="btn btn-secondary btn-lg px-4 ms-2">إلغاء</a>
    </div>
</form>

<!-- Modal Add Customer -->
<div class="modal fade" id="newCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card-glass border-0">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-user-plus text-primary"></i> إضافة عميل جديد سريعاً</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="quickAddCustomerForm">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="modal_cust_name" class="form-control frst" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">رقم الهاتف 1</label>
                            <input type="text" name="phone" id="modal_cust_phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">رقم الهاتف 2</label>
                            <input type="text" name="phone_2" id="modal_cust_phone2" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">العنوان</label>
                            <input type="text" name="address" id="modal_cust_address" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">العميل المرشح (Referred By)</label>
                            <select name="referred_by" id="modal_cust_ref" class="form-select select2-modal" style="width: 100%">
                                <option value="">-- بدون ترشيح --</option>
                                @foreach($customers as $cc)
                                    <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="btnSaveQuickCustomer">حفظ وإدراج</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({ dir: "rtl" });
        $('.select2-modal').select2({ dir: "rtl", dropdownParent: $('#newCustomerModal') });

        let rowIdx = 1;

        // Auto fetch price when item selected
        $(document).on('change', '.select-item', function() {
            let price = $(this).find(':selected').data('price') || 0;
            let tr = $(this).closest('tr');
            tr.find('.input-price').val(price);
            calcRow(tr);
        });

        // Calc Row Total
        $(document).on('input', '.input-qty, .input-price', function() {
            calcRow($(this).closest('tr'));
        });

        function calcRow(tr) {
            let qty = parseFloat(tr.find('.input-qty').val()) || 0;
            let price = parseFloat(tr.find('.input-price').val()) || 0;
            let total = qty * price;
            tr.find('.input-total').val(total.toFixed(2));
            calcGrandTotal();
        }

        // Calculate Final Total
        function calcGrandTotal() {
            let grandTotal = 0;
            $('.input-total').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('#final_total').val(grandTotal.toFixed(2));
            calcRemaining();
        }

        // Calculate Remaining
        function calcRemaining() {
            let t = parseFloat($('#final_total').val()) || 0;
            let p = parseFloat($('#paid_amount').val()) || 0;
            $('#remaining_amount').val((t - p).toFixed(2));
        }

        $('#paid_amount').on('input', calcRemaining);

        // Add Row
        $('#addItemRow').click(function() {
            let tr = `
                <tr>
                    <td>
                        <select name="items[${rowIdx}][item_id]" class="form-select select-item select2-dyn" required>
                            <option value="">-- اختر الصنف --</option>
                            @foreach($items as $i)
                                <option value="{{ $i->id }}" data-price="{{ $i->rental_price }}">{{ $i->name }} {{ $i->barcode ? '('.$i->barcode.')' : '' }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="items[${rowIdx}][qty]" class="form-control text-center input-qty" value="1" min="1" required></td>
                    <td><input type="number" name="items[${rowIdx}][price]" class="form-control text-center input-price" value="0" min="0" step="0.01" required></td>
                    <td><input type="number" name="items[${rowIdx}][total]" class="form-control text-center input-total bg-light fw-bold" value="0" readonly></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa-solid fa-times"></i></button></td>
                </tr>
            `;
            $('#itemsTable tbody').append(tr);
            $('#itemsTable tbody tr:last').find('.select2-dyn').select2({ dir: "rtl" });
            
            // Enable remove button on first row if > 1
            if($('#itemsTable tbody tr').length > 1) {
                $('#itemsTable tbody tr:first .remove-row').prop('disabled', false);
            }
            rowIdx++;
        });

        // Remove Row
        $(document).on('click', '.remove-row', function() {
            if ($('#itemsTable tbody tr').length > 1) {
                $(this).closest('tr').remove();
                calcGrandTotal();
            }
            if ($('#itemsTable tbody tr').length === 1) {
                $('#itemsTable tbody tr:first .remove-row').prop('disabled', true);
            }
        });

        // --- Ajax Quick Add Customer ---
        $('#btnSaveQuickCustomer').click(function() {
            let data = {
                _token: '{{ csrf_token() }}',
                name: $('#modal_cust_name').val(),
                phone: $('#modal_cust_phone').val(),
                phone_2: $('#modal_cust_phone2').val(),
                address: $('#modal_cust_address').val(),
                referred_by: $('#modal_cust_ref').val()
            };

            if(!data.name) {
                Swal.fire('خطأ', 'الاسم مطلوب', 'error');
                return;
            }

            $.ajax({
                url: '{{ route("customers.store") }}?quick_ajax=1',
                type: 'POST',
                data: data,
                success: function(res) {
                    if (res.success) {
                        // Append to select
                        let newOption = new Option(res.customer.name + ' - ' + (res.customer.phone || ''), res.customer.id, false, true);
                        $('#customer_id').append(newOption).trigger('change');
                        
                        // Append to referred options as well
                        let newRefOption = new Option(res.customer.name, res.customer.id, false, false);
                        $('#modal_cust_ref').append(newRefOption);

                        // Reset & Close
                        $('#quickAddCustomerForm')[0].reset();
                        $('#modal_cust_ref').val('').trigger('change');
                        $('#newCustomerModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'تم',
                            text: 'تم إضافة العميل بنجاح!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(err) {
                    let errorMessage = 'تأكد من صحة البيانات (قد يكون الرقم مسجل مسبقاً)';
                    if (err.responseJSON && err.responseJSON.errors) {
                        errorMessage = Object.values(err.responseJSON.errors)[0][0];
                    }
                    Swal.fire('خطأ', errorMessage, 'error');
                }
            });
        });
    });
</script>
@endpush
