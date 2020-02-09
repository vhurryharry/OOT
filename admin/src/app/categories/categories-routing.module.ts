import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { CategoriesComponent } from "./categories.component";
import { CategoryListComponent } from "./category-list/category-list.component";
import { CreateCategoryComponent } from "./create-category/create-category.component";

const routes: Routes = [
  {
    path: "",
    component: CategoriesComponent,
    children: [
      {
        path: "edit/:id",
        component: CreateCategoryComponent
      },
      {
        path: "",
        component: CategoryListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CategoriesRoutingModule {}
