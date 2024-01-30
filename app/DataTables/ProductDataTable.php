<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
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
            ->editColumn('image',function($row){
                $imageUrl = $row->image? asset('storage/'.$row->image):asset('admintheme/assets/img/user.png');
                $image = '<img alt="image" src="'.$imageUrl.'" alt="profile" class="widthHeigh rounded-circle profile-image" >';
                return $image ?? "";
            })
            ->editColumn('name',function($row){
                return $row->name ?? "";
            })
            ->editColumn('product_category_id',function($row){
                return $row->category_name ?? "";
            })
            ->editColumn('group_id',function($row){
                return $row->group_name ?? "";
            })
            ->addColumn('action',function($row){
                $action='';
                 if (Gate::check('product_edit')) {
                    $editIcon = view('components.svg-icon', ['icon' => 'edit'])->render();
                    $editUrl = route("admin.master.products.edit",$row->id);
                    $action .= '<a href="'.$editUrl.'" class="btn btn-icon btn-info m-1 edit_product" data-id="'.encrypt($row->id).'" data-name="'.$row->name.'">'.$editIcon.'</a>';
                 }
                 if (Gate::check('product_delete')) {
                    $deleteIcon = view('components.svg-icon', ['icon' => 'delete'])->render();
                    $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger m-1 delete_product" data-id="'.encrypt($row->id).'">  '.$deleteIcon.'</a>';
                 }
                return $action;
            })->rawColumns(['action','image']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        //return $model->newQuery();
        $query = $model->newQuery()->select(['products.*','product_categories.name as category_name','groups.name as group_name']);
        $query->leftJoin('product_categories', 'product_categories.id', '=', 'products.product_category_id');
        $query->leftJoin('groups', 'groups.id', '=', 'products.group_id');
        $query->whereNull('product_categories.deleted_at')->whereNull('groups.deleted_at');
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
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
            Column::make('image')->title(trans('admin_master.g_image')),
            Column::make('name')->title(trans('admin_master.product.name')),
            Column::make('product_category_id')->title(trans('admin_master.product.category_name')),
            Column::make('group_id')->title(trans('admin_master.product.group_type_name')),
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
        return 'product' . date('YmdHis');
    }
}
