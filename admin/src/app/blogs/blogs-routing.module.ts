import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { BlogsComponent } from "./blogs.component";
import { BlogListComponent } from "./blog-list/blog-list.component";
import { CreateBlogComponent } from "./create-blog/create-blog.component";

const routes: Routes = [
  {
    path: "",
    component: BlogsComponent,
    children: [
      {
        path: "edit/:id",
        component: CreateBlogComponent
      },
      {
        path: "",
        component: BlogListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class BlogsRoutingModule {}
