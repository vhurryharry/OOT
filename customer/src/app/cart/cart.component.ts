import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

import { PaymentService, ICartItem } from "../services/payment.service";
import { LoginService } from "../services/login.service";

@Component({
  selector: "app-cart",
  templateUrl: "./cart.component.html",
  styleUrls: ["./cart.component.scss"]
})
export class CartComponent implements OnInit {
  cart: ICartItem[] = [];
  total = 0;
  loggedIn = false;

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
  }

  ngOnInit() {}

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

  checkout() {}

  loginAndCheckout() {
    this.loginService.redirectUrl = "/cart";
    this.router.navigateByUrl("/login");
  }
}
