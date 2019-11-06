import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { CheckoutComponent } from './checkout.component';
import { LoaderComponent } from './loader.component';
import { OrderService } from './order.service';
import { CookieService } from 'ngx-cookie-service';
import { LoaderInterceptorService } from './loader.interceptor';

@NgModule({
  declarations: [
    CheckoutComponent,
    LoaderComponent,
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpClientModule,
  ],
  providers: [
    CookieService,
    // {
    //   provide: HTTP_INTERCEPTORS,
    //   useClass: LoaderInterceptorService,
    //   multi: true
    // }
  ],
  bootstrap: [
    CheckoutComponent,
  ],
})
export class CheckoutModule { }
