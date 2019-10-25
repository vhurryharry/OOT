import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CookieService } from 'ngx-cookie-service';

@Injectable({
  providedIn: 'root',
})
export class OrderService {
  constructor(private http: HttpClient, private cookieService: CookieService) { }

  getCurrentOrder() {
    return this.http.post('/api/order/recalculate', {});
  }

  remove(item) {
    return this.http.post('/api/order/remove', {
      id: item.id,
    });
  }

  placeOrder(order) {
    return this.http.post('/api/order/place', order);
  }
}
