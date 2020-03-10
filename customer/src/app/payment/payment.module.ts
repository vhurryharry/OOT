import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";

import { SharedModule } from "../layout/shared/shared.module";
import { PaymentRoutingModule } from "./payment-routing.module";
import { PaymentComponent } from "./payment.component";

import { OwlDateTimeModule, OwlNativeDateTimeModule } from "ng-pick-datetime";

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaymentRoutingModule,
    SharedModule,
    OwlDateTimeModule,
    OwlNativeDateTimeModule
  ],
  declarations: [PaymentComponent]
})
export class PaymentModule {}
