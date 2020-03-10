import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { HeaderComponent } from "./header/header.component";
import { FooterComponent } from "./footer/footer.component";
import { CardComponent } from "./card/card.component";

@NgModule({
  imports: [CommonModule, FormsModule, CommonModule, ReactiveFormsModule],
  declarations: [HeaderComponent, FooterComponent, CardComponent],
  exports: [HeaderComponent, FooterComponent, CardComponent]
})
export class SharedModule {}
