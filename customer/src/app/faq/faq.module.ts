import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";

import { SharedModule } from "../layout/shared/shared.module";
import { FAQRoutingModule } from "./faq-routing.module";
import { FAQComponent } from "./faq.component";
import { FAQService } from "./faq.service";

@NgModule({
  imports: [CommonModule, FormsModule, FAQRoutingModule, SharedModule],
  declarations: [FAQComponent],
  providers: [FAQService]
})
export class FAQModule {}
