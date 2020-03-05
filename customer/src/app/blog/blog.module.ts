import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";

import { SharedModule } from "../layout/shared/shared.module";
import { BlogRoutingModule } from "./blog-routing.module";
import { BlogListComponent } from "./blog-list/blog-list.component";
import { BlogViewComponent } from "./blog-view/blog-view.component";
import { BlogService } from "./blog.service";

@NgModule({
  imports: [CommonModule, FormsModule, BlogRoutingModule, SharedModule],
  declarations: [BlogListComponent, BlogViewComponent],
  providers: [BlogService]
})
export class BlogModule {}
