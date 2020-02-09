import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { CategoriesComponent } from "./categories.component";
import { CategoryListComponent } from "./category-list/category-list.component";
import { CreateCategoryComponent } from "./create-category/create-category.component";

import { CategoriesRoutingModule } from "./categories-routing.module";

@NgModule({
  imports: [
    CommonModule,
    CategoriesRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    CategoriesComponent,
    CategoryListComponent,
    CreateCategoryComponent
  ]
})
export class CategoriesModule {}
