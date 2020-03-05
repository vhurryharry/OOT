import { NgModule } from "@angular/core";

import { SharedModule } from "../layout/shared/shared.module";
import { ContactRoutingModule } from "./contact-routing.module";
import { ContactComponent } from "./contact.component";
import { ContactService } from "./contact.service";

@NgModule({
  imports: [ContactRoutingModule, SharedModule],
  declarations: [ContactComponent],
  providers: [ContactService]
})
export class ContactModule {}
