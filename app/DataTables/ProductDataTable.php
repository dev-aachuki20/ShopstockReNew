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
                return $row->name ?? "";
            })
            ->editColumn('calculation_type',function($row){
                $html = "";
                $calculation = config('constant.calculationType');                
                $html .= $calculation[$row->calculation_type];
                $html .= '<br/>'.$row->product_unit_name;
                return $html ?? "";
            })
            ->editColumn('group_id',function($row){
                $grupName = $row->group_name.'<br>'. $row->sub_group_name;
                return $grupName ?? "";
            })
            ->editColumn('price',function($row){
                return  '<i class="fa fa-inr"></i> '. $row->price ?? 0;
            })
            ->editColumn('min_sale_price',function($row){
                return  '<i class="fa fa-inr"></i> '.$row->min_sale_price ?? "";
            })
            ->editColumn('wholesaler_price',function($row){
                return '<i class="fa fa-inr"></i> '.$row->wholesaler_price ?? "";
            })
            ->editColumn('retailer_price',function($row){
                return '<i class="fa fa-inr"></i> '.$row->retailer_price ?? "";
            })
            ->addColumn('action',function($row){
                $action='';
                if($this->isRecycle == "isRecycle"){            
                    if (Gate::check('product_undo')) {
                        $editIcon = '<i class="fa fa-undo" aria-hidden="true"></i>';
                        $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-info m-1 recycle_group" data-id="'.encrypt($row->id).'">'.$editIcon.'</a>';
                    }    
                }else{ 
                    if (Gate::check('product_access')) {
                        $editIcon = view('components.svg-icon', ['icon' => 'view'])->render();
                       $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-info m-1 view_detail" data-id="'.encrypt($row->id).'" data-product_name="'.$row->name.'" >'.$editIcon.'</a>';
                   }
                    if (Gate::check('product_edit')) {
                        $editIcon = view('components.svg-icon', ['icon' => 'edit'])->render();
                        $editUrl = route("admin.master.products.edit",['product' => $row->id] );
                        $action .= '<a href="'.$editUrl.'" class="btn btn-icon btn-info m-1 edit_product" data-id="'.encrypt($row->id).'" data-name="'.$row->name.'">'.$editIcon.'</a>';
                    }
                    if (Gate::check('product_delete')) {
                        $deleteIcon = view('components.svg-icon', ['icon' => 'delete'])->render();
                        $action .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger m-1 delete_product" data-id="'.encrypt($row->id).'">  '.$deleteIcon.'</a>';
                    }                    
                }
                return $action;
            })->rawColumns(['action','calculation_type','group_id','price','min_sale_price','wholesaler_price','retailer_price']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        //return $model->newQuery();
        $query = $model->newQuery()->select(['products.*','groups.name as group_name','sub_group.name as sub_group_name','product_units.name as product_unit_name']);
        $query->leftJoin('groups', 'groups.id', '=', 'products.group_id');
        $query->leftJoin('groups as sub_group', 'sub_group.id', '=', 'products.sub_group_id');
        $query->leftJoin('product_units', 'product_units.id', '=', 'products.unit_type')->orderBy('products.id','DESC');
        //$query->whereNull('product_categories.deleted_at')->whereNull('groups.deleted_at');
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
            Column::make('name')->title(trans('quickadmin.product2.fields.name')),
            Column::make('calculation_type')->title(trans('quickadmin.product2.fields.calculation_unit')),
            Column::make('group_id')->title(trans('quickadmin.product2.fields.group_sub_group')),

            Column::make('price')->title(trans('quickadmin.product2.fields.price')),
            Column::make('min_sale_price')->title(trans('quickadmin.product2.fields.min_sale_price')),
            Column::make('wholesaler_price')->title(trans('quickadmin.product2.fields.wholesaler_price')),
            Column::make('retailer_price')->title(trans('quickadmin.product2.fields.retailer_price')),

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
