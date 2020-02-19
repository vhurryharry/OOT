import { NgModule } from "@angular/core";
import { Routes, RouterModule } from "@angular/router";

import { CondensedComponent } from "./layout/condensed/condensed.component";

const routes: Routes = [
  {
    path: "",
    component: CondensedComponent,
    loadChildren: "./home/home.module#HomeModule"
  },
  {
    path: "contact",
    component: CondensedComponent,
    loadChildren: "./contact/contact.module#ContactModule"
  },
  {
    path: "about",
    component: CondensedComponent,
    loadChildren: "./about/about.module#AboutModule"
  },
  {
    path: "course",
    component: CondensedComponent,
    loadChildren: "./course/course.module#CourseModule"
  },
  {
    path: "login",
    component: CondensedComponent,
    loadChildren: "./auth/login/login.module#LoginModule"
  },
  {
    path: "signup",
    component: CondensedComponent,
    loadChildren: "./auth/signup/signup.module#SignupModule"
  },
  {
    path: "forgot-pwd",
    component: CondensedComponent,
    loadChildren: "./auth/forgot-pwd/forgot-pwd.module#ForgotPwdModule"
  },
  {
    path: "reset-pwd",
    component: CondensedComponent,
    loadChildren: "./auth/reset-pwd/reset-pwd.module#ResetPwdModule"
  },
  {
    path: "**",
    redirectTo: "",
    pathMatch: "full"
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
