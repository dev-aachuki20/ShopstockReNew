<?php

namespace App\DataTables;

use App\Models\RoleIp;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RolePermissionDataTable extends DataTable
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
            ->editColumn(' ',function($row){
                return $row->ip_address ?? "";
            })
            ->addColumn('action',function($row){
                $action='';
                //  if (Gate::check('brand_edit')) {
                    $editIcon = view('components.svg-icon', ['icon' => 'edit'])->render();
                    $editUrl = route("role_ip.edit",['role_ip' => $row->id] );
                    $action .= '<a href="'.$editUrl.'" class="btn btn-icon btn-info m-1 edit_product">'.$editIcon.'</a>';
                    
                    //  }
                //  if (Gate::check('brand_delete')) {
                    $deleteIcon = view('components.svg-icon', ['icon' => 'delete'])->render();
                    $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger m-1 delete_role_ip" data-id="'.encrypt($row->id).'">  '.$deleteIcon.'</a>';
                //  }
                return $action;
            })->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(RoleIp $model): QueryBuilder
    {
        //return $model->newQuery();
        $query = $model->newQuery()->select(['role_ips.*']);
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('role_ip-table')
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
            Column::make('ip_address')->title(trans('quickadmin.ip.title')),
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
        return 'Ip_address' . date('YmdHis');
    }
}
