import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { TestimonialsComponent } from "./testimonials.component";
import { TestimonialListComponent } from "./testimonial-list/testimonial-list.component";
import { CreateTestimonialComponent } from "./create-testimonial/create-testimonial.component";

const routes: Routes = [
  {
    path: "",
    component: TestimonialsComponent,
    children: [
      {
        path: "edit/:id",
        component: CreateTestimonialComponent
      },
      {
        path: "",
        component: TestimonialListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TestimonialsRoutingModule {}
