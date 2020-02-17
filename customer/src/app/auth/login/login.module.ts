import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { LoginComponent } from "./login.component";

import { SharedModule } from "src/app/layout/shared/shared.module";
import { LoginRoutingModule } from "./login-routing.module";

@NgModule({
  imports: [LoginRoutingModule, SharedModule, FormsModule],
  declarations: [LoginComponent]
})
export class LoginModule {}
