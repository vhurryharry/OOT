import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { BlogCategoriesComponent } from "./blog-categories.component";
import { BlogCategoryListComponent } from "./blog-category-list/blog-category-list.component";
import { CreateBlogCategoryComponent } from "./create-blog-category/create-blog-category.component";

const routes: Routes = [
  {
    path: "",
    component: BlogCategoriesComponent,
    children: [
      {
        path: "edit/:id",
        component: CreateBlogCategoryComponent
      },
      {
        path: "",
        component: BlogCategoryListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class BlogCategoriesRoutingModule {}
