<?php

namespace App\DataTables;

use App\Models\OrderEditHistory;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionHistory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class PaymentTransactionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */

    public function dataTable(QueryBuilder $query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', function ($row) {
                $checkbox = "";
                $checkbox = '<input type="checkbox" class="dt_checkbox" name="orders_ids[]" value="'.$row->order_id.'">';
                return $checkbox;
            })
            ->addIndexColumn()
            ->editColumn('entry_date', function ($row) {
                return date('d-m-Y', strtotime($row->entry_date)) ?? "";
            })
            ->addColumn('customer.name', function ($row) {
                return $row->customer ? $row->customer->name : "";
            })
            ->editColumn('voucher_number', function ($row) {
                return $row->voucher_number ?? "";
            })
            ->editColumn('payment_way', function ($row) {
                return (isset(config('constant.paymentModifyWays')[$row->payment_way]) ? config('constant.paymentModifyWays')[$row->payment_way] : '');
            })
            ->editColumn('debit_amount', function ($row) {
                return ($row->payment_type == 'debit' ? number_format(abs($row->amount), 2) : '');
            })
            ->editColumn('credit_amount', function ($row) {
                return ($row->payment_type == 'credit' ? number_format(abs($row->amount), 2) : '');
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y h:i A') : '';
            })
            ->addColumn('action', function ($row) {
                $action = '<div class="estimate-action-icons">';
                $customer_name= $row->customer ? $row->customer->name : "";
                // if (Gate::check('product_access')) {
                $viewIcon = view('components.svg-icon', ['icon' => 'view'])->render();
                $historyIcon = view('components.svg-icon', ['icon' => 'staff-rejoin'])->render();
                // }
                // if (Gate::check('product_edit')) {
                $typeAction = $this->type;
                $today = \Carbon\Carbon::now()->format('Y-m-d');
                $date_created = ($row->created_at) ? $row->created_at->format('Y-m-d') : null;

                $editIcon = view('components.svg-icon', ['icon' => 'edit'])->render();
                if ($this->type == 'sales_return' || $this->type == 'sales'|| $this->type == 'current_estimate') {
                    if (Gate::check('estimate_edit') && $this->type != 'cancelled') {
                        $action .= '<a href="' . route("admin.orders.edit", [$this->type, encrypt($row->order_id)]) . '" class="btn btn-icon btn-info m-1 edit_product" title="'.trans('quickadmin.qa_update').'" >' . $editIcon . '</a>';
                    }
                    if (Gate::check('estimate_show')) {
                        $action .= (($this->type == 'cancelled') && ($row->payment_way == "by_cash")) ? '' : '<a data-url="' . route('admin.orders.show', encrypt($row->order_id)) . '" href="javascript:void(0)" class="btn btn-icon btn-info m-1 view_detail" data-customerName="'.$customer_name.' ( #' .$row->voucher_number . ')" title="'.trans('quickadmin.qa_view').'" >' . $viewIcon . '</a>';


                        if ($typeAction == 'sales'){
                            if (Gate::check('estimate_history')) {
                                if(OrderEditHistory::where('order_id', $row->order_id)->where('update_status', '!=', 'add')->exists()){
                                    $action .= '<a data-url="' . route('admin.orders.history.show',  ['type' => $typeAction, 'id' => encrypt($row->order_id)]) . '" href="javascript:void(0)" class="btn btn-icon btn-info m-1 view_history_detail" data-customerName="'.$customer_name.' ( #' . $row->voucher_number . ')" title="'.trans('quickadmin.qa_history').'" >' . $historyIcon . '</a>';
                                }
                            }
                        }
                    }
                } else if ($this->type == 'cash_reciept') {
                    if (Gate::check('transaction_edit')) {
                        $action .= '<a href="' . route("admin.transactions.edit", encrypt($row->id)) . '" class="btn btn-icon btn-info m-1 edit_product" title="'.trans('quickadmin.qa_update').'" >' . $editIcon . '</a>';
                    }
                    if (Gate::check('transaction_access')) {
                        $action .= '<a href="javascript:void(0)" data-url="' . route('admin.transactions.show', encrypt($row->id)) . '" data-customerName="'.$customer_name.' ( #' . $row->voucher_number . ')" class="btn btn-icon btn-info m-1 view_detail" title="'.trans('quickadmin.qa_view').'" >' . $viewIcon . '</a>';
                        if (Gate::check('estimate_history')) {
                            if(PaymentTransactionHistory::where('payment_transaction_id', $row->id)->count() > 1){
                                $action .= '<a href="javascript:void(0)" data-url="' . route('admin.transactions.history.show',  ['type' => $typeAction, 'id' => encrypt($row->id)]) . '" data-customerName="'.$customer_name.' ( #' .$row->voucher_number . ')" class="btn btn-icon btn-info m-1 view_history_detail" title="'.trans('quickadmin.qa_history').'" >' . $historyIcon . '</a>';
                            }
                        }
                    }
                }
                else if ($typeAction == 'modified_sales'){
                    if (Gate::check('estimate_history')) {
                        $action .= '<a data-url="' . route('admin.orders.history.show',  ['type' => $typeAction, 'id' => encrypt($row->order_id)]) . '" href="javascript:void(0)" class="btn btn-icon btn-info m-1 view_history_detail" data-customerName="'.$customer_name.' ( #' . $row->voucher_number . ')" title="'.trans('quickadmin.qa_history').'" >' . $viewIcon . '</a>';
                    }
                }
                else if($this->type == 'cancelled'){
                    if (Gate::check('estimate_show') && Gate::check('estimate_cancelled_show')) {
                        $action .= '<a data-url="' . route('admin.orders.show', encrypt($row->order_id)) . '" href="javascript:void(0)" class="btn btn-icon btn-info m-1 view_detail" data-customerName="'.$customer_name.' ( #' .$row->voucher_number . ')" title="'.trans('quickadmin.qa_view').'" >' . $viewIcon . '</a>';
                    }
                }

                if($typeAction != 'modified_sales'){
                    $deleteIcon = view('components.svg-icon', ['icon' => 'delete'])->render();
                    if ((Gate::check('estimate_delete') && ($this->type == 'sales_return' || $this->type == 'sales' || $this->type == 'modified_sales' || $this->type == 'current_estimate')) || (Gate::check('transaction_delete') && $this->type == 'cash_reciept')) {
                        $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger m-1 delete_transaction" data-id="' . encrypt($row->id) . '" title="'.trans('quickadmin.qa_delete').'" >  ' . $deleteIcon . '</a>';
                    }
                }

                $action .= '</div>';

                return $action;
            })
            ->filterColumn('customer.name', function ($query, $keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('customers.name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->where('payment_transactions.created_at', 'like', '%' . $keyword . '%');
            })
            ->setRowClass(function ($row) {

                $typeAction = $this->type;
                if($typeAction=='sales')
                {
                    return $row->order && $row->order->is_modified ? 'estimates-trans-active' : '';
                }
                elseif($typeAction=='cash_reciept'){
                        return $row->is_modified ? 'estimates-trans-active' : '';
                }
                else{
                    return '';
                }

            })
            ->rawColumns(['action','checkbox']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PaymentTransaction $model): QueryBuilder
    {
        $type = $this->type;
        switch ($type) {
            case 'sales_return':
                $model = $model->where('payment_way', 'order_return')->whereHas('customer', function ($query) {
                    $query->whereNull('deleted_at');
                });
                break;

            case 'sales':
                $model = $model->where('payment_way', 'order_create')->whereHas('customer', function ($query) {
                    $query->whereNull('deleted_at');
                });
                break;

            case 'modified_sales':
                // $model = $model->with('order')->where('payment_way', 'order_create')->whereHas('order', function ($query) {
                //     $query->whereHas('orderProduct', function ($subquery) {
                //         $subquery->whereDate('updated_at', Carbon::today());
                //     });
                // });
                // $model = $model->where('payment_way', 'order_create');
                $model = $model->with('order')->where('payment_way', 'order_create')->whereHas('order', function ($query) {
                    $query->whereHas('orderProduct', function ($subquery) {
                        $subquery->whereColumn('updated_at','!=','created_at');
                    });
                });
                break;

            case 'cash_reciept':
                $model = $model->whereIn('payment_way', ['by_cash', 'by_check', 'by_account'])->whereNotNull('voucher_number')->where('remark', '!=', 'Opening balance')->whereHas('customer', function ($query) {
                    $query->whereNull('deleted_at');
                });
                break;

            case 'cancelled':
                // $model = $model->where('is_split', 0)->onlyTrashed();
                $model = $model->where('is_split', 0)->whereHas('customer', function ($query) {
                    $query->whereNull('deleted_at');
                })->onlyTrashed();
                break;

            case 'current_estimate':
                $today = now()->format('Y-m-d');
                $model = $model->whereDate('created_at', $today)->where('payment_way', 'order_create');
                break;

            default:
                // Handle unknown type here
                return abort(404);
                break;
        }
        // if ($type == 'case_reciept') {
        //     $model = $model->orderBy('voucher_number', 'desc');
        // } else {
        //     $model = $model->orderBy('entry_date', 'asc');
        // }
        // $this->start_date
        if($this->startDate !=''){
            $model->whereDate('entry_date','>=',$this->startDate);
        }
        if($this->endDate !=''){
            $model->whereDate('entry_date','<=',$this->endDate);
        }

        if (auth()->user()->hasRole([config('app.roleid.admin'), config('app.roleid.staff')])) {
            $model = $model->whereDate('entry_date', '=', now()->toDateString());
        }

        return $model;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('payment_transaction-table')
            ->parameters([
                'responsive' => true,
                'pageLength' => 70,
                'lengthMenu' => [[10, 25, 50, 70, 100, -1], [10, 25, 50, 70, 100, 'All']],
            ])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lfrtip')
            ->orderBy(2);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $type = $this->type;
        $checkboxvisisbility = ($type ==='cash_reciept' || $type == 'cancelled') ? false : true ;
        $snvisibility = ($type ==='cash_reciept' || $type == 'cancelled') ? true : false ;
        $columns = [
            Column::computed('checkbox')->title('<label class="custom-checkbox"><input type="checkbox" id="dt_cb_all" ><span></span></label>')->titleAttr('')->orderable(false)->searchable(false)->visible($checkboxvisisbility),
            Column::make('DT_RowIndex')->title(trans('quickadmin.qa_sn'))->orderable(false)->searchable(false)->visible($snvisibility),
            Column::make('entry_date')->title(trans('quickadmin.order.fields.estimate_date'))->searchable(false),
            Column::make('customer.name')->title(trans('quickadmin.transaction.fields.customer')),
            Column::make('voucher_number')->title(trans('quickadmin.estimate_number')),
            Column::make('payment_way')->title(trans('quickadmin.transaction.fields.payment_type'))
        ];
        if ($type === 'sales' || $type === 'cancelled') {
            $columns[] = Column::make('debit_amount')->title(trans('quickadmin.transaction.fields.debit_amount'))->name('amount');
        }
        if($type === 'sales_return' || $type === 'cash_reciept' || $type === 'cancelled'){
            $columns[] = Column::make('credit_amount')->title(trans('quickadmin.transaction.fields.credit_amount'))->name('amount');
        }

        $columns[] =  Column::make('created_at')->title(trans('quickadmin.transaction.fields.created_at'))->searchable(true);
        $columns[] =  Column::computed('action')
                        ->exportable(false)
                        ->printable(false)
                        ->width(60)
                        ->addClass('text-center')->title(trans('quickadmin.qa_action'));
        return $columns;

    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'order' . date('YmdHis');
    }
}
