import { NgModule } from "@angular/core";
import { SharedModule } from "src/app/layout/shared/shared.module";
import { SignupRoutingModule } from "./signup-routing.module";
import { SignupComponent } from "./signup.component";

@NgModule({
  imports: [SignupRoutingModule, SharedModule],
  declarations: [SignupComponent]
})
export class SignupModule {}
