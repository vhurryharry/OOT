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
    path: "**",
    component: CondensedComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
