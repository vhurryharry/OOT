import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { BlogsComponent } from "./blogs.component";
import { BlogListComponent } from "./blog-list/blog-list.component";
import { CreateBlogComponent } from "./create-blog/create-blog.component";

import { BlogsRoutingModule } from "./blogs-routing.module";

@NgModule({
  imports: [
    CommonModule,
    BlogsRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [BlogsComponent, BlogListComponent, CreateBlogComponent]
})
export class BlogsModule {}
