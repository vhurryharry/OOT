import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";
import { BlogListComponent } from "./blog-list/blog-list.component";
import { BlogViewComponent } from "./blog-view/blog-view.component";

const routes: Routes = [
  {
    path: "",
    component: BlogListComponent
  },
  {
    path: ":slug",
    component: BlogViewComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class BlogRoutingModule {}
