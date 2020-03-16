import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { BlogCategoriesComponent } from "./blog-categories.component";
import { BlogCategoryListComponent } from "./blog-category-list/blog-category-list.component";
import { CreateBlogCategoryComponent } from "./create-blog-category/create-blog-category.component";

import { BlogCategoriesRoutingModule } from "./blog-categories-routing.module";

@NgModule({
  imports: [
    CommonModule,
    BlogCategoriesRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    BlogCategoriesComponent,
    BlogCategoryListComponent,
    CreateBlogCategoryComponent
  ]
})
export class BlogCategoriesModule {}
