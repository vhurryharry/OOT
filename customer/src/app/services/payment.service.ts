import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

export interface ICartItem {
  id: string;
  name: string;
  price: number;
  quantity: number;
}

export interface IPaymentMethod {
  id: string;
  userName: string;
  last4: string;
  brand: string;
  expMonth: number;
  expYear: number;
}

export const paymentIcons = {
  visa: 1,
  mastercard: 2,
  maestro: 3,
  cirrus: 4,
  paypal: 5,
  discover: 14,
  diners: 10,
  jcb: 16,
  amex: 22,
  "american express": 22,
  unknown: 0
};

@Injectable({
  providedIn: "root"
})
export class PaymentService {
  private baseURL: string = environment.baseURL + "/api/customer";

  constructor(private http: HttpClient) {}

  addToCart(item: ICartItem) {
    const cart = this.getCart();
    cart.push(item);

    localStorage.setItem("cart", JSON.stringify(cart));
  }

  clearCart() {
    localStorage.removeItem("cart");
  }

  getCart() {
    let cart = [];
    const cartStr = localStorage.getItem("cart");
    if (cartStr !== null) {
      cart = JSON.parse(cartStr);
    }

    return cart;
  }

  setCart(cart: ICartItem[]) {
    if (cart !== null) {
      localStorage.setItem("cart", JSON.stringify(cart));
    }
  }

  addPaymentMethod(userId: string, token: string) {
    return this.http.post(this.baseURL + "/payment-method", {
      userId,
      token
    });
  }

  removePaymentMethod(userId: string, methodId: string) {
    return this.http.delete(
      this.baseURL + "/payment-method/" + userId + "/" + methodId
    );
  }

  getPaymentMethod(userId: string) {
    return this.http.get(this.baseURL + "/payment-method/" + userId);
  }

  getPaymentIcon(brand: string, isDark = true) {
    if (isDark) {
      return (
        "/assets/images/payment/dark/" +
        paymentIcons[brand.toLowerCase()] +
        ".png"
      );
    }

    return (
      "/assets/images/payment/light/" +
      paymentIcons[brand.toLowerCase()] +
      ".png"
    );
  }

  placeOrder(userId: string, cart: ICartItem[], paymentMethodId: string) {
    return this.http.post(this.baseURL + "/order", {
      userId,
      paymentMethodId,
      cart
    });
  }
}
