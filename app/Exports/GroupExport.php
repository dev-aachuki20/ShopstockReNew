<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Group;

class GroupExport implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $group_id;

    public function __construct($group_id)
    {
        $this->group_id = $group_id;
    }

    public function collection()
    {

        $query = Group::query();

        if ($this->group_id !== null) {
            $query->where('id', $this->group_id);
        }

        $query->select(['groups.*']);
        $query->orderByRaw('CASE WHEN parent_id = 0 THEN id ELSE parent_id END DESC');
        $groups = $query->withCount('products')->withCount('subproducts')->get();

        return $groups->map(function ($group, $key) {
              return [
                'Sn.' => $key + 1,
                'Name' => ($group->parent_id == 0)?$group->name ?? "":$group->parent->name,
                'Sub Group' => ($group->parent_id > 0)?$group->name ?? "":"",
                'Product' => ($group->products_count > 0)? $group->products_count :$group->subproducts_count,
                'Created At' => $group->created_at->format('d-m-Y'),
            ];
        });

    }

    public function headings(): array
    {
        return ["Sn.", "Name" , "Sub Group" , "Product", "Created At"];
    }
}
