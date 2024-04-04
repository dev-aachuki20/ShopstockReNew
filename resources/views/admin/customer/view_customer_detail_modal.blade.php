<div class="modal fade px-3" id="customerMonthDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-8">
                    <h6>{{ $customer->name ? ucwords($customer->name) : "" }} ( {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }} ) </h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Voucher Number</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalSales = 0;
                                $totalCashReceipt = 0;
                                $totalSalesReturn = 0;
                                @endphp
                                @foreach ($alldata as $data)
                                <tr>
                                    <td>{{ $data->entry_date ? \Carbon\Carbon::parse($data->entry_date)->format('d-m-Y') : '' }}</td>
                                    <td>{{ $data->type=='sales' ? "Sales" : ($data->type=='sales_return' ? "Estimate Return" : "Cash Receipt") }}</td>
                                    <td>{{ $data->voucher_number ?? ""}}</td>
                                    <td>{{ $data->type=='sales' ? $data->amount : ""}}</td>
                                    <td>{{ $data->type=='cashreceipt' || $data->type=='sales_return' ? $data->amount : "" }}</td>
                                </tr>
                                @php
                                    if ($data->type == 'sales') {
                                        $totalSales += $data->amount;
                                    } elseif ($data->type == 'cashreceipt') {
                                        $totalCashReceipt += $data->amount;
                                    } elseif ($data->type == 'sales_return') {
                                        $totalSalesReturn += $data->amount;
                                    }
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Grand Total</th>
                                    <th>{{ number_format($totalSales, 2, '.', ',') }}</th>
                                    <th>{{ number_format($totalCashReceipt+$totalSalesReturn, 2, '.', ',') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Closing Balance</th>
                                    <th >{{ number_format($totalSales - ($totalCashReceipt+$totalSalesReturn), 2, '.', ',') }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


