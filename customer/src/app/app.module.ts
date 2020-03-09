import { BrowserModule } from "@angular/platform-browser";
import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { HttpClientModule } from "@angular/common/http";

import { AppRoutingModule } from "./app-routing.module";
import { AppComponent } from "./app.component";

import { CondensedComponent } from "./layout/condensed/condensed.component";
import { BlankComponent } from "./layout/blank/blank.component";

import { LoginService } from "./services/login.service";
import { PaymentService } from "./services/payment.service";

import "@stripe/stripe-js";

@NgModule({
  declarations: [AppComponent, CondensedComponent, BlankComponent],
  imports: [BrowserModule, AppRoutingModule, FormsModule, HttpClientModule],
  providers: [LoginService, PaymentService],
  bootstrap: [AppComponent]
})
export class AppModule {}
