<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DraftInvoiceDataTable extends DataTable
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
            ->addColumn('customer.name', function ($row) {
                return $row->customer ? $row->customer->name : "";
            })
            ->editColumn('invoice_number', function ($row) {
                return $row->invoice_number ?? "";
            })
            ->addColumn('total_products', function ($row) {
                return $row->orderProduct->count() ?? 0;
            })
            ->editColumn('total_amount', function ($row) {
                return $row->total_amount ?? '';
            })
            ->editColumn('remark', function ($row) {
                return  $row->remark ?? '';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at? $row->created_at->format('d-m-Y h:i A') : '';
            })
            ->addColumn('action', function ($row) {
                $action = '';
                if(Gate::check('estimate_access')){
                    $viewIcon = view('components.svg-icon', ['icon' => 'view'])->render();
                    $action .= '<a data-url="' . route('admin.orders.show', encrypt($row->id)) . '" href="javascript:void(0)" class="btn btn-icon btn-info m-1 view_detail" >' . $viewIcon . '</a>';
                }
                if(Gate::check('estimate_edit')){
                    $editIcon = view('components.svg-icon', ['icon' => 'edit'])->render();
                    $action .= '<a href="' .route("admin.orders.edit", ['draft', encrypt($row->id)]). '" class="btn btn-icon btn-info m-1 edit_product">' . $editIcon . '</a>';
                }
                if (Gate::check('estimate_delete')) {
                    $deleteIcon = view('components.svg-icon', ['icon' => 'delete'])->render();
                    $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger m-1 delete_transaction" data-id="'.encrypt($row->id).'">  '.$deleteIcon.'</a>';
                }
                return $action;
            })
            ->filterColumn('customer.name', function ($query, $keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('customers.name', 'like', "%$keyword%");
                });
            })->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        $model = $model->whereIsDraft(1)->orderBy('id', 'desc');
        if (auth()->user()->hasRole([config('app.roleid.admin'), config('app.roleid.staff')])) {
            $model = $model->whereDate('invoice_date', '=', now()->toDateString());
        }
        // return $this->applyScopes($model);
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('draft-invoice-table')
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
            Column::make('customer.name')->title(trans('quickadmin.transaction.fields.customer')),
            Column::make('invoice_number')->title(trans('quickadmin.estimate_number')),
            Column::make('total_products')->title(trans('quickadmin.order.fields.total_products')),
            Column::make('total_amount')->title(trans('quickadmin.order.fields.total_amount')),
            Column::make('remark')->title(trans('quickadmin.transaction.fields.remark')),
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
        return 'draftInvoice' . date('YmdHis');
    }
}
