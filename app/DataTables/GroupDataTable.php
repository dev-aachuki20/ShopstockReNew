<?php

namespace App\DataTables;

use App\Models\Group;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class GroupDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    protected $isRecycle;
    public function withParam1($recycle)
    {
        $this->isRecycle = $recycle;
        return $this;
    }

    public function dataTable(QueryBuilder $query)
    {
        
        return datatables()
        ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('name',function($row){
                if($row->parent_id == 0){
                    return $row->name ?? "";
                }else{
                    return $row->parent->name ?? "";
                }                
            })
            ->editColumn('parent_id',function($row){
                return ($row->parent_id > 0)?$row->name ?? "":"";
            })
            ->editColumn('products_count',function($row){
                return ($row->products_count > 0)? $row->products_count :$row->subproducts_count;
            })
            ->addColumn('action',function($row){
                $action='';
                if($this->isRecycle == "isRecycle"){            
                    if (Gate::check('group_undo')) {
                        $editIcon = '<i class="fa fa-undo" aria-hidden="true"></i>';
                        $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-info m-1 recycle_group" data-id="'.encrypt($row->id).'">'.$editIcon.'</a>';
                    }    
                }else{                    
                    if (Gate::check('group_edit')) {
                        $editIcon = view('components.svg-icon', ['icon' => 'edit'])->render();
                        $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-info m-1 edit_group" data-id="'.encrypt($row->id).'" data-name="'.$row->name.'" data-parent_id="'.$row->parent_id.'">'.$editIcon.'</a>';
                    }
                    if (Gate::check('group_delete')) {
                        $deleteIcon = view('components.svg-icon', ['icon' => 'delete'])->render();
                        $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger m-1 delete_group" data-id="'.encrypt($row->id).'">  '.$deleteIcon.'</a>';
                    }
                }
                return $action;
            })->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Group $model): QueryBuilder
    {
        $query = $model->newQuery()->select(['groups.*'])->withCount('products')->withCount('subproducts');
        $query->orderByRaw('CASE WHEN parent_id = 0 THEN id  ELSE parent_id END DESC');
        if($this->isRecycle == "isRecycle"){            
            $query->onlyTrashed();           
        }
        return $this->applyScopes($query);       
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('group-table')
                    ->parameters([
                        'responsive' => true,
                        'pageLength' => 70,
                        'lengthMenu' => [[10, 25, 50, 70, 100, -1], [10, 25, 50, 70, 100, 'All']],
                    ])
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('lBfrtip')
                    //->orderBy(1)
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
            Column::make('name')->title(trans('quickadmin.group_master.fields.name')),
            Column::make('parent_id')->title(trans('quickadmin.group_master.fields.sub_group')),
            Column::make('products_count')->title(trans('admin_master.product.products')),
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
        return 'group-list' . date('YmdHis');
    }


}
