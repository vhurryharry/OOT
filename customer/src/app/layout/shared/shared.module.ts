import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { HeaderComponent } from "./header/header.component";
import { FooterComponent } from "./footer/footer.component";
import { PaymentComponent } from "./payment/payment.component";

@NgModule({
  imports: [CommonModule, FormsModule, CommonModule, ReactiveFormsModule],
  declarations: [HeaderComponent, FooterComponent, PaymentComponent],
  exports: [HeaderComponent, FooterComponent, PaymentComponent]
})
export class SharedModule {}
