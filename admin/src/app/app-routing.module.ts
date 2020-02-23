import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { AuthGuard } from "./services/auth-guard.service";

import { CondensedComponent } from "./layout/condensed/condensed.component";
import { BlankComponent } from "./layout/blank/blank.component";

const routes: Routes = [
  {
    path: "",
    component: CondensedComponent,
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "login",
    component: BlankComponent,
    loadChildren: "./login/login.module#LoginModule"
  },

  // Content
  {
    path: "courses",
    component: CondensedComponent,
    loadChildren: "./courses/courses.module#CoursesModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "pages",
    component: CondensedComponent,
    loadChildren: "./entities/entities.module#EntitiesModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "notifications",
    component: CondensedComponent,
    loadChildren: "./notifications/notifications.module#NotificationsModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "categories",
    component: CondensedComponent,
    loadChildren: "./categories/categories.module#CategoriesModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "course-categories",
    component: CondensedComponent,
    loadChildren:
      "./course-categories/course-categories.module#CourseCategoriesModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "course-topics",
    component: CondensedComponent,
    loadChildren: "./course-topics/course-topics.module#CourseTopicsModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "tags",
    component: CondensedComponent,
    loadChildren: "./tags/tags.module#TagsModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "menus",
    component: CondensedComponent,
    loadChildren: "./menus/menus.module#MenusModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "faqs",
    component: CondensedComponent,
    loadChildren: "./faqs/faqs.module#FaqsModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },

  // Business
  {
    path: "students",
    component: CondensedComponent,
    loadChildren: "./students/students.module#StudentsModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "instructors",
    component: CondensedComponent,
    loadChildren: "./instructors/instructors.module#InstructorsModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "orders",
    component: CondensedComponent,
    loadChildren: "./orders/orders.module#OrdersModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },

  // System
  {
    path: "users",
    component: CondensedComponent,
    loadChildren: "./users/users.module#UsersModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "roles",
    component: CondensedComponent,
    loadChildren: "./roles/roles.module#RolesModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "config",
    component: CondensedComponent,
    loadChildren: "./users/users.module#UsersModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },
  {
    path: "audit-logs",
    component: CondensedComponent,
    loadChildren: "./audit-logs/audit-logs.module#AuditLogsModule",
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  },

  {
    path: "**",
    component: CondensedComponent,
    canActivate: [AuthGuard],
    canLoad: [AuthGuard]
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
