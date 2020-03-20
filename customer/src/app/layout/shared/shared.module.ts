import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { HeaderComponent } from "./header/header.component";
import { FooterComponent } from "./footer/footer.component";
import { CardComponent } from "./card/card.component";
import { RouterModule } from "@angular/router";

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    CommonModule,
    ReactiveFormsModule,
    RouterModule
  ],
  declarations: [HeaderComponent, FooterComponent, CardComponent],
  exports: [HeaderComponent, FooterComponent, CardComponent]
})
export class SharedModule { }
