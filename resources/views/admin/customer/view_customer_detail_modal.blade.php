<div class="modal fade px-3" id="customerMonthDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-8">
                    <h6>Name : {{ $customer->name ?? "" }} </h4>
                    <h6>Month : {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h4>
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
                                {{-- @dd($alldata) --}}
                                @foreach ($alldata as $data)
                                <tr>
                                    {{-- <td>{{ $data->entry_date ? $data->entry_date->format('d-m-Y') : ''}}</td> --}}
                                    <td>{{ $data->entry_date ? \Carbon\Carbon::parse($data->entry_date)->format('d-m-Y') : '' }}</td>

                                    <td>{{ $data->type=='sales' ? "Sales" : "Cash Receipt"}}</td>
                                    <td>{{ $data->voucher_number ?? ""}}</td>
                                    <td>{{ $data->type=='sales' ? $data->amount : ""}}</td>
                                    <td>{{ $data->type=='cashreceipt' ? $data->amount : ""}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


