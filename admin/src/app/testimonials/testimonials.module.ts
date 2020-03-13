import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { TestimonialsComponent } from "./testimonials.component";
import { TestimonialListComponent } from "./testimonial-list/testimonial-list.component";
import { CreateTestimonialComponent } from "./create-testimonial/create-testimonial.component";

import { TestimonialsRoutingModule } from "./testimonials-routing.module";

@NgModule({
  imports: [
    CommonModule,
    TestimonialsRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    TestimonialsComponent,
    TestimonialListComponent,
    CreateTestimonialComponent
  ]
})
export class TestimonialsModule {}
