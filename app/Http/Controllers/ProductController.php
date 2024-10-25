<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Product;
use App\Models\StripePayment;
use App\Models\User;
use App\Services\Stripe\StripeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Climate\Order;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class ProductController extends SiteController
{
   

    public function index(Request $request)
    {
        $search_text        = $request->input('search_text');
        $category           = $request->input('category');
        $prices             = $request->input('price');
        $short_by           = $request->input('short_by');
        $per_page_limit     = $request->input('per_page_limit');

        $order_by = 'product_title';
        $order    = 'ASC' ;
        $where    = ['is_active' => 1,'is_deleted' => 0];
        $this->per_page =  (!empty($per_page_limit)) ? $per_page_limit : $this->per_page;

        if($short_by && !empty($short_by))
        {
            $order_by = ($short_by == 'price') ? 'price' : $order_by;
        }

        if($category && !empty($category)){
            if($category_id = (new Category())->get_categoryId_byName($category)){
                $where['category_id']  = $category_id;
            }
        }

        $lowPrice = 1;
        $highPrice = 0;
        if($prices && !empty($prices)){
            foreach($prices as $price){
                $priceItems = explode('-',$price);
                if(!empty($priceItems)){
                    if( $lowPrice > $priceItems[0]){
                        $lowPrice = $priceItems[0];
                    }

                    if( $highPrice < $priceItems[1]){
                        $highPrice = $priceItems[1];
                    }
                }
            }
        }

        $query = Product::select('*')->where($where);

        if(!empty($search_text))
        {
            $query = $query->where('product_title','like','%'.$search_text.'%');
        }
        
        if(!empty($lowPrice) && !empty($highPrice)){
            $query = $query->where('price','>',$lowPrice)->where('price','<',$highPrice);
        }

        $products = $query->orderBy($order_by,$order)->paginate($this->per_page)->withQueryString();

        $this->pageData['products']   = $products;
        $this->pageData["categories"] =  (new Category())->get_categories();
        $this->page_title             = 'Products';

        return $this->render_view('product.index');
    }


    public function add_to_cart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            $response = ['success' => false,'message' => 'Failed to add product to cart, refresh, try again'];
            return response()->json($response);
        }

        $productId = $request->input('product_id');
        $quantity  = $request->input('qty');


        // Retrieve existing cart from the cookie or initialize it
        $cart = json_decode(Cookie::get('shopping_cart'), true) ?? [];

        // Add or update the product in the cart
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $quantity; // Increment quantity if product exists
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'qty' => $quantity,
            ];
        }

        // Set the updated cart cookie, with an expiration of 1 day
        $cookie = Cookie::make('shopping_cart', json_encode($cart), time()+31556926); // 1440 minutes = 1 day
       // $cookie = Cookie::make('shopping_cart', json_encode($cart), 1440); // 1440 minutes = 1 day

        // Return a response with the cookie
        $response = ['cart_total_items' => count($cart),'success' => true,'message' => 'Product added in cart successfully'];
        return response()->json($response)->cookie($cookie);


        //$cart = json_decode($request->cookie('e_comm_product_cart', '[]',true));
       // $cart = json_decode(Cookie::get('e_comm_product_cart', '[]'), true);

        // Retrieve the existing cart from the cookie or initialize it
        $cart = json_decode(Cookie::get('e_comm_product_cart'), true);
       

        $found = false;
         foreach ($cart as &$item) {
             if ($item['product_id'] == $productId) {
                 $item['qty'] += $quantity;
                 $quantity = $item['qty']+$quantity;
                 $found = true;
                 break;
             }
         }

         if (!$found) {
           
            $cart[] = ['product_id' => $productId, 'qty' => $quantity];
        }

        

        // Set the cookie with the updated cart, with an expiration of 1 day
        $cookie = Cookie::make('e_comm_product_cart', json_encode($cart), 1440); 

        $response = ['cart_total_items' => count($cart),'success' => true,'message' => 'Product added in cart successfully'];
        return response()->json($response)->cookie($cookie);

    }

    public function product_cart()
    {
        $cart = json_decode(Cookie::get('shopping_cart')) ?? [];

        $cartProducts = [];
        if(!empty($cart)){
            foreach($cart as $cartKey => $cartItem){
                if($product = Product::where(['id'=>$cartItem->product_id])->first()){
                    $product->qty = $cartItem->qty;
                    $cartProducts[$cartKey] = $product;    
                }    
            }
        }

        $this->pageData['product_cart_list']   = $cartProducts;
        $this->page_title                      = 'Cart';

        return $this->render_view('product.cart');
    }

    public function remove_from_cart(Request $request)
    {
         // Retrieve cart from cookies
        $cart = json_decode(Cookie::get('shopping_cart'),true) ?? [];
        
        if(!empty($cart)){
            
            $cartId = $request->input('cart_id');
            unset($cart[$cartId]);

            $cookie = Cookie::make('shopping_cart', json_encode($cart), time()+31556926); // 1440 minutes = 1 day
            // $cookie = Cookie::make('shopping_cart', json_encode($cart), 1440); // 1440 minutes = 1 day

            // Return a response with the cookie
            $response = ['cart_total_items' => count($cart),'success' => true,'message' => 'Product added in cart successfully'];
            return response()->json(true)->cookie($cookie);

        }
    }

    public function checkout(){
        $cart = json_decode(Cookie::get('shopping_cart')) ?? [];

        if(empty($cart)){
            return redirect()->route('products');
        }

        $cartProducts = [];
        if(!empty($cart)){
            foreach($cart as $cartKey => $cartItem){
                if($product = Product::where(['id'=>$cartItem->product_id])->first()){
                    $product->qty = $cartItem->qty;
                    $cartProducts[$cartKey] = $product;    
                }    
            }
        }

        $this->pageData['product_cart_list']   = $cartProducts;
        $this->page_title                      = 'Checkout';
        return $this->render_view('product.checkout');
    }

    public function create_order_charge(Request $request)
    {
        try {

			$validator = Validator::make($request->all(), [
				"products" => ["bail", "array", "required"],
				"sub_total" => ["bail", "numeric", "required"],
				"total_amount" => ["bail", "numeric", "required"],
			]);

            $paymentCharge = StripePayment::create_payment_intent($request->all());
            return response()->json($paymentCharge);

		} catch (Exception $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage(),'data' =>  [] ]);   
			Log::error($th->getMessage(), $th->getTrace());
		}
        
    }

    

    public function processingOrderBooking(Request $request)
    {
        try {
            $response = [
                'status' => false,
                'message' => 'Oops something went wrong. Please try again later. Thank you!',
                'data' => []
            ];

            

            // Validate the request data
            $params = $request->validate([
                'user_type' => 'required|string',
                'payment_intent_id' => 'required|string',
                'products' => 'required|array',
                'sub_total' => 'required|numeric',
                'total_amount' => 'required|numeric',
                'shipping_charge' => 'nullable|numeric',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'contact' => 'required|string|max:15',
                'email' => 'required|email|max:255',
                'address' => 'required|string|max:255',
                'address2' => 'nullable|string|max:255',
                'zipcode' => 'required|string|max:20',
                //'shipping_country' => 'required|string|max:100',
                //'billing_country' => 'required|string|max:100',
                //'promo_code' => 'nullable|string|max:50',
            ]);

            // Check payment intent ID
            if (empty($params['payment_intent_id'])) {
                $response['message'] = 'Oops, payment intent ID not found.';
                return response()->json($response);
            }

            // Verify the payment status
            $paymentService = StripeService::Payment()->getChargeIntent($params['payment_intent_id']);
            if (empty($paymentService) || $paymentService->status !== "succeeded") {
                $response['message'] = 'Oops, payment failed. Please try again.';
                return response()->json($response);
            }

            // Retrieve or create customer data
            $customer = $params['user_type'] === 'register_user' && !empty($customer_Id)
                ? User::find($customer_Id)
                : (object)[
                    'id' => null,
                    'first_name' => $params['first_name'],
                    'last_name' => $params['last_name'],
                    'name' => "{$params['first_name']} {$params['last_name']}",
                    'email' => $params['email'],
                ];

            // Create order
            $order = new Orders();
            $order->fill([
                'buyer_id'            => $customer->id,
                'user_type'           => $params['user_type'] ?? 'guest',
                'order_no'            => 'EC-' . rand(1000, 9999), 
                'sub_total'           => $params['sub_total'],
                'total_amount'        => $params['total_amount'],
                'payment_intent_id'   => $params['payment_intent_id'],
                'shipping_first_name' => $params['first_name'],
                'shipping_last_name'  => $params['last_name'],
                'shipping_name'       => "{$params['first_name']} {$params['last_name']}",
                'shipping_email'      => $params['email'],
                'shipping_contact'    => $params['contact'],
                'shipping_address'    => $params['address'],
                'shipping_address2'   => $params['address2'],
                'shipping_zipcode'    => $params['zipcode'],
            ]);

            if (!$order->save()) {
                // Refund if order save fails
                $refundCharge = StripeService::Payment()->refundChargeIntent($params['payment_intent_id']);

                $response['message'] = 'Failed to save order.';
                return response()->json($response);
            }

            // Process order items
            $subTotal = $finalTotal = 0;
            foreach ($params['products'] as $orderItem) {
                if ($orderItem) {
                    [$productId, $productQty] = explode(":", $orderItem);
                    $product = Product::find($productId);

                    if (!$product) {
                        $refundCharge = StripeService::Payment()->refundChargeIntent($params['payment_intent_id']);
                        $response['message'] = 'Product not found. Please try again later.';
                        return response()->json($response);
                    }

                    $itemTotal   = $product->price * $productQty;
                    $subTotal   += $itemTotal;
                    $finalTotal += $subTotal;

                    // Create order item
                    $orderItemModel = new OrderItems([
                        'order_id' => $order->id,
                        'buyer_id' => $customer->id,
                        'product_id' => $productId,
                        'qty' => $productQty,
                        'item_total' => $itemTotal,
                    ]);
                    $orderItemModel->save();
                }
            }

            // Update the order with the calculated subtotal
            $order->sub_total    = $subTotal;
            $order->total_amount = $finalTotal;
            $order->save();

            $response['status'] = true;
            $response['message'] = 'Payment completed successfully!';
            $response['data'] = ['order_id' => $order->id,'order_no' => $order->order_no];

            Cookie::queue(Cookie::forget('shopping_cart'));

            return response()->json($response);
            

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capture validation errors
            $response = [
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->validator->errors(), // This contains the validation errors
            ];

            return response()->json($response, 422);
        } catch (Exception $th) {
            // Handle general exceptions
            Log::error($th->getMessage(), $th->getTrace());
            return response()->json(['status' => false, 'message' => $th->getMessage()]);
        }
    }

    /** GEt Customer/Buyer user  **/
    public function getCustomer($customer_Id, $user_type)
    {
        if(!empty($customer_Id) && $user_type == 'register_user')
        {
            $Customer = User::where(['id' => $customer_Id])->first();
        }
        else
        {
            $Customer = ['id' => null];
            $Customer = (object) $Customer;
        }

        return $Customer;
    }

    public function success_booking(Request $request){
        $orderInfo = $request->all();
        $this->pageData['orderInfo']   = $orderInfo;
        return $this->render_view('product.success-booking');

    }

   
    




}
