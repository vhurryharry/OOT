import { NgModule } from "@angular/core";
import { SharedModule } from "src/app/layout/shared/shared.module";
import { SignupRoutingModule } from "./signup-routing.module";
import { SignupComponent } from "./signup.component";
import { FormsModule } from "@angular/forms";

@NgModule({
  imports: [SignupRoutingModule, SharedModule, FormsModule],
  declarations: [SignupComponent]
})
export class SignupModule {}
