<?php

namespace App\DataTables;

use App\Models\Area;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AreaDataTable extends DataTable
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
            ->editColumn('address',function($role){
                return $role->address ?? "";
            })
            ->addColumn('action',function($role){
                $action='';
                // if (Gate::check('role_edit')) {
                    $editIcon = view('components.svg-icon', ['icon' => 'edit'])->render();
                    $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-info m-1 edit_area" data-id="'.encrypt($role->id).'" data-address="'.$role->address.'">'.$editIcon.'</a>';
                // }
                // if (Gate::check('role_show')) {
                    $deleteIcon = view('components.svg-icon', ['icon' => 'delete'])->render();
                    $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger m-1 delete_area" data-id="'.encrypt($role->id).'">  '.$deleteIcon.'</a>';
                // }
                return $action;
            })->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Area $model): QueryBuilder
    {
        //return $model->newQuery();
        $query = $model->newQuery()->select(['areas.*']);
        if (!(auth()->user()->hasRole(config('app.roleid.super_admin')))) {
            $query->whereNotIn('id', [1]);
        }
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('brand-table')
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
            Column::make('address')->title('Address'),
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
        return 'Role_' . date('YmdHis');
    }
}
