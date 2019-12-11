import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { DashboardComponent } from './dashboard/dashboard.component';

import { EntityListComponent } from './entities/entity-list/entity-list.component';
import { NotificationListComponent } from './notifications/notification-list/notification-list.component';
import { CategoryListComponent } from './categories/category-list/category-list.component';
import { TagListComponent } from './tags/tag-list/tag-list.component';
import { MenuListComponent } from './menus/menu-list/menu-list.component';
import { FaqListComponent } from './faqs/faq-list/faq-list.component';

import { DocumentListComponent } from './documents/document-list/document-list.component';

import { UserListComponent } from './users/user-list/user-list.component';
import { RoleListComponent } from './roles/role-list/role-list.component';
import { OrderListComponent } from './orders/order-list/order-list.component';

import { AuditLogListComponent } from './audit-logs/audit-log-list/audit-log-list.component';
import { StudentListComponent } from './students/student-list/student-list.component';
import { InstructorListComponent } from './instructors/instructor-list/instructor-list.component';

const routes: Routes = [
  { path: '', component: DashboardComponent },
  {
    path: 'login',
    loadChildren: './login/login.module#LoginModule'
  },

  // Content
  {
    path: 'courses',
    loadChildren: './courses/courses.module#CoursesModule'
  },
  { path: 'pages', component: EntityListComponent },
  { path: 'notifications', component: NotificationListComponent },
  { path: 'categories', component: CategoryListComponent },
  { path: 'tags', component: TagListComponent },
  { path: 'menus', component: MenuListComponent },
  { path: 'faqs', component: FaqListComponent },

  // Business
  { path: 'students', component: StudentListComponent },
  { path: 'instructors', component: InstructorListComponent },
  { path: 'orders', component: OrderListComponent },

  // System
  { path: 'users', component: UserListComponent },
  { path: 'roles', component: RoleListComponent },
  { path: 'config', component: UserListComponent },
  { path: 'audit-logs', component: AuditLogListComponent },

  { path: '**', component: DashboardComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
