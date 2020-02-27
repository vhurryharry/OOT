import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { CommonModule } from "@angular/common";

import { SharedModule } from "src/app/layout/shared/shared.module";
import { AccountRoutingModule } from "./account-routing.module";
import { AccountComponent } from "./account.component";
import { AccountService } from "./account.service";

@NgModule({
  imports: [CommonModule, AccountRoutingModule, SharedModule, FormsModule],
  declarations: [AccountComponent],
  providers: [AccountService]
})
export class AccountModule {}
