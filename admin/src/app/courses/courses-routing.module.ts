import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { CoursesComponent } from './courses.component';
import { CourseListComponent } from './course-list/course-list.component';
import { CreateCourseComponent } from './create-course/create-course.component';
import { ManageOptionsComponent } from './manage-options/manage-options.component';
import { ManageReviewsComponent } from './manage-reviews/manage-reviews.component';

const coursesRoutes: Routes = [
  {
    path: '',
    component: CoursesComponent,
    children: [
      {
        path: 'edit/:courseId',
        component: CreateCourseComponent
      },
      {
        path: 'edit/:courseId/options',
        component: ManageOptionsComponent
      },
      {
        path: 'edit/:courseId/reviews',
        component: ManageReviewsComponent
      },
      {
        path: '',
        component: CourseListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(coursesRoutes)],
  exports: [RouterModule]
})
export class CoursesRoutingModule {}
