<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Product;

class ProductExport implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $product_id;

    public function __construct($product_id)
    {
        $this->product_id = $product_id;
    }

    public function collection()
    {

        $query = Product::query();

        if ($this->product_id !== null) {
            $query->where('id', $this->product_id);
        }

        $query->select(['products.*','groups.name as group_name','sub_group.name as sub_group_name','product_units.name as product_unit_name']);
        $query->leftJoin('groups', 'groups.id', '=', 'products.group_id');
        $query->leftJoin('groups as sub_group', 'sub_group.id', '=', 'products.sub_group_id');
        $query->leftJoin('product_units', 'product_units.id', '=', 'products.unit_type');
        $groups = $query->get();

        return $groups->map(function ($group, $key) {
            $html = "";
            $calculation = config('constant.calculationType');                
            $html .= $calculation[$group->calculation_type];
              return [
                'Sn.' => $key + 1,
                'Name' => $group->name ?? "",
                'Calculation Type' =>$html ?? "",
                'Unit Type' => $group->product_unit_name ?? "",
                'Group' => $group->group_name ?? "",
                'Sub Group' => $group->sub_group_name ?? "",
                'Height' => $group->is_height ?? '',
                'Width' => $group->is_width ?? '',
                'Length' => $group->is_length ?? '',
                'Is Sub Product' => $group->is_sub_product ?? '',
                'Hint' => $group->extra_option_hint ?? '',
                'Purchase Price' => $group->price ?? 0,
                'Min. Sale Price' => $group->min_sale_price??0,
                'Wholesaler price' => $group->wholesaler_price??0,
                'Retailer price' => $group->retailer_price??0,
            ];
        });
       
    }

    public function headings(): array
    {
        return ["Sn.", "Name" , "Calculation Type","Unit Type" , "Group", "Sub Group" ,'Height','Width','Length','Is Sub Product','Hint', "Purchase Price","Min. Sale Price","Wholesaler price","Retailer price"];
    }
}
