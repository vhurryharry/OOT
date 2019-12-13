import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ClarityModule } from '@clr/angular';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';

import { InstructorsComponent } from './instructors.component';
import { InstructorListComponent } from './instructor-list/instructor-list.component';
import { CreateInstructorComponent } from './create-instructor/create-instructor.component';

import { InstructorsRoutingModule } from './instructors-routing.module';

@NgModule({
  imports: [
    CommonModule,
    InstructorsRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    InstructorsComponent,
    InstructorListComponent,
    CreateInstructorComponent
  ]
})
export class InstructorsModule {}
