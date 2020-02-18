import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";
import { ResetPwdComponent } from "./reset-pwd.component";

const routes: Routes = [
  {
    path: "",
    component: ResetPwdComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ResetPwdRoutingModule {}
