import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { HttpClientModule } from "@angular/common/http";

import { SharedModule } from "../layout/shared/shared.module";
import { CartRoutingModule } from "./cart-routing.module";
import { CartComponent } from "./cart.component";

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    SharedModule,
    HttpClientModule,
    CartRoutingModule
  ],
  declarations: [CartComponent],
  providers: []
})
export class CartModule {}
