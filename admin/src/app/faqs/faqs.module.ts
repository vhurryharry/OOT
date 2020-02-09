import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { FaqsComponent } from "./faqs.component";
import { FaqListComponent } from "./faq-list/faq-list.component";
import { CreateFaqComponent } from "./create-faq/create-faq.component";

import { FaqsRoutingModule } from "./faqs-routing.module";

@NgModule({
  imports: [
    CommonModule,
    FaqsRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [FaqsComponent, FaqListComponent, CreateFaqComponent]
})
export class FaqsModule {}
