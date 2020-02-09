import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { CoursesComponent } from "./courses.component";
import { CourseListComponent } from "./course-list/course-list.component";
import { CreateCourseComponent } from "./create-course/create-course.component";
import { ManageOptionsComponent } from "./manage-options/manage-options.component";
import { ManageReviewsComponent } from "./manage-reviews/manage-reviews.component";
import { ManageInstructorsComponent } from "./manage-instructors/manage-instructors.component";

const routes: Routes = [
  {
    path: "",
    component: CoursesComponent,
    children: [
      {
        path: "edit/:id",
        component: CreateCourseComponent
      },
      {
        path: "edit/:id/options",
        component: ManageOptionsComponent
      },
      {
        path: "edit/:id/reviews",
        component: ManageReviewsComponent
      },
      {
        path: "edit/:id/instructors",
        component: ManageInstructorsComponent
      },
      {
        path: "",
        component: CourseListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CoursesRoutingModule {}
