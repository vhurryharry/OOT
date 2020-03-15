import { NgModule } from "@angular/core";
import { SharedModule } from "../layout/shared/shared.module";
import { TermsRoutingModule } from "./terms-routing.module";
import { TermsComponent } from "./terms.component";

@NgModule({
  imports: [TermsRoutingModule, SharedModule],
  declarations: [TermsComponent]
})
export class TermsModule {}
