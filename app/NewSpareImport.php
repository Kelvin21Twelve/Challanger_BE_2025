<?php

namespace App;

use App\NewSpareParts;
use App\Brand;
use App\Supplier;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NewSpareImport implements ToModel,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        $if_exist_itemcode = NewSpareParts::where([['item_code','=',$row['itemcode']],['is_delete','=',0]])->get();
        // if(isset($if_exist_itemcode[0]['id'])){
        //     $total_count=$if_exist_itemcode[0]['balance']+$row['balance'];
        //     $update_balance=NewSpareParts::where('item_code',$row['itemcode'])->update(['balance' => $row['balance']]);
        // } 
        $if_exist_brand = Brand::where('brand_name',$row['brand'])->get(); 
        $if_exist_supplier = Supplier::where('name',$row['supplier'])->get(); 
        //print_r($if_exist_itemcode); print_r($if_exist_brand); print_r($if_exist_supplier);   die();
        
        if(!isset($if_exist_itemcode[0]['id'])  && isset($if_exist_brand[0]['id'])  && isset($if_exist_supplier[0]['name'])
         && (($row['saleprice']) > ($row['agentprice'])) && (($row['item_unit']) =="Pcs" || ($row['item_unit']) =="Carton" || ($row['item_unit']) =="Litre")  ) {
          
          $NewSpare = new NewSpareParts();
          $NewSpare->user_id = 1;
          $NewSpare->item_code = $row['itemcode'];
          $NewSpare->item_name = $row['itemname'];
          $NewSpare->agent_price = $row['agentprice'];
          $NewSpare->sale_price = $row['saleprice'];
          $NewSpare->balance = $row['balance'];
          $NewSpare->avg_cost = $row['avgcost'];
          $NewSpare->min_limit = $row['minlimit']; 
          $NewSpare->supplier = $if_exist_supplier[0]['name'];
          $NewSpare->item_unit = $row['item_unit']; 
          $NewSpare->brand = $if_exist_brand[0]['id'];     
          if ($NewSpare->save()) {
               return $NewSpare;
          }
        }

    }

    public function headingRow(): int
     {
         return 1;
     }
}
