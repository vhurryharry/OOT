import { NgModule } from "@angular/core";
import { SharedModule } from "../layout/shared/shared.module";
import { ContactRoutingModule } from './contact-routing.module';
import { ContactComponent } from './contact.component';

@NgModule({
    imports: [ContactRoutingModule, SharedModule],
    declarations: [ContactComponent]
})
export class ContactModule { }
