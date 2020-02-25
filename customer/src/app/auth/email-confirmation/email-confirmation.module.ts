import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { CommonModule } from "@angular/common";

import { SharedModule } from "src/app/layout/shared/shared.module";
import { EmailConfirmationComponent } from "./email-confirmation.component";
import { EmailConfirmationRoutingModule } from "./email-confirmation-routing.module";

@NgModule({
  imports: [
    CommonModule,
    EmailConfirmationRoutingModule,
    SharedModule,
    FormsModule
  ],
  declarations: [EmailConfirmationComponent]
})
export class EmailConfirmationModule {}
