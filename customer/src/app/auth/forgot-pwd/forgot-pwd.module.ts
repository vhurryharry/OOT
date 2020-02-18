import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";

import { SharedModule } from "src/app/layout/shared/shared.module";
import { ForgotPwdRoutingModule } from "./forgot-pwd-routing.module";
import { ForgotPwdComponent } from "./forgot-pwd.component";

@NgModule({
  imports: [ForgotPwdRoutingModule, SharedModule, FormsModule],
  declarations: [ForgotPwdComponent]
})
export class ForgotPwdModule {}
