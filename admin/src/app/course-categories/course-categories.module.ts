import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { CourseCategoriesComponent } from "./course-categories.component";
import { CourseCategoryListComponent } from "./course-category-list/course-category-list.component";
import { CreateCourseCategoryComponent } from "./create-course-category/create-course-category.component";

import { CourseCategoriesRoutingModule } from "./course-categories-routing.module";

@NgModule({
  imports: [
    CommonModule,
    CourseCategoriesRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    CourseCategoriesComponent,
    CourseCategoryListComponent,
    CreateCourseCategoryComponent
  ]
})
export class CourseCategoriesModule {}
