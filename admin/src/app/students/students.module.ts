import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ClarityModule } from '@clr/angular';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';

import { StudentsComponent } from './students.component';
import { StudentListComponent } from './student-list/student-list.component';
import { CreateStudentComponent } from './create-student/create-student.component';

import { StudentsRoutingModule } from './students-routing.module';

@NgModule({
  imports: [
    CommonModule,
    StudentsRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    StudentsComponent,
    StudentListComponent,
    CreateStudentComponent
  ]
})
export class StudentsModule {}
