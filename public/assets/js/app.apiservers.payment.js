/* the forms data  */

$(function () {
	"use strict"

	let BillingFormData = null,OrderFormData = null;
	let OrderFormDataList = [], BillingFormDataList = [];

	/* global vars */
	const URI = new URL(window.location.href)
	const callbackUrl = window.location.href.split(" ").join()
	const form = document.querySelector("#payment-form");
	

	const CONFIG = {
		/* app environment */
		env: () =>
			URI.protocol === "http:" || !URI.host.includes(".com") ? "development" : "production",
		isProd: () => (CONFIG.env() === "production" ? true : false),
		isDev: () => (CONFIG.env() === "development" ? true : false),
		/* server config  */
		servers: {
			authorization: "Bearer",
			local: "http://localhost/laravel/e-commerce/",
			dev: "http://localhost/laravel/e-commerce/",
			prod: "http://localhost/laravel/e-commerce/",
			apiBaseUrl: () => {
				const url = new URL(window.location.href)
				return `${CONFIG.servers.local}`
			}
		},

		templates: {
			renderPaymentForm: (target, id = "lgy_stripe_payment-form") => {
				let domElem = null
				if ((domElem = document.querySelector(target))) {
					$(domElem).html(
						`
                        <!-- strip payment testing  -->
                        <div class="heading_s1">
                            <h4>Payment Details</h4>
                        </div>

                        <form id="${id}">
                            <div id="payment-element">
                                <!-- Elements will create form elements here -->
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group text-center">
                                        <input type="hidden" name="payment_option" class="form-control" value="stripe">
                                        <button
                                            id="submit"
                                            class="btn btn-fill-out btn-block place-order-btn pay-out-btn stripe_payment_btn"
                                            style="cursor: pointer;" type="submit">
                                            <span class="btn-title">Pay Now</span>
                                        </button>
                                    </div>
                                </div>
                                <div id="error-message" style="color:red;">
                                <!-- Display error message to your customers here -->
                                </div>
                                
                                <div class="col-md-12 is_hold_payment_error"></div>
                            </div>
                         </form>
                        
                        `
					)
				}
			},

			/* This method mounts the strip form. */
			mountStripeForm: formId => {
				const stripe = Stripe(sessionStorage.getItem("pubKey"))

				const options = {
					clientSecret: sessionStorage.getItem("orderSecret")
				}

				// Set up Stripe.js and Elements to use in checkout form, passing the client secret obtained in step 2
				const elements = stripe.elements(options)
				const paymentElement = elements.create("payment")

				paymentElement.mount("#payment-element")

				$(formId).on("submit", async function (e) {
					//$("#preloader-custom").removeClass("d-none")
					e.preventDefault()

					/* change button text status to indicate something is working in the background */
					$("form #submit").prop("disabled", true)
					$("form #submit").html("<em>Please Wait....</em>")

					 // Confirm the payment without a return_url
					 const { paymentIntent } = await stripe.confirmPayment({
						elements,
						redirect: 'if_required', // Use this option
					});




					if (paymentIntent.error) {
						/* change button text status to indicate something is working in the 
						background */

						$("#preloader-custom").addClass("d-none")
						$("form #submit").prop("disabled", false)
						$("form #submit").html("<em>Ops! Re-try payment</em>")

						// This point will only be reached if there is an immediate error when
						// confirming the payment. Show error to your customer (for example, payment
						// details incomplete)
						const messageContainer = document.querySelector("#error-message")
						messageContainer.textContent = paymentIntent.error
					} else {

						$("form #submit").html("<em>Payment successful!</em>")
						handlePaymentSuccess(paymentIntent)

						/* Your customer will be redirected to your `return_url`. For some payment
                        methods like iDEAL, your customer will be redirected to an intermediate
                        site first to authorize the payment, then redirected to the `return_url`. */

						/* change button text status to indicate something is working in the 
						background */

						
					}
				})
			}
		},

		Toast: () => {
			return Swal.mixin({
				toast: true,
				position: "top-end",
				showConfirmButton: false,
				timer: 3000
			})
		}
		
		
		 
	}

	/* extract helpers methods */
	const { env, isProd, isDev, servers: server, Toast, templates } = CONFIG

	/* now only configure axios when its loaded */

	let HTTPClient = null
	if ("axios" in window) {
		HTTPClient = axios.create({
			responseType: "json"
		})

		/* intercept request to set authorization header */
		HTTPClient.interceptors.request.use(configs => {
			// check if authorization token is in session

			if (!configs.url.includes("csrf-cookie")) {
				configs.baseURL = server.apiBaseUrl()
			}

			/* configure the request base url */
			if (configs.url.includes("csrf-cookie") && isProd()) {
				configs.baseURL = server.prod
			} else if (configs.url.includes("csrf-cookie") && isDev()) {
				configs.baseURL = server.dev
			}
			/* End */

			/* set the app authorization header if found */
			if (sessionStorage.getItem("api_token")) {
				configs.headers.Authorization = `${server.authorization} ${sessionStorage.getItem(
					"api_token"
				)}`
			}

			return configs
		})

		/* response interceptors */
		HTTPClient.interceptors.response.use(
			response => {
				return response.data
			},
			error => {
				return Promise.reject(error.response.data)
			}
		)
	}

	/* extract the short hand methods of the http protocol */
	if (HTTPClient) {
		/* export  */
		const { get: GET, post: POST, put: PUT, delete: DROP } = HTTPClient

		/* api handlers  */
		const Apis = {
			async CSRF() {
				return await GET("/sanctum/csrf-cookie")
			},

			/**
			 * Place an order with products in shopping cart
			 * @param {object} formData Formdata
			 */
			async placeOrder(formData) {
				try {
					if (formData) {
						$("#preloader-custom").removeClass("d-none")
						const { data } = await POST("/product/create_order_charge", formData)
							.then(res => {

								if(res.status){
									$("#preloader-custom").addClass("d-none")
									// add loader here
									Toast().fire({
										type: "success",
										title: "Please complete payment with your card."
									})

									return res
								}
								else{
									Toast().fire({
										type: "error",
										title: res.message
									})
								}

								
							})
							.catch(e => {
								$("#preloader-custom").addClass("d-none")

								Toast().fire({
									type: "error",
									title: e.data ? e.data.join(", ") : "Ops! something went wrong"
								})
							})

						return data
					}
				} catch (error) {
					console.warn("APISERVER_ERR", error)
				}
			}
		}

		/* extract api handlers  */
		const { CSRF, placeOrder } = Apis
		

		/* fetch csrf token */
		CSRF().then(res => {})


	
		/* disable default forms submition */
		$("#review_order_items").on("submit", function (e) {
			e.preventDefault()
			OrderFormData = $(this).serialize();
			OrderFormDataList = $(this).serializeArray()
			
		})
		/* Billing form */
		$(".lgy__billing_form").on("submit", function (e) {
			e.preventDefault()

			BillingFormData = $(this).serialize()
			BillingFormDataList = $(this).serializeArray()
		})

		const BillingForm = $(".lgy__billing_form")
		const OrderForm = $("#review_order_items")
		const PaymentformId = "lgy_stripe_payment_form"

		/* when place order button is click run this  */
		$(".trigger_stripe_order").on("click", function (e) {
			e.preventDefault()
			const paymentForm = document.getElementById("payment-form")
			if (paymentForm?.checkValidity()) {
				
				BillingForm.trigger("submit")
				OrderForm.trigger("submit")

						
				$('.preloader').show();

				/* ONLY REQUEST NEW ORDER IF CLIENT HAS COMPLETE PREVIOUS ORDER  */
				/* place order  */
				placeOrder(`${OrderFormData}&${BillingFormData}`).then(res => {
					if (res) {
						/* disable order button */
						OrderForm.find("button[type=submit]").prop("disabled", true)

						/* parase payment form  */
						templates.renderPaymentForm("#lgy__sp_payment_form", PaymentformId)

						/* set payment data  */
						sessionStorage.setItem("pubKey", res.config.pub_key)
						sessionStorage.setItem("orderSecret", res.client_secret)

						setTimeout(() => templates.mountStripeForm(`#${PaymentformId}`), 100)
						
						
						$('.preloader').hide();

					}
				})
			} else {
				// add loader here
				Toast().fire({
					type: "info",
					title: "Please fill in the required fields in billing details form"
				})
			}
		})
	}

	async function handlePaymentSuccess(paymentIntent) {

		$('.preloader').show();
	
		if (paymentIntent && BillingFormData) {
			const paymentInfo = {
				payment_intent_id: paymentIntent.id,
			};
	
			const orderFormdata = `${OrderFormData}&${BillingFormData}&${$.param(paymentInfo)}`;
	
			try {
				const response = await $.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') },
					url: `${SITE_URL}/product/processing-order-booking`,
					type: "POST",
					dataType: "JSON",
					data: orderFormdata,
				});
	
				if (response.status) {
					window.location.href = `${SITE_URL}/product/order/success-booking?order_id=${response.data.order_id}&order_no=${response.data.order_no}`;
				} else {
					Toast.fire({
						icon: "info",
						title: response.message,
					});
				}
			} catch (error) {

				console.error("Error processing order:", error);
				$('.preloader').hide();
				Toast.fire({
					icon: "error",
					title: "An error occurred while processing your order. Please try again.",
				});

				// Reload the page after 2 seconds
				setTimeout(function() {
					location.reload();
				}, 2000);
			}
		}
	}

})



