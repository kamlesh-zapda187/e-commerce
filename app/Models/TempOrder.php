<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;



use stdClass;

use function App\Utilities\random_string;

class TempOrder extends Model
{
    use HasFactory;

    /* 
        Create Temp order with  payment Intent Id 
        Temp Order deleted after success order place
    */
    public static function saveTempOrder(string $payInt, string $payReference, array $params)
    {
        try {

			

			$reference = random_int(1, 9);

			$tempOrder = new TempOrder();

			$tempOrder->reference = $reference;
			$tempOrder->pay_int = $payInt;
			$tempOrder->pay_reference = $payReference;
			$tempOrder->payload = json_encode($params, JSON_PRETTY_PRINT) ;

			if (!$tempOrder->save()) {
				return false;
			}

			return $reference;
		} catch (Exception $th) {
			
			Log::error($th->getMessage(), ["Line" => $th->getLine(), "file" => $th->getFile()]);
			return false;
		}

    }
}
