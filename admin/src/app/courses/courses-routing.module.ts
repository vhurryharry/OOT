import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { CoursesComponent } from "./courses.component";
import { CourseListComponent } from "./course-list/course-list.component";
import { CreateCourseComponent } from "./create-course/create-course.component";
import { ManageOptionsComponent } from "./manage-options/manage-options.component";
import { ManageReviewsComponent } from "./manage-reviews/manage-reviews.component";
import { ManageInstructorsComponent } from "./manage-instructors/manage-instructors.component";
import { ManageCategoriesComponent } from "./manage-categories/manage-categories.component";
import { ManageTopicsComponent } from "./manage-topics/manage-topics.component";
import { ManageTestimonialsComponent } from "./manage-testimonials/manage-testimonials.component";
import { ManageSurveyQuestionsComponent } from "./manage-survey-questions/manage-survey-questions.component";
import { ManageSurveyResultsComponent } from "./manage-survey-results/manage-survey-results.component";

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
        path: "edit/:id/testimonials",
        component: ManageTestimonialsComponent
      },
      {
        path: "edit/:id/instructors",
        component: ManageInstructorsComponent
      },
      {
        path: "edit/:id/categories",
        component: ManageCategoriesComponent
      },
      {
        path: "edit/:id/topics",
        component: ManageTopicsComponent
      },
      {
        path: "edit/:id/survey-questions",
        component: ManageSurveyQuestionsComponent
      },
      {
        path: "edit/:id/survey-results",
        component: ManageSurveyResultsComponent
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
