import { NgModule } from "@angular/core";

import { HeaderComponent } from "./header/header.component";
import { CommonModule } from "@angular/common";
import { FooterComponent } from './footer/footer.component';

@NgModule({
    imports: [CommonModule],
    declarations: [HeaderComponent, FooterComponent],
    exports: [HeaderComponent, FooterComponent]
})
export class SharedModule { }
