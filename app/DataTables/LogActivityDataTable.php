<?php

namespace App\DataTables;

use App\Models\LogActivity;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LogActivityDataTable extends DataTable
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
            ->editColumn('user_id',function($row){
                return $row->user->name ?? "";
            })
            ->editColumn('model_type',function($row){
                return $row->model_name ?? "";
            })
            ->editColumn('activity',function($row){
                return $row->activity ?? "";
            })
            ->editColumn('created_at',function($row){
                return $row->created_at ?? "";
            })
            ->addColumn('action',function($row){
                $action='';
                //  if (Gate::check('brand_edit')) {
                    $editIcon = view('components.svg-icon', ['icon' => 'view'])->render();
                    $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-info m-1 view_active_log" data-id="'.encrypt($row->id).'" >'.$editIcon.'</a>';
                //  }
                return $action;
            })->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LogActivity $model): QueryBuilder
    {
        //return $model->newQuery();
        $query = $model->newQuery()->select(['log_activities.*']);
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('log_activities-table')
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
            Column::make('user_id')->title(trans('quickadmin.logActivities.fields.name')),
            Column::make('model_type')->title(trans('quickadmin.logActivities.fields.model_type')),
            Column::make('activity')->title(trans('quickadmin.logActivities.fields.activity')),
            Column::make('created_at')->title(trans('quickadmin.qa_created_at')),
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
        return 'Log_activity_' . date('YmdHis');
    }
}
