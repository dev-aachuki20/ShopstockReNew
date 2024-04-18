<?php

namespace App\DataTables;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportCustomerDataTable extends DataTable
{

    public function dataTable(QueryBuilder $query)
    {
        return datatables()
        ->eloquent($query)
            ->addColumn('checkbox', function ($row) {
                $checkbox = "";
                $checkbox = '<input type="checkbox" class="dt_checkbox" name="customer_ids[]" value="' . $row->id . '">';
                return $checkbox;
            })
            ->addIndexColumn()
            ->editColumn('name',function($row){
                return $row->name ?? "";
            })
            ->editColumn('phone_number',function($row){
                return $row->phone_number ?? "";
            })
            ->addColumn('debit',function($row){
               $getTotalBlance = getTotalBlance($row->id,1);
               $dabit_blance = "";
               if($getTotalBlance < 0){
                $dabit_blance = '<i class="fa fa-inr" aria-hidden="true"></i> '. number_format(abs($getTotalBlance),2);
               }
               return $dabit_blance;
            })
            ->addColumn('credit',function($row){
                $getTotalBlance = getTotalBlance($row->id,1);
                $credit_blance = "";
                if($getTotalBlance > 0){
                    $credit_blance = '<i class="fa fa-inr" aria-hidden="true"></i> '. number_format(abs($getTotalBlance),2);
                }
                return $credit_blance;
            })
            // ->addColumn('action',function($row){
            //     $action='';
            //     if (Gate::check('customer_access')) {
            //         $editIcon = view('components.svg-icon', ['icon' => 'view'])->render();
            //         $url = route('admin.customers.view_customer',['id'=> $row->id]);
            //         $action .= '<a href="'.$url.'" class="btn btn-icon btn-info m-1 view_detail">'.$editIcon.'</a>';
            //     }
            //     // if (Gate::check('customer_delete')) {
            //     //     $deleteIcon = view('components.svg-icon', ['icon' => 'delete'])->render();
            //     //     $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger m-1 delete_customer" data-id="'.encrypt($row->id).'">  '.$deleteIcon.'</a>';
            //     // }
            //     return $action;
            // })
            ->rawColumns(['debit','credit','checkbox']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Customer $model): QueryBuilder
    {
        if(request()->has('area_id') && !empty(request()->area_id))
        {
            $area_ids = explode(',', request()->area_id);
            $area_ids = array_map('intval', $area_ids);
            $model = $model->whereIn('area_id', $area_ids);
        }

        return $model->newQuery()
        ->select('customers.*')
        ->whereExists(function ($query) {
            $query->select('id')
                ->from('payment_transactions')
                ->whereColumn('payment_transactions.customer_id', 'customers.id')
                ->where('payment_transactions.remark', '<>', 'Opening balance');
        })->with('area');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('customers-table')
                    ->parameters([
                        'responsive' => true,
                        'pageLength' => 70,
                        'lengthMenu' => [[10, 25, 50, 70, 100, -1], [10, 25, 50, 70, 100, 'All']],
                    ])
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('lfrtip')
                    ->orderBy(1);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')->title('<label class="custom-checkbox"><input type="checkbox" id="dt_cb_all" ><span></span></label>')->titleAttr('')->orderable(false)->searchable(false),
            Column::make('DT_RowIndex')->title(trans('quickadmin.qa_sn'))->orderable(false)->searchable(false)->visible(false),
            Column::make('name')->title(trans('quickadmin.customers.fields.name')),
            Column::make('phone_number')->title(trans('quickadmin.customers.fields.phone_number')),
            Column::make('debit')->title(trans('quickadmin.transaction.fields.debit_amount'))->orderable(false)->searchable(false),
            Column::make('credit')->title(trans('quickadmin.transaction.fields.credit_amount'))->orderable(false)->searchable(false),
            // Column::computed('action')
            // ->exportable(false)
            // ->printable(false)
            // ->width(60)
            // ->addClass('text-center')->title(trans('quickadmin.qa_action')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'customer_' . date('YmdHis');
    }
}
