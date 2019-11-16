import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DashboardComponent } from './dashboard/dashboard.component';
import { CourseListComponent } from './courses/course-list/course-list.component';
import { UserListComponent } from './users/user-list/user-list.component';

const routes: Routes = [
  { path: '', component: DashboardComponent },

  // Content
  { path: 'courses', component: CourseListComponent },
  { path: 'pages', component: CourseListComponent },
  { path: 'notifications', component: CourseListComponent },

  // System
  { path: 'users', component: UserListComponent },
  { path: 'roles', component: UserListComponent },
  { path: 'config', component: UserListComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
