import { BrowserModule } from "@angular/platform-browser";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { HttpClientModule } from "@angular/common/http";

import { AppRoutingModule } from "./app-routing.module";
import { AppComponent } from "./app.component";

import { SharedModule } from "./layout/shared/shared.module";

import { CondensedComponent } from "./layout/condensed/condensed.component";
import { PageComponent } from "./layout/page/page.component";

import { LoginService } from "./services/login.service";
import { PaymentService } from "./services/payment.service";
import { AuthGuard } from "./services/auth-guard.service";
import { SurveyService } from "./services/survey.service";
import { EntityService } from "./services/entity.service";

import "@stripe/stripe-js";

@NgModule({
  declarations: [AppComponent, CondensedComponent, PageComponent],
  imports: [
    SharedModule,
    BrowserModule,
    BrowserAnimationsModule,
    AppRoutingModule,
    FormsModule,
    HttpClientModule
  ],
  providers: [
    LoginService,
    PaymentService,
    AuthGuard,
    SurveyService,
    EntityService
  ],
  bootstrap: [AppComponent]
})
export class AppModule {}
