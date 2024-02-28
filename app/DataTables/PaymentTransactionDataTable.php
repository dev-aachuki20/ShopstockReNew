<?php

namespace App\DataTables;

use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

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
            ->addIndexColumn()
            ->editColumn('entry_date',function($row){
                return date('d-m-Y',strtotime($row->entry_date)) ?? "";
            })
            ->editColumn('customer_id',function($row){
                return $row->user->name ?? "";
            })
            ->editColumn('voucher_number',function($row){
                return $row->voucher_number ?? "";
            })
            ->editColumn('payment_way',function($row){
                return (isset(config('constant.paymentModifyWays')[$row->payment_way]) ? config('constant.paymentModifyWays')[$row->payment_way] : '');
            })
            ->editColumn('credit_amount',function($row){
                return ($row->payment_type == 'credit' ? number_format(abs($row->amount),2) : '');
            })
            ->editColumn('debit_amount',function($row){
                return ($row->payment_type == 'debit' ? number_format(abs($row->amount),2) : '');
            })
            ->editColumn('created_at',function($row){
                return $row->created_at;
            })
            ->addColumn('action',function($row){
                $action='';
                // if (Gate::check('product_access')) {
                    $editIcon = view('components.svg-icon', ['icon' => 'view'])->render();
                    $action .= '<a data-order="'.encrypt($row->order_id) .'" href="javascript:void(0)" class="btn btn-icon btn-info m-1 view_detail" >'.$editIcon.'</a>';
                // }
                // if (Gate::check('product_edit')) {
                    $editIcon = view('components.svg-icon', ['icon' => 'edit'])->render();
                    $editUrl = route("admin.orders.edit",['order' => $row->order_id] );
                    $action .= '<a href="'.$editUrl.'" class="btn btn-icon btn-info m-1 edit_product" data-id="'.encrypt($row->order_id).'" data-name="'.$row->name.'">'.$editIcon.'</a>';
                // }
                // if (Gate::check('product_delete')) {
                    // $deleteIcon = view('components.svg-icon', ['icon' => 'delete'])->render();
                    // $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger m-1 delete_product" data-id="'.encrypt($row->order_id).'">  '.$deleteIcon.'</a>';
                // }     
                return $action;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PaymentTransaction $model): QueryBuilder
    {
        //return $model->newQuery();
        if($this->type == 'sales'){
            $model = $model->where('payment_way','order_create')->orderBy('entry_date','desc');
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
                    ->dom('lBfrtip')
                    ->orderBy(1)
                    // ->selectStyleSingle()
                    ->buttons([
                        // Button::make('excel'),
                        // Button::make('csv'),
                        // Button::make('pdf'),
                        // Button::make('print'),
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('DT_RowIndex')->title(trans('quickadmin.qa_sn'))->orderable(false)->searchable(false),
            Column::make('entry_date')->title(trans('quickadmin.order.fields.estimate_date')),
            Column::make('customer_id')->title(trans('quickadmin.transaction.fields.customer')),
            Column::make('voucher_number')->title(trans('quickadmin.estimate_number')),
            Column::make('payment_way')->title(trans('quickadmin.transaction.fields.payment_type')),
            Column::make('credit_amount')->title(trans('quickadmin.transaction.fields.credit_amount')),
            Column::make('debit_amount')->title(trans('quickadmin.transaction.fields.debit_amount')),
            Column::make('created_at')->title(trans('quickadmin.transaction.fields.created_at')),

            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center')->title(trans('quickadmin.qa_action')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'order' . date('YmdHis');
    }
}
