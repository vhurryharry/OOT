import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { CourseCategoriesComponent } from "./course-categories.component";
import { CourseCategoryListComponent } from "./course-category-list/course-category-list.component";
import { CreateCourseCategoryComponent } from "./create-course-category/create-course-category.component";

const routes: Routes = [
  {
    path: "",
    component: CourseCategoriesComponent,
    children: [
      {
        path: "edit/:id",
        component: CreateCourseCategoryComponent
      },
      {
        path: "",
        component: CourseCategoryListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CourseCategoriesRoutingModule {}
