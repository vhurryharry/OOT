import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";
import { CoursesComponent } from "./courses/courses.component";
import { CourseDetailComponent } from "./detail/course-detail.component";
import { NewCourseComponent } from "./new-course/new-course.component";
import { AuthGuard } from "../services/auth-guard.service";

const routes: Routes = [
  {
    path: "",
    component: CoursesComponent
  },
  {
    path: "new",
    component: NewCourseComponent,
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
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
