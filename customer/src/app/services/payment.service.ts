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

export enum PaymentAction {
  AddCard = 1,
  RegisterAndPay = 2
}

@Injectable({
  providedIn: "root"
})
export class PaymentService {
  private baseURL: string = environment.baseURL + "/api/customer";

  private action: PaymentAction;
  public redirectUrl: string = null;

  constructor(private http: HttpClient) {}

  setAction(action: PaymentAction) {
    this.action = action;
  }

  getAction() {
    return this.action;
  }

  addToCart(item: ICartItem) {
    const cart = [];
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

  getClientSecret(userId: string, billingDetails, attendeeInformation) {
    return this.http.post(this.baseURL + "/client-secret", {
      userId,
      billing: billingDetails,
      attendee: attendeeInformation
    });
  }

  addPaymentMethod(userId: string, clientSecret: string, pmToken: string) {
    return this.http.post(this.baseURL + "/payment-method", {
      userId,
      clientSecret,
      pmToken
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

  saveOrder(
    userId: string,
    cart: ICartItem[],
    paymentIntentId: string,
    paymentMethodId: string
  ) {
    return this.http.post(this.baseURL + "/order/save", {
      userId,
      paymentIntentId,
      paymentMethodId,
      cart
    });
  }

  getBilling(billingNumber: string) {
    return this.http.get(this.baseURL + "/billing/" + billingNumber);
  }
}
