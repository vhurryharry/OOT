import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";

import { environment } from "../../environments/environment";

export interface ICartItem {
  id: string;
  name: string;
  price: number;
  quantity: number;
}

@Injectable({
  providedIn: "root"
})
export class PaymentService {
  private baseURL: string = environment.baseURL + "/api";

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
}
