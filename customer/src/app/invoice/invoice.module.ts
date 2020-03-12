import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";

import { SharedModule } from "../layout/shared/shared.module";
import { InvoiceRoutingModule } from "./invoice-routing.module";
import { InvoiceComponent } from "./invoice.component";

@NgModule({
  imports: [CommonModule, FormsModule, InvoiceRoutingModule, SharedModule],
  declarations: [InvoiceComponent]
})
export class InvoiceModule {}
