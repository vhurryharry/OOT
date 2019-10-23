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
}
