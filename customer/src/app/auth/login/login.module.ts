import { NgModule } from "@angular/core";
import { LoginComponent } from "./login.component";
import { SharedModule } from "src/app/layout/shared/shared.module";
import { LoginRoutingModule } from "./login-routing.module";

@NgModule({
  imports: [LoginRoutingModule, SharedModule],
  declarations: [LoginComponent]
})
export class LoginModule {}
