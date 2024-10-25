<?php namespace App\Http\Handlers;

use App\Http\Handlers\Core\HandlersStateManager;
use App\Http\Modules\Core\Modules;


use Exception;
use Illuminate\Support\Facades\Log;






class OrderHandlers
{
	use HandlersStateManager, StoreHandlersHelper;

	protected function _processOrder(string $payInt, string $payref, array $params)
	{
		try {
			$StoreModule = Modules::Stores($this->request);

			$order_payload = [];
			$order_totalAmount = 0;
			$Products = [];

			/* Get the vendors */
			$Vendors = [];

			foreach ($params["products"] as $product_cart_key=>$orderItem) {
				if ($orderItem) {
					/* now extract vendor and user info */
					$orderExtraction = explode(":", $orderItem);
					/* the persons contains index 0 -> client (Customer Id) and index 1 -> Vendor (Supplier Id)
					 while the index -> is product infor */

					$ClientId = $orderExtraction[0];
					$VendorId = $orderExtraction[1];

					$productShippingCharge = !empty($orderExtraction[3]) ? (float) $orderExtraction[3] : 0;

					$productPrice = !empty($orderExtraction[4]) ? (float) $orderExtraction[4] : 0;
					

					/* Validate customer and vendor id before push to vendors list */
					if (empty($VendorId)) {
						return $this->raise("Oops the provided product information was invalid and couldn't be processed.");
					}
					
					/* if (empty($ClientId) || empty($VendorId)) {
						return $this->raise("Oops the provided product information was invalid and couldn't be processed.");
					} */

					/* Push the vendor for this product to the vendor list  */
					array_push($Vendors, $VendorId);

					/* get The client and vendor */
					if(!empty($ClientId) && $params['user_type']=='register_user')
					{
						$Client = Modules::Customer($this->request)->__getCustomer($ClientId);
					}
					else
					{
						$Client = [
									'id' => null,
									'first_name' => $params['first_name'],
									'last_name' => $params['last_name'],
									'contact' => $params['contact'],
									'email' => $params['email'],
								
						];
						
						$Client = (object) $Client;
					}
					
					/* if (!($Client = Modules::Customer($this->request)->__getCustomer($ClientId))) {
						return $this->raise("Invalid customer Id");
					} */

					if (!($Vendor = Modules::Vendors($this->request)->__getVendor($VendorId))) {
						return $this->raise("Invalid vendor id");
					}

					/* processs the product information  */
					$OrderedProduct = explode(",", $orderExtraction[2]);
					$productId = $OrderedProduct[0];
					$quantity = $OrderedProduct[1];
					
					$customisation_text_code = null;
					if(isset($params['customisation_text_code']))
					{
						if(isset($params['customisation_text_code'][$productId.'_'.$product_cart_key]))
						{
							$customisationCode = $params['customisation_text_code'][$productId.'_'.$product_cart_key];
							if(!empty($customisationCode))
							{
								$customisation_text_code =  (!empty($customisationCode)) ? $customisationCode : null;
							}
						}
					}
					
					$customisationDetails = '';
					if(isset($params['customisation_details']))
					{
						if(isset($params['customisation_details'][$productId.'_'.$product_cart_key]))
						{
							$customisation_details = $params['customisation_details'][$productId.'_'.$product_cart_key];
							if(!empty($customisation_details))
							{
								$customisationDetails =  (!empty($customisation_details)) ? json_encode($customisation_details,true) : null;
							}
						}
					}
					
					$customisation_total_amount = 0;
					if(isset($params['customisation_total_amount']))
					{
						
						if(isset($params['customisation_total_amount'][$productId.'_'.$product_cart_key]))
						{
							$customisationAmount = $params['customisation_total_amount'][$productId.'_'.$product_cart_key];
							if(!empty($customisationAmount))
							{
								$customisation_total_amount =  (!empty($customisationAmount)) ? $customisationAmount : 0;
							}
						}
					}
					
					$customisationFile = null;
					if(isset($params['customisation_file']))
					{
					
						if(isset($params['customisation_file'][$productId.'_'.$product_cart_key]))
						{
							$customisation_files = $params['customisation_file'][$productId.'_'.$product_cart_key];
							if(!empty($customisation_files))
							{
								$customisationFile =  (!empty($customisation_files)) ? $customisation_files : null;
							}
						}
					}
					
					// set product variation id
					$variation_id = null;
					if(isset($params['product_variation_id']))
					{
						if(isset($params['product_variation_id'][$product_cart_key]))
						$variation_id = (!empty($params['product_variation_id'][$product_cart_key])) ? $params['product_variation_id'][$product_cart_key] : null;
					}
					
					// set $product variation details
					$variation_details = null;
					if(isset($params['product_variation_details']))
					{
						if(isset($params['product_variation_details'][$product_cart_key]))
						$variation_details =  (!empty($params['product_variation_details'][$product_cart_key])) ? json_encode($params['product_variation_details'][$product_cart_key],true) : null;
					}
					
					// set $product variation details
					$need_by_date = null;
					if(isset($params['product_certain_date']))
					{
						$need_by_date = (!empty($params['product_certain_date'][$product_cart_key])) ? $params['product_certain_date'][$product_cart_key] : null;
					}
					
					// set shipping method name
					$shipping_method_name = null;
					if(isset($params['shipping_method_name']))
					{
						$shipping_method_name = (!empty($params['shipping_method_name'][$product_cart_key])) ? $params['shipping_method_name'][$product_cart_key] : null;
					}
					
					$product_tax_price      = 0;
					$product_tax_percentage = 0;
					if(isset($params['product_tax_price']))
					{
						if(isset($params['product_tax_price'][$productId.'_'.$product_cart_key]))
						{
							$productTaxPrice = $params['product_tax_price'][$productId.'_'.$product_cart_key];
							if(!empty($productTaxPrice))
							{
								$product_tax_price =  isset($productTaxPrice) ? $productTaxPrice : 0;
							}
						}
						
						if(isset($params['product_tax_percentage'][$productId.'_'.$product_cart_key]))
						{
							$productTaxPercentage = $params['product_tax_percentage'][$productId.'_'.$product_cart_key];
							if(!empty($productTaxPercentage))
							{
								$product_tax_percentage =  isset($productTaxPercentage) ? $productTaxPercentage : 0;
							}
						}
					}
					
					$is_gift_available = false;
					$gift_wrap_price = 0;
					if(isset($params['is_gift_available']))
					{
						
						if(isset($params['is_gift_available'][$productId.'_'.$product_cart_key]))
						{
							$isGiftAvailable = $params['is_gift_available'][$productId.'_'.$product_cart_key];
							
							if(!empty($isGiftAvailable))
							{
								$is_gift_available = (!empty($isGiftAvailable)) ? 1 : NULL;
								$gift_wrap_price = (!empty($isGiftAvailable)) ? $isGiftAvailable : NULL;
							}
						}
					}

					if (empty($productId) || empty($quantity)) {
						return $this->raise("Couldn't process product ordered information.");
					}

					/* Get the product information */
					if (!($Product = Modules::Stores($this->request)->__getProduct($productId))) {
						return $this->raise("Couldn't retrieve product information.");
					}

					/* Add this properties to product object to  */
					$Product->ordered_qty 				  = $quantity;
					$Product->shipping_charge 			  = $productShippingCharge;
					$Product->product_customisation_file  = $customisationFile;
					$Product->customisation_text_code     = $customisation_text_code;
					$Product->customisation_total_amount  = $customisation_total_amount;
					$Product->customisation_details       = $customisationDetails;
					$Product->product_tax_price    		  = $product_tax_price;
					$Product->product_tax_percentage      = $product_tax_percentage;
					$Product->variation_id      		  = $variation_id;
					$Product->variation_details      	  = $variation_details;
					
					$Product->is_gift_available      	  = $is_gift_available;
					$Product->gift_wrap_price      		  = $gift_wrap_price;
					
					$Product->site_url = config("app.site_url");

					$Product->price = !empty($productPrice) ? $productPrice : $Product->price;

					if (!empty($customisationFile)) {
						$Product->product_customisation_file = $customisationFile;//config("app.dev_url") . "/uploads/product/customisation_file/" . $customisationFile;
					}

					/* push product item to products */
					array_push($Products, $Product);

					/* Now that we have the product, vendor and client info. Lets process the orders  */
					$totalAmount = 0;
					$subtotal = 0;
					$charges = 0;

					$itemTotal = $Product->price * $quantity;
					$subtotal += $itemTotal;
					$charges += $productShippingCharge;

					$totalAmount = $subtotal + $charges + $customisation_total_amount + $gift_wrap_price;
					
					/* get product tax price */
					if(!empty($product_tax_percentage))
					{
						$itemTotalForTax = $totalAmount;
						$product_tax_price = ($itemTotalForTax*$product_tax_percentage)/100;
					}
					
					/* plus product tax price in total amount */ 
					$totalAmount = $totalAmount+$product_tax_price;
					
					/* amount that customer will be charged  */
					$order_totalAmount += $totalAmount;

					/* create shipping order  */
					$shippingData["shipping_first_name"] = (!empty($params["shipping_first_name"])) ? $params["shipping_first_name"] : $params["first_name"];
					$shippingData["shipping_last_name"]  = (!empty($params["shipping_last_name"])) ? $params["shipping_last_name"] : $params["last_name"];
					
					$shippingData["name"] = !empty($params["shipping_first_name"]) && !empty($params["shipping_last_name"]) ? "{$params["shipping_first_name"]} {$params["shipping_last_name"]}" : "{$params["first_name"]} {$params["last_name"]}";

					$shippingData["user_type"] 	  = $params['user_type'];
					$shippingData["customer"] 	  = $Client->id;
					$shippingData["vendor"]   	  = $Vendor->id;
					$shippingData["subtotal"] 	  = $subtotal;
					$shippingData["charges"]  	  = $charges;
					$shippingData["total"]    	  = $totalAmount;
					$shippingData["contact"]  	  = !empty($params["shipping_contact"]) ? $params["shipping_contact"] : $params["contact"];
					$shippingData["address"]  	  = !empty($params["shipping_address"]) ? $params["shipping_address"] : $params["address"];
					$shippingData["address2"] 	  = !empty($params["shipping_address2"]) ? $params["shipping_address2"] : $params["address2"];
					$shippingData["zipcode"]  	  = !empty($params["shipping_zipcode"]) ? $params["shipping_zipcode"] : $params["zipcode"];
					$shippingData["email"]    	  = !empty($params["shipping_email"]) ? $params["shipping_email"] : $params["email"];
					$shippingData["billing_country"] = !empty($params["billing_country"]) ? $params["billing_country"] : null;
					$shippingData["shipping_country"] = !empty($params["shipping_country"]) ? $params["shipping_country"] : null;
					$shippingData["note"]     	  = $params["gift_note"];
					$shippingData["info"]         = $params["additional_information"];
					$shippingData["promo_code"]   = $params["promo_code"];
					//$shippingData["certain_date"] = (!empty($params["certain_date"])) ? $params["certain_date"] : NULL;
					
					// set param fro product customisation
					$shippingData["product_customisation_file"] = $customisationFile;
					$shippingData["customisation_text_code"] 	= $customisation_text_code;
					$shippingData["customisation_total_amount"] = $customisation_total_amount;
					$shippingData["customisation_details"] 	    = $customisationDetails;
					
					// set product order tax amount and tax percentage
					$shippingData["tax_amount"] 		= $product_tax_price;
					$shippingData["tax"] 				= $product_tax_percentage;
					
					$shippingData["is_gift_available"]  = $is_gift_available;
					$shippingData["gift_wrap_price"] 	= $gift_wrap_price;
					

					if (!($shippingOrder = $StoreModule->__addShippingOrder($shippingData))) {
						return $this->raise("Couldn't not create shipping information.");
					}

					/* push payload data */
					array_push($order_payload, [$shippingOrder->id => $shippingOrder->order_no]);

					/* create customer order record. */
					if (
						!Modules::Stores($this->request)->__addOrderItem($shippingOrder->id, $Client->id, [
							"product" 			   => $Product->id,
							"price" 			   => $Product->price,
							"total" 			   => $totalAmount,
							"quantity" 			   => $quantity,
							"product_tax" 		   => $product_tax_percentage,
							"product_tax_price"    => $product_tax_price,
							"item_shipping_charge" => $productShippingCharge,
							"shipping_method_name" => (!empty($shipping_method_name)) ? $shipping_method_name : null,
							"customisation_file"   => (!empty($customisationFile)) ? $customisationFile : null,
							"customisation_amount" => (!empty($customisation_total_amount)) ? $customisation_total_amount : null,
							"customisation_details" => (!empty($customisationDetails)) ? $customisationDetails : null,
							"variation_id" 		   => (!empty($variation_id)) ? $variation_id : null,
							"variation_details"    => (!empty($variation_details)) ? $variation_details : null,
							"is_gift_wrap" 		   => (!empty($is_gift_available)) ? $is_gift_available : null,
							"gift_wrap_price" 	   => (!empty($gift_wrap_price)) ? $gift_wrap_price : null,
							"need_by_date" 	   => (!empty($need_by_date)) ? $need_by_date : null,
								
						])
					) {
						return $this->raise("Your order succeeded but couln't not record your copy of the order.");
					}

					/* create order item list  */
					$StoreModule->__addOrderList($Vendor->id, $shippingOrder->id, $shippingOrder->order_no, $payref);
				} else {
					return $this->raise("Couldn't process order information.");
				}
			}

			/**
			 * DEVELOPER NOTES:
			 * -----------------
			 *  In this thread we're creating a product order and generating a payment secret
			 * That client will use to complete transaction. The order is not a successful payment.
			 * However this is just a record that when user has completed payment the db record will be updated
			 * to paid and the payment will be recorded approprately into the `product_order` and `product_order_item`
			 * respectively.
			 */

			/* success then, get client details */
			$data["secret"] = $payInt;
			$data["payload"] = $order_payload;
			$data["group"] = "SUPPLIER_ORDER";
			$data["reference"] = $payref;

			if (!($OrderId = Modules::Stores($this->request, $data)->__addOrder())) {
				return $this->raise("Couldn't complete order request.");
			} else {
				/* Get the newly added client order and use its reference to get the product ordered */
				$Order = Modules::Stores($this->request)->__getOrder($OrderId);
				$OrderedProduct = Modules::Stores($this->request)->__getProductOrdered($Order->order_reference);
			}

			/* send client order email */
			Mail::to($params["shipping_email"] ?? $params["email"])->send(new NewOrder($params, $Products, $OrderedProduct->order_no));

			/* Filter any duplicate vendor id. */
			$FilteredVendors = array_unique($Vendors);

			/* loop through each vendor to get the vendor and production information */
			foreach ($FilteredVendors as $vendorid) {
				if (!($Vendor = Modules::Vendors($this->request)->__getVendor($vendorid))) {
					return $this->raise("Couldn't verify supplier information.");
				}

				/* customer ordered products */
				$CustomerOrderedProducts = [];
				$Client = null;
				foreach ($params["products"] as $product_cart_key=>$orderItem) {
					if ($orderItem) {
						/* now extract vendor and user info */
						$orderExtraction = explode(":", $orderItem);

						$ClientId = $orderExtraction[0];
						$VendorId = $orderExtraction[1];
						$productShippingCharge = $orderExtraction[3];
						//$productPrice = (!empty($orderExtraction[4])) ? $orderExtraction[4] : NULL;
						//$customisationFile = (!empty($orderExtraction[5])) ? $orderExtraction[5] : NULL;

						$productPrice = $orderExtraction[4];
						
						//$customisationFile = $orderExtraction[5];

						/* get The client and vendor */
						if(!empty($ClientId) && $params['user_type']=='register_user')
						{
							$Client = Modules::Customer($this->request)->__getCustomer($ClientId);
						}
						else
						{
							$Client = new Customer();
							$Client->id = null;
							$Client->first_name = $params['first_name'];
							$Client->last_name = $params['last_name'];
							$Client->contact = $params['contact'];
							$Client->email = $params['email'];
						}
						
						/* if (!($Client = Modules::Customer($this->request)->__getCustomer($ClientId))) {
							return $this->raise("Couldn't verify client information.");
						} */

						if ($VendorId == $vendorid) {
							/* processs the product information  */
							$OrderedProduct = explode(",", $orderExtraction[2]);
							$productId = $OrderedProduct[0];
							$quantity = $OrderedProduct[1];

							if (empty($productId) || empty($quantity)) {
								return $this->raise("Couldn't process product ordered information.");
							}

							/* Get the product information */
							if (!($Product = Modules::Stores($this->request)->__getProduct($productId))) {
								return $this->raise("Couldn't retrieve product information.");
							}
							
						$customisation_text_code = null;
						if(isset($params['customisation_text_code']))
						{
							if(isset($params['customisation_text_code'][$productId.'_'.$product_cart_key]))
							{
								$customisationCode = $params['customisation_text_code'][$productId.'_'.$product_cart_key];
								if(!empty($customisationCode))
								{
									$customisation_text_code =  (!empty($customisationCode)) ? $customisationCode : null;
								}
							}
							
							/* if(!empty($params['customisation_text_code']))
							{
								$customisationCode = $params['customisation_text_code'];
								$customisation_text_code =  isset($customisationCode[$productId]) ? $customisationCode[$productId] : null;
							} */
						}
						
						$customisationDetails = '';
						if(isset($params['customisation_details']))
						{
							if(isset($params['customisation_details'][$productId.'_'.$product_cart_key]))
							{
								$customisation_details = $params['customisation_details'][$productId.'_'.$product_cart_key];
								if(!empty($customisation_details))
								{
									$customisationDetails =  (!empty($customisation_details)) ? json_encode($customisation_details,true) : null;
								}
							}
							
							/*
							if(!empty($params['customisation_details']))
							{
								$customisation_details = $params['customisation_details'];
								$customisationDetails =  isset($customisation_details[$productId]) ? $customisation_details[$productId] : '';
								$customisationDetails =  (!empty($customisationDetails)) ? json_encode($customisationDetails,true) : null;
							}
							*/
						}
						
						$customisation_total_amount = 0;
						if(isset($params['customisation_total_amount']))
						{
							
							if(isset($params['customisation_total_amount'][$productId.'_'.$product_cart_key]))
							{
								$customisationAmount = $params['customisation_total_amount'][$productId.'_'.$product_cart_key];
								if(!empty($customisationAmount))
								{
									$customisation_total_amount =  (!empty($customisationAmount)) ? $customisationAmount : 0;
								}
							}
							
							/*
							if(!empty($params['customisation_total_amount']))
							{
								$customisationAmount = $params['customisation_total_amount'];
								$customisation_total_amount =  isset($customisationAmount[$productId]) ? $customisationAmount[$productId] : 0;
							}
							
							*/
						}
						
						$customisationFile = null;
						if(isset($params['customisation_file']))
						{
						
							if(isset($params['customisation_file'][$productId.'_'.$product_cart_key]))
							{
								$customisation_files = $params['customisation_file'][$productId.'_'.$product_cart_key];
								if(!empty($customisation_files))
								{
									$customisationFile =  (!empty($customisation_files)) ? $customisation_files : null;
								}
							}
						}
							
						$product_tax_price      = 0;
						$product_tax_percentage = 0;
						if(isset($params['product_tax_price']))
						{
							if(isset($params['product_tax_price'][$productId.'_'.$product_cart_key]))
							{
								$productTaxPrice = $params['product_tax_price'][$productId.'_'.$product_cart_key];
								if(!empty($productTaxPrice))
								{
									$product_tax_price =  isset($productTaxPrice) ? $productTaxPrice : 0;
								}
							}
							
							if(isset($params['product_tax_percentage'][$productId.'_'.$product_cart_key]))
							{
								$productTaxPercentage = $params['product_tax_percentage'][$productId.'_'.$product_cart_key];
								if(!empty($productTaxPercentage))
								{
									$product_tax_percentage =  isset($productTaxPercentage) ? $productTaxPercentage : 0;
								}
							}
								
								/* if(!empty($params['product_tax_price']))
								{
									$productTaxAmount = $params['product_tax_price'];
									$product_tax_price =  isset($productTaxAmount[$productId]) ? $productTaxAmount[$productId] : 0;
								}
							
								if(!empty($params['product_tax_percentage']))
								{
									$productTaxPercentage   = $params['product_tax_percentage'];
									$product_tax_percentage =  isset($productTaxPercentage[$productId]) ? $productTaxPercentage[$productId] : 0;
								} */
							}
							
							$is_gift_available = false;
							$gift_wrap_price = 0;
							if(isset($params['is_gift_available']))
							{
								
								if(isset($params['is_gift_available'][$productId.'_'.$product_cart_key]))
								{
									$isGiftAvailable = $params['is_gift_available'][$productId.'_'.$product_cart_key];
									
									if(!empty($isGiftAvailable))
									{
										$is_gift_available = (!empty($isGiftAvailable)) ? 1 : NULL;
										$gift_wrap_price = (!empty($isGiftAvailable)) ? $isGiftAvailable : NULL;
									}
								}
							}
							
							// set product variation id
							$variation_id = null;
							if(isset($params['product_variation_id']))
							{
								if(isset($params['product_variation_id'][$product_cart_key]))
									$variation_id = (!empty($params['product_variation_id'][$product_cart_key])) ? $params['product_variation_id'][$product_cart_key] : null;
							}
								
							// set $product variation details
							$variation_details = null;
							if(isset($params['product_variation_details']))
							{
								if(isset($params['product_variation_details'][$product_cart_key]))
									$variation_details =  (!empty($params['product_variation_details'][$product_cart_key])) ? json_encode($params['product_variation_details'][$product_cart_key],true) : null;
							}

							$Product->ordered_qty 				  = $quantity;
							$Product->shipping_charge 			  = $productShippingCharge;
							$Product->product_customisation_file  = null;
							$Product->customisation_text_code     = $customisation_text_code;
							$Product->customisation_total_amount  = $customisation_total_amount;
							$Product->customisation_details       = $customisationDetails;
							$Product->product_tax_price    		  = $product_tax_price;
							$Product->product_tax_percentage      = $product_tax_percentage;
							
							$Product->is_gift_available      	  = $is_gift_available;
							$Product->gift_wrap_price      		  = $gift_wrap_price;
							
							$Product->variation_id      		  = $variation_id;
							$Product->variation_details      	  = $variation_details;
							

							$Product->price = !empty($productPrice) ? $productPrice : $Product->price;

							if (!empty($customisationFile)) {
								 $Product->product_customisation_file = $customisationFile;//config("app.site_url") . "/uploads/product/customisation_file/" . $customisationFile;
							}

							/* get order */
							if (!($ClientOrder = Modules::Stores($this->request)->__getOrder($OrderId))) {
								return $this->raise("Sorry couldn't process order request.");
							}

							if (!($Order = Modules::Stores($this->request)->__getProductOrdered($ClientOrder->order_reference))) {
								return $this->raise("Invalid order reference");
							}
							
							$Product->site_url = config("app.site_url");

							$Product->action_url = config("app.site_url") . "/store-manager/login";
							$Product->dashboard_url = config("app.site_url") . "/store-manager/login";

							array_push($CustomerOrderedProducts, $Product);
						}
					}
				}

				$Vendor->dashboard_url = config("app.site_url") . "/auth/login/orderStatus?status=2&oId={$Order->id}&sId={$Order->vendor_id}&token={$Order->token}";
				$Vendor->action_url = config("app.site_url") . "/store-manager/login";
				
				/* if($params['user_type']=='guest_user')
				{
					$Client = (array)$Client;
					
					$Client['certain_date'] = (!empty($params["certain_date"])) ? $params["certain_date"] : '';
					$Client['additional_information'] = (!empty($Order->additional_information)) ? $Order->additional_information : '';
					$Client['gift_note'] = (!empty($Order->gift_note)) ? $Order->gift_note : '';
					
					$Client = (object)$Client;
				}
				else{
					$Client->certain_date = (!empty($params["certain_date"])) ? $params["certain_date"] : '';
					$Client->additional_information = (!empty($Order->additional_information)) ? $Order->additional_information : '';
					$Client->gift_note = (!empty($Order->gift_note)) ? $Order->gift_note : '';
				} */
				
				$Client->certain_date = null;
				$Client->additional_information = (!empty($Order->additional_information)) ? $Order->additional_information : '';
				$Client->gift_note = (!empty($Order->gift_note)) ? $Order->gift_note : '';
				
				

				Mail::to($Vendor->email)->send(new NewCustomerOrder($Vendor, $Client, $CustomerOrderedProducts));
				
				Mail::to(config('mail.admin'))->send(new NewCustomerOrderToAdmin($Vendor, $Client, $CustomerOrderedProducts));
			}

			return $this->response($OrderId, "Order created successfully!");
		} catch (Exception $th) {
			Log::error($th->getMessage(), ["Line" => $th->getLine(), "file" => $th->getFile()]);
			return $this->raise();
		}
	}
	/**
	 * place a new product order request
	 *
	 * @return $this
	 */
	public function _placeOrder()
	{
		try {
			$params = $this->request->all(["products", "sub_total", "total_amount", "shipping_charge", "additional_information", "address", "address2", "compnay_name", "contact", "email", "first_name", "last_name", "gift_note", "shipping_address", "shipping_address2", "shipping_charge", "shipping_contact", "shipping_email", "shipping_first_name", "shipping_last_name", "shipping_zipcode","shipping_country", "zipcode", "promo_code",'customisation_file','customisation_text_code','customisation_total_amount','customisation_details','billing_country','product_tax_price','product_tax_percentage','taxes_charge','is_gift_available','user_type','product_variation_id','product_variation_details','product_certain_date','shipping_method_name']);
			
			$customisationDetails = [];
			if(!empty($params['customisation_details']))
			{
				//$params['customisation_details'] = html_entity_decode($params['customisation_details']);
				
				foreach ($params['customisation_details'] as $key=>$customisation)
				{
					if(!empty($customisation))
					{
						$customisation = html_entity_decode($customisation);
						$customisationDetails[$key] = (!empty($customisation)) ? json_decode($customisation,true) : [];
					}
					else
					{
						$customisationDetails[$key] = $customisation;
					}
					
				}
			}
			
			$params['customisation_details'] = $customisationDetails;
			
			$product_variation_details = [];
			if(!empty($params['product_variation_details']))
			{
				//$params['customisation_details'] = html_entity_decode($params['customisation_details']);
			
				foreach ($params['product_variation_details'] as $key=>$variationDetails)
				{
					if(!empty($variationDetails))
					{
						$variation = html_entity_decode($variationDetails);
						$product_variation_details[$key] = (!empty($variation)) ? json_decode($variation,true) : [];
					}
					else
					{
						$product_variation_details[$key] = $variationDetails;
					}
						
				}
			}
				
			$params['product_variation_details'] = $product_variation_details;
			
			//$params['customisation_details'] = (!empty($params['customisation_details'])) ? json_decode($params['customisation_details'][93]) : '';
			//Log::info($params);
			
			/* create payment secret first  */
			$paymentService = StripeService::Payment()->chargeIntent($params["total_amount"], "Cient order.", "SUPPLIER_ORDER", $params["products"]);
			if (!$paymentService->STATE) {
				return $this->raise("couldn't complete order at the moment, please try again later!");
			}

			if (!($orderId = Modules::Stores($this->request)->__saveTempOrder($paymentService->RESPONSE->client_secret, $paymentService->RESPONSE->id, $params))) {
				return $this->raise("Oops! Couldn't create order.");
			}

			return $this->response(
				[
					"client_secret" => $paymentService->RESPONSE->client_secret,
					"order_id" => $orderId,
					"config" => [
						"pub_key" => config("stripe.public"),
					],
				],
				"Please complete your order."
			);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise();
		}
	}

	/**
	 * Verify customer order
	 *
	 * @return $this
	 */
	public function _verifyOrderPayment(string $orderId)
	{
		try {
			$params = $this->request->only(["order", "payment_intent", "payment_intent_client_secret", "redirect_status"]);

			/* get the payment informatin  */
			if (!($Order = Modules::Stores($this->request)->__getTempOrder($orderId))) {
				return $this->raise("Invalid Payment");
			}

			/* verify the status of the payment  */
			$paymentService = StripeService::Payment()->getChargeIntent($Order->pay_reference);
			if (!$paymentService->STATE) {
				// delete order if payment is faild
				Modules::Stores($this->request)->__deleteTempOrder($orderId);

				return $this->raise("Oops something went wrong. Please try again later thank you!");
			}

			/* verify the payment status  */
			if (!($params["redirect_status"] === $paymentService->RESPONSE->status) && $paymentService->RESPONSE->status !== "succeeded") {
				/*  add logic for delete order and order items */

				// delete order if payment is faild
				Modules::Stores($this->request)->__deleteTempOrder($orderId);
				return $this->raise("Your payment was not successfully completed!");
			}

			/* check if order was completed  */
			if (Modules::Stores($this->request)->__isOrderPaid($Order->pay_reference)) {
				return $this->raise("Sorry this order has been completed.");
			}

			$response = null;
			if (!Modules::Stores($this->request)->__hasOrder($Order->pay_reference)) {
				/* Create Payment  */
				$handler = $this->_processOrder($Order->pay_int, $Order->pay_reference, $Order->payload);

				if (!$handler->STATE) {
					return $this->raise("Oops! Order couldn't be processed.");
				}

				/* Remove temp data from table */
				Modules::Stores($this->request)->__deleteTempOrder($orderId);

				// update order status
				$order_id = Modules::Stores($this->request)->__orderStatusPaid($handler->RESPONSE);
				if (!Modules::Stores($this->request)->__updateShippingOrder($order_id, ["payment_status" => 2, "token" => $Order->pay_reference])) {
					return $this->raise("Unable to update payment status.");
				}

				$response = $handler->RESPONSE;
			}

			return $this->response(["order" => $response], "Payment completed successfully!");
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());
			return $this->raise();
		}
	}

	/**
	 * Supplier Accept order and account get credited with the funds
	 * that was helt with legacy events
	 *
	 * @return $this
	 */
	public function _acceptOrder(bool $isCloned = false)
	{
		try {
			
			$params = $this->request->only(["orderId","shipping_track_no","tracking_company_name","shipping_track_message","is_shipping_track_no"]);
			$User = $this->request->user();
			
			
			
			$StoreModule = Modules::Stores($this->request);

			/* get the order information  */
			if (!($Order = $StoreModule->__getShippingOrder($params["orderId"]))) {
				return $this->raise("Order couldn't be processed");
			}

			/* Get the product item  */
			if (!($ProductItem = $StoreModule->__getOrderItem($Order->id))) {
				return $this->raise("Invalid order. Product item Couldn't retrieved order Items");
			}

			/* Get the product its self  */
			if (!($Product = $StoreModule->__getProduct($ProductItem->product_id))) {
				return $this->raise("Invalid Product Id. Unable to retrieved product.");
			}

			/* Add the required product info */
			$Product->qty = $ProductItem->qty;
			
			$Product->variation_id = $ProductItem->variation_id;
			
			$Product->variation_details = (!empty($ProductItem->variation_details)) ? json_decode($ProductItem->variation_details) : []; 
			
			$Order->tracking_company_name = $params['tracking_company_name'];
			
			$Order->shipping_track_no = $params['shipping_track_no'];
			
			$Order->is_shipping_track_no = $params['is_shipping_track_no'];
			
			$Order->shipping_track_message = $params['shipping_track_message'];
			

			/* sub_total = (item_price * qty) */
			$Product->sub_total = $ProductItem->item_price * $ProductItem->qty;
			
			/* product tax price */
			$Product->product_tax_price = (!empty($ProductItem->product_tax_price)) ? $ProductItem->product_tax_price : 0;
			
			/* product customisation price */
			$Product->customisation_total_amount = (!empty($Order->customisation_total_amount)) ? $Order->customisation_total_amount : 0;
			
			/* product gift wrap price */
			$Product->gift_wrap_price = (!empty($Order->gift_wrap_price)) ? $Order->gift_wrap_price : 0;
				
			
			/* total = sub_total + charges */
			$Product->total = $Product->sub_total + $ProductItem->item_shipping_charge + $Product->product_tax_price + $Product->customisation_total_amount + $Product->gift_wrap_price;

			$Product->shipping_charges = $ProductItem->item_shipping_charge;

			$Product->price = !empty($ProductItem->item_price) ? $ProductItem->item_price : $Product->price;

			/*
            START: IMPORTANT NOTE:
            -------------------------
            Make sure the payment status is paid before attempting to reject or accept order.
            */
			if ($OrderListItem = $StoreModule->__getOrderListItem($Order->order_no, $Order->id)) {
				if (!$StoreModule->__isOrderPaid($OrderListItem->payment_intent)) {
					return $this->raise("This order was not completed by client and can not be ACCEPTED.");
				}
			} else {
				/* check if the order is an alaise order  */
				if (!$StoreModule->__isAlaiseOrder($params["orderId"])) {
					return $this->raise("Couldn't fetch order list item.");
				}

				/* clone product  */
				return $this->_cloneAlaiseProductOrdered();
			}
			/* END: IMPORTANT NOTE */

			/* make sure order has not already been accepted */
			if ($Order->order_status === 3) {
				return $this->raise("Order already accepted an cannot be altered.");
			} elseif ($Order->order_status === 5) {
				return $this->raise("Order alrady canceled");
			}

			/* Calculate the amount to be credited to the supplier balance */
			//$charge = $ProductItem->item_shipping_charge;
			$charge = $ProductItem->item_shipping_charge + $Product->customisation_total_amount + $Product->product_tax_price + $Product->gift_wrap_price;
			/* retrieve the other total amount. */

			/* calculate legacy events commision before crediting the payout to vendor*/
			if (!($Commission = Modules::Stores($this->request)->getVendorCommission($Order->vendor_id))) {
				return $this->raise("Invalid commission for product.");
			}

			$commisionToken = !empty($Commission->commission) ? $Commission->commission : 10;

			/* calculate the percentage of the amount using the commission value. */
			$commisionAmount = PercentageCalculator::PercentageOfX($commisionToken, $Product->sub_total);

			/* Use the subtal so that we only take charges from the product not with shipping charges */
			$creditAmount = $Product->sub_total - $commisionAmount;

			/* This final amount is the total amount after extract commission value plus he shipping amount if any for shipping. */
			$finalCreditAmount = $creditAmount + $charge;

			/* initiate a credit reques */
			$stripeService = StripeService::Payment()->transferToAccount($User->account_id, $finalCreditAmount, !$isCloned ? "SUPPLIER_ORDER" : null);
			if (!$stripeService->STATE) {
				return $this->raise("Ops! Something bad went wrong, we couldn't credit your account at the moment.");
			}

			/* update shipping order status incase alaise code fails to updated the status
			 inother to avoid funds losses  */
			
			$updateOrderData['order_status']   		   = 3;
			$updateOrderData['payment_status'] 		   = 2;
			$updateOrderData['is_shipping_track_no']   = $params['is_shipping_track_no'];
			$updateOrderData['tracking_company_name']  = $params['tracking_company_name'];
			$updateOrderData['shipping_track_no']      = $params['shipping_track_no'];
			$updateOrderData['shipping_track_message'] = $params['shipping_track_message'];
			
			if (!$StoreModule->__updateShippingOrder($Order->id, $updateOrderData)) {
				return $this->raise("Order acceptance couldn't be processed");
			}

			Mail::to($Order->shipping_email)->send(new OrderShipped($Order, $Product));
			
			Mail::to(config('mail.admin'))->send(new OrderShipped($Order, $Product));

			return $this->response(
				[
					"credited_amount" => $finalCreditAmount,
				],
				"Congratulations! You have been credited $finalCreditAmount which has now been added to your balance"	
			);
			// "Congratulations! You have been credited with $finalCreditAmount to your balance payable weekly on every Monday's. Thank you for choosing Legacy Events."
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise();
		}
	}

	/**
	 * Supplier reject order and order amount get refunded to customer
	 * @return $this
	 */
	public function _rejectOrder()
	{
		try {
			$params = $this->request->only("orderId");
			$StoreModule = Modules::Stores($this->request);

			/* get the order information  */
			if (!($Order = $StoreModule->__getShippingOrder($params["orderId"]))) {
				return $this->raise("Order couldn't be processed");
			}

			/* Get the product item  */
			if (!($ProductItem = $StoreModule->__getOrderItem($Order->id))) {
				return $this->raise("Invalid order. Product item Couldn't retrieved order Items");
			}

			/* Get the product its self  */
			if (!($Product = $StoreModule->__getProduct($ProductItem->product_id))) {
				return $this->raise("Invalid Product Id. Unable to retrieved product.");
			}

			/* Add the required product info */
			$Product->qty = $ProductItem->qty;
			
			$Product->variation_id = $ProductItem->variation_id;
				
			$Product->variation_details = (!empty($ProductItem->variation_details)) ? json_decode($ProductItem->variation_details) : [];
				

			/* sub_total = (item_price * qty) */
			$Product->sub_total = $ProductItem->item_price * $ProductItem->qty;
			
			/* product tax price */
			$Product->product_tax_price = (!empty($ProductItem->product_tax_price)) ? $ProductItem->product_tax_price : 0;
				
			
			/* total = sub_total + charges */
			$Product->total = $Product->sub_total + $ProductItem->item_shipping_charge + $Product->product_tax_price;

			$Product->shipping_charges = $ProductItem->item_shipping_charge;

			$Product->price = !empty($ProductItem->item_price) ? $ProductItem->item_price : $Product->price;

			/* START: IMPORTANT NOTE: Make sure the payment status is paid before attempting to either reject or accept order. */
			if ($OrderListItem = $StoreModule->__getOrderListItem($Order->order_no, $Order->id)) {
				if (!$StoreModule->__isOrderPaid($OrderListItem->payment_intent)) {
					return $this->raise("This order was not completed by client and can not be CANCELED / REFUNDED.");
				}
			} else {
				/* check if the order is an alaise order  */
				if (!$StoreModule->__isAlaiseOrder($params["orderId"])) {
					return $this->raise("Couldn't fetch order list item.");
				}

				/* clone product  */
				return $this->_cloneAlaiseProductOrdered();
			}
			/* END: IMPORTANT NOTE */

			/* get order list item */
			if (!($OrderListItem = $StoreModule->__getOrderListItem($Order->order_no, $Order->id))) {
				return $this->raise("Couldn't retrieve order information.");
			}

			/* make sure order has not already been accepted */
			if ($Order->order_status === 2) {
				return $this->raise("Order already accepted by vendor.");
			} elseif ($Order->order_status === 5) {
				return $this->raise("Order alrady canceled");
			}

			/* Getting the currency highest value by multiplying the total by 100 */

			/* total_amount = (item_price * qty) + charges */
			$finalRefundamount = $Order->total_amount;

			/* initiate a credit reques */
			$stripeService = StripeService::Payment()->refundChargeIntent($finalRefundamount, $OrderListItem->payment_intent, [
				"orderId" => $Order->id,
				"orderNo" => $Order->order_no,
			]);

			if (!$stripeService->STATE) {
				return $this->raise("Ops! Something bad went wrong, we couldn't credit your account at the moment.");
			}

			/* update shipping order status incase alaise code fails to updated the status
			 inother to avoid funds losses  */
			if (!$StoreModule->__updateShippingOrder($Order->id, ["order_status" => 5, "payment_status" => 3])) {
				return $this->raise("Order acceptance couldn't be processed");
			}
			Mail::to($Order->shipping_email)->send(new OrderDeclined($Order, $Product));

			return $this->response(
				[
					"refund_amount" => $finalRefundamount,
				],
				"The sum of {$finalRefundamount} has been refunded to client for this order."
			);
		} catch (Exception $th) {
			Log::error($th->getMessage(), $th->getTrace());

			return $this->raise();
		}
	}
}