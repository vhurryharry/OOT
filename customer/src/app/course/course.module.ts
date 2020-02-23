import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";

import { SharedModule } from "../layout/shared/shared.module";
import { CourseRoutingModule } from "./course-routing.module";
import { CoursesComponent } from "./courses/courses.component";
import { CourseDetailComponent } from "./detail/course-detail.component";
import { CourseService } from "./course.service";

import { NgxMapboxGLModule } from "ngx-mapbox-gl";
import { environment } from "src/environments/environment";

@NgModule({
  imports: [
    CommonModule,
    CourseRoutingModule,
    SharedModule,
    FormsModule,
    NgxMapboxGLModule.withConfig({
      accessToken: environment.mapbox.accessToken
    })
  ],
  declarations: [CoursesComponent, CourseDetailComponent],
  providers: [CourseService]
})
export class CourseModule {}
