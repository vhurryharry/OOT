import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";
import { CoursesComponent } from "./courses/courses.component";
import { CourseDetailComponent } from "./detail/course-detail.component";

const routes: Routes = [
  {
    path: "",
    component: CoursesComponent
  },
  {
    path: "/:courseId",
    component: CourseDetailComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CourseRoutingModule {}