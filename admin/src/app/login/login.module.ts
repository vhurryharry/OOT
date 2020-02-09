import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";

import { AuthGuard } from "../services/auth-guard.service";
import { LoginComponent } from "./login.component";
import { RouterModule } from "@angular/router";

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    RouterModule.forChild([
      {
        path: "",
        component: LoginComponent
      }
    ])
  ],
  declarations: [LoginComponent],
  providers: [AuthGuard]
})
export class LoginModule {}
