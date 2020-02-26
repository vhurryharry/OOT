import { NgModule } from "@angular/core";
import { FormsModule } from "@angular/forms";

import { SharedModule } from "src/app/layout/shared/shared.module";
import { AccountRoutingModule } from "./account-routing.module";
import { AccountComponent } from "./account.component";
import { AccountService } from "./account.service";

@NgModule({
  imports: [AccountRoutingModule, SharedModule, FormsModule],
  declarations: [AccountComponent],
  providers: [AccountService]
})
export class AccountModule {}
