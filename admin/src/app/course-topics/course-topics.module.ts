import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { CourseTopicsComponent } from "./course-topics.component";
import { CourseTopicListComponent } from "./course-topic-list/course-topic-list.component";
import { CreateCourseTopicComponent } from "./create-course-topic/create-course-topic.component";

import { CourseTopicsRoutingModule } from "./course-topics-routing.module";

@NgModule({
  imports: [
    CommonModule,
    CourseTopicsRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    CourseTopicsComponent,
    CourseTopicListComponent,
    CreateCourseTopicComponent
  ]
})
export class CourseTopicsModule {}
