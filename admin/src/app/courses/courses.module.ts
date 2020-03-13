import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { CoursesComponent } from "./courses.component";
import { CourseListComponent } from "./course-list/course-list.component";
import { CreateCourseComponent } from "./create-course/create-course.component";
import { ManageOptionsComponent } from "./manage-options/manage-options.component";
import { ManageReviewsComponent } from "./manage-reviews/manage-reviews.component";
import { ManageTestimonialsComponent } from "./manage-testimonials/manage-testimonials.component";
import { ManageInstructorsComponent } from "./manage-instructors/manage-instructors.component";
import { ManageCategoriesComponent } from "./manage-categories/manage-categories.component";
import { ManageTopicsComponent } from "./manage-topics/manage-topics.component";

import { CoursesRoutingModule } from "./courses-routing.module";

@NgModule({
  imports: [
    CommonModule,
    CoursesRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    CoursesComponent,
    CourseListComponent,
    CreateCourseComponent,
    ManageOptionsComponent,
    ManageReviewsComponent,
    ManageTestimonialsComponent,
    ManageInstructorsComponent,
    ManageCategoriesComponent,
    ManageTopicsComponent
  ]
})
export class CoursesModule {}
