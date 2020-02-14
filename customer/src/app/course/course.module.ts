import { NgModule } from "@angular/core";
import { SharedModule } from "../layout/shared/shared.module";
import { CourseRoutingModule } from "./course-routing.module";
import { CoursesComponent } from "./courses/courses.component";
import { CourseDetailComponent } from "./detail/course-detail.component";

@NgModule({
  imports: [CourseRoutingModule, SharedModule],
  declarations: [CoursesComponent, CourseDetailComponent]
})
export class CourseModule {}
