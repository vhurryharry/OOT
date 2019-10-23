import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { CheckoutComponent } from './checkout.component';
import { OrderService } from './order.service';
import { CookieService } from 'ngx-cookie-service';

@NgModule({
  declarations: [
    CheckoutComponent,
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpClientModule,
  ],
  providers: [
    CookieService,
  ],
  bootstrap: [
    CheckoutComponent,
  ],
})
export class CheckoutModule { }
