import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";

import { SharedModule } from "src/app/layout/shared/shared.module";
import { ResetPwdComponent } from "./reset-pwd.component";
import { ResetPwdRoutingModule } from "./reset-pwd-routing.module";

@NgModule({
  imports: [ResetPwdRoutingModule, SharedModule, FormsModule],
  declarations: [ResetPwdComponent]
})
export class ResetPwdModule {}
