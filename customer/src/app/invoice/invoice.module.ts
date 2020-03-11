import { NgModule } from "@angular/core";
import { SharedModule } from "../layout/shared/shared.module";
import { InvoiceRoutingModule } from "./invoice-routing.module";
import { InvoiceComponent } from "./invoice.component";

@NgModule({
    imports: [InvoiceRoutingModule, SharedModule],
    declarations: [InvoiceComponent]
})
export class InvoiceModule { }
