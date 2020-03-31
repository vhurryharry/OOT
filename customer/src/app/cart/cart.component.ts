import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

import {
  PaymentService,
  ICartItem,
  IPaymentMethod,
  PaymentAction
} from "../services/payment.service";
import { LoginService, IUserInfo } from "../services/login.service";

import { loadStripe } from "@stripe/stripe-js";
import { environment } from "src/environments/environment";

declare var $: any;

@Component({
  selector: "app-cart",
  templateUrl: "./cart.component.html",
  styleUrls: ["./cart.component.scss"]
})
export class CartComponent implements OnInit {
  cart: ICartItem[] = [];
  total = 0;

  loggedIn = false;
  paymentLoaded = false;
  paymentRequested = false;

  paymentMethods: IPaymentMethod[] = [];
  userId: string;

  success = false;
  errorMessage: string[] = [null, null];

  selectedPaymentMethod = -1;

  private stripe: any;

  constructor(
    private router: Router,
    private paymentService: PaymentService,
    private loginService: LoginService
  ) {
    this.loggedIn = this.loginService.isLoggedIn();

    this.cart = this.paymentService.getCart();
    this.cart.forEach(item => {
      this.total += item.quantity * item.price;
    });

    this.userId = this.loginService.getCurrentUserId();
  }

  async ngOnInit() {
    this.stripe = await loadStripe(environment.stripePKey);
  }

  decreaseItem(index: number) {
    if (this.cart[index].quantity > 0) {
      this.cart[index].quantity--;
      this.total -= this.cart[index].price;

      this.paymentService.setCart(this.cart);
    }
  }

  increaseItem(index: number) {
    this.cart[index].quantity++;
    this.total += this.cart[index].price;

    this.paymentService.setCart(this.cart);
  }

  removeItem(index: number) {
    if (this.cart.length > 0 && this.cart[index] !== null) {
      this.total -= this.cart[index].price * this.cart[index].quantity;
      this.cart.splice(index, 1);

      this.paymentService.setCart(this.cart);
    }
  }

  selectPaymentMethod(index: number) {
    this.selectedPaymentMethod = index;
  }

  checkout() {
    if (this.loggedIn) {
      if (!this.paymentLoaded) {
        this.errorMessage[0] = null;

        this.paymentService
          .getPaymentMethod(this.userId)
          .subscribe((result: any) => {
            if (result.success) {
              this.paymentLoaded = true;
              this.paymentMethods = result.methods.map(method => {
                return {
                  ...method,
                  expYear: method.expYear % 100,
                  brand: this.paymentService.getPaymentIcon(method.brand)
                };
              });
            } else {
              this.paymentLoaded = true;
              this.errorMessage[0] =
                "Error occured while getting your payment methods!";
            }
          });
      }

      $("#selectPaymentMethodModal").modal("show");
    }
  }

  pay() {
    this.paymentRequested = true;
    this.success = false;
    this.errorMessage[1] = null;

    this.paymentService
      .placeOrder(
        this.userId,
        this.cart,
        this.paymentMethods[this.selectedPaymentMethod].id
      )
      .subscribe(
        (result: any) => {
          if (result && result.success) {
            this.paymentRequested = false;

            this.success = true;

            this.paymentService.clearCart();
            this.cart = [];
            this.total = 0;

            $("#selectPaymentMethodModal").modal("hide");
          } else {
            if (result.paymentIntent) {
              this.stripe
                .confirmCardPayment(result.paymentIntent.client_secret, {
                  payment_method:
                    result.paymentIntent.last_payment_error.payment_method.id
                })
                .then((stripeResult: any) => {
                  this.paymentRequested = false;

                  if (stripeResult.error) {
                    // Show error to your customer
                    this.errorMessage[1] = stripeResult.error.message;
                  } else {
                    if (stripeResult.paymentIntent.status === "succeeded") {
                      // The payment is complete!
                      this.paymentService
                        .saveOrder(
                          this.userId,
                          this.cart,
                          stripeResult.paymentIntent.id,
                          this.paymentMethods[this.selectedPaymentMethod].id
                        )
                        .subscribe(res => {
                          this.success = true;

                          this.paymentService.clearCart();
                          this.cart = [];
                          this.total = 0;

                          $("#selectPaymentMethodModal").modal("hide");
                        });
                    }
                  }
                });
            } else {
              this.paymentRequested = false;
              this.errorMessage[1] = result.error;
            }
          }
        },
        (error: any) => {
          this.paymentRequested = false;
          this.errorMessage[1] = "Error occured while processing your order!";
        }
      );
  }

  loginAndCheckout() {
    this.loginService.redirectUrl = "/cart";
    this.router.navigateByUrl("/login");
  }

  addPaymentMethod() {
    $("#selectPaymentMethodModal").modal("hide");

    this.paymentService.redirectUrl = "/cart";
    this.paymentService.setAction(PaymentAction.AddCard);
    this.router.navigateByUrl("/payment");
  }
}
