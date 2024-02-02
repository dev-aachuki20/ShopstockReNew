<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductUnit;
class UnitType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $urnit_types = ['DOR','DOZ','KG','PCS','PKT','SET','UNIT','DOZENS','KGS','BAGS','SQFT','FT','GRAMS','ML','LTR','BUCKET','METER','PETI','BUNDLE','CARTOON'];
        foreach($urnit_types as $unit){
              ProductUnit::create(['name'=>$unit,'created_by'=>1]);
        }
    }
}

       