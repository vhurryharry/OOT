import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { SharedModule } from "../layout/shared/shared.module";
import { CourseRoutingModule } from "./course-routing.module";
import { CoursesComponent } from "./courses/courses.component";
import { CourseDetailComponent } from "./detail/course-detail.component";
import { CourseService } from "./course.service";

import { NgxMapboxGLModule } from "ngx-mapbox-gl";
import { environment } from "src/environments/environment";
import { NewCourseComponent } from "./new-course/new-course.component";
import { OwlDateTimeModule, OwlNativeDateTimeModule } from "ng-pick-datetime";

@NgModule({
  imports: [
    CommonModule,
    CourseRoutingModule,
    SharedModule,
    FormsModule,
    NgxMapboxGLModule.withConfig({
      accessToken: environment.mapbox.accessToken
    }),
    OwlDateTimeModule,
    OwlNativeDateTimeModule,
    CKEditorModule,
    ReactiveFormsModule
  ],
  declarations: [CoursesComponent, CourseDetailComponent, NewCourseComponent],
  providers: [CourseService]
})
export class CourseModule {}
