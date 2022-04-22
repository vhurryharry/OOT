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
    canLoad: [AuthGuard],
  },
  {
    path: "login",
    component: BlankComponent,
    loadChildren: () =>
      import("./login/login.module").then((x) => x.LoginModule),
  },

  // Content
  {
    path: "courses",
    component: CondensedComponent,
    loadChildren: () =>
      import("./courses/courses.module").then((x) => x.CoursesModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "course-categories",
    component: CondensedComponent,
    loadChildren: () =>
      import("./course-categories/course-categories.module").then(
        (x) => x.CourseCategoriesModule
      ),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "course-topics",
    component: CondensedComponent,
    loadChildren: () =>
      import("./course-topics/course-topics.module").then(
        (x) => x.CourseTopicsModule
      ),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "blogs",
    component: CondensedComponent,
    loadChildren: () =>
      import("./blogs/blogs.module").then((x) => x.BlogsModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "blog-categories",
    component: CondensedComponent,
    loadChildren: () =>
      import("./blog-categories/blog-categories.module").then(
        (x) => x.BlogCategoriesModule
      ),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "pages",
    component: CondensedComponent,
    loadChildren: () =>
      import("./entities/entities.module").then((x) => x.EntitiesModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "notifications",
    component: CondensedComponent,
    loadChildren: () =>
      import("./notifications/notifications.module").then(
        (x) => x.NotificationsModule
      ),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "testimonials",
    component: CondensedComponent,
    loadChildren: () =>
      import("./testimonials/testimonials.module").then(
        (x) => x.TestimonialsModule
      ),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "categories",
    component: CondensedComponent,
    loadChildren: () =>
      import("./categories/categories.module").then((x) => x.CategoriesModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "tags",
    component: CondensedComponent,
    loadChildren: () => import("./tags/tags.module").then((x) => x.TagsModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "menus",
    component: CondensedComponent,
    loadChildren: () =>
      import("./menus/menus.module").then((x) => x.MenusModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "faqs",
    component: CondensedComponent,
    loadChildren: () => import("./faqs/faqs.module").then((x) => x.FaqsModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },

  // Business
  {
    path: "students",
    component: CondensedComponent,
    loadChildren: () =>
      import("./students/students.module").then((x) => x.StudentsModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "instructors",
    component: CondensedComponent,
    loadChildren: () =>
      import("./instructors/instructors.module").then(
        (x) => x.InstructorsModule
      ),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "orders",
    component: CondensedComponent,
    loadChildren: () =>
      import("./orders/orders.module").then((x) => x.OrdersModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },

  // System
  {
    path: "users",
    component: CondensedComponent,
    loadChildren: () =>
      import("./users/users.module").then((x) => x.UsersModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "roles",
    component: CondensedComponent,
    loadChildren: () =>
      import("./roles/roles.module").then((x) => x.RolesModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "config",
    component: CondensedComponent,
    loadChildren: () =>
      import("./users/users.module").then((x) => x.UsersModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
  {
    path: "audit-logs",
    component: CondensedComponent,
    loadChildren: () =>
      import("./audit-logs/audit-logs.module").then((x) => x.AuditLogsModule),
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },

  {
    path: "**",
    component: CondensedComponent,
    canActivate: [AuthGuard],
    canLoad: [AuthGuard],
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
