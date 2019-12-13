import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ClarityModule } from '@clr/angular';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';

import { TagsComponent } from './tags.component';
import { TagListComponent } from './tag-list/tag-list.component';
import { CreateTagComponent } from './create-tag/create-tag.component';

import { TagsRoutingModule } from './tags-routing.module';

@NgModule({
  imports: [
    CommonModule,
    TagsRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [TagsComponent, TagListComponent, CreateTagComponent]
})
export class TagsModule {}
