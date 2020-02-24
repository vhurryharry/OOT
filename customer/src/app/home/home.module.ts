import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";

import { HomeComponent } from "./home.component";
import { HomeRoutingModule } from "./home-routing.module";
import { SharedModule } from "../layout/shared/shared.module";
import { HomeService } from "./home.service";

@NgModule({
  imports: [CommonModule, FormsModule, HomeRoutingModule, SharedModule],
  declarations: [HomeComponent],
  providers: [HomeService]
})
export class HomeModule {}
