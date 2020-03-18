import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";
import { CoursesComponent } from "./courses/courses.component";
import { CourseDetailComponent } from "./detail/course-detail.component";
import { NewCourseComponent } from "./new-course/new-course.component";

const routes: Routes = [
  {
    path: "",
    component: CoursesComponent
  },
  {
    path: "new",
    component: NewCourseComponent
  },
  {
    path: ":slug",
    component: CourseDetailComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CourseRoutingModule {}
