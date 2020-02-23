import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { CourseTopicsComponent } from "./course-topics.component";
import { CourseTopicListComponent } from "./course-topic-list/course-topic-list.component";
import { CreateCourseTopicComponent } from "./create-course-topic/create-course-topic.component";

const routes: Routes = [
  {
    path: "",
    component: CourseTopicsComponent,
    children: [
      {
        path: "edit/:id",
        component: CreateCourseTopicComponent
      },
      {
        path: "",
        component: CourseTopicListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CourseTopicsRoutingModule {}
