import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { CommonModule } from "@angular/common";

import { LoginComponent } from "./login.component";

import { SharedModule } from "src/app/layout/shared/shared.module";
import { LoginRoutingModule } from "./login-routing.module";

@NgModule({
  imports: [CommonModule, LoginRoutingModule, SharedModule, FormsModule],
  declarations: [LoginComponent]
})
export class LoginModule {}
