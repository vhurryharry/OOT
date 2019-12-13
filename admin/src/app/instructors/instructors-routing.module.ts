import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { InstructorsComponent } from './instructors.component';
import { InstructorListComponent } from './instructor-list/instructor-list.component';
import { CreateInstructorComponent } from './create-instructor/create-instructor.component';

const routes: Routes = [
  {
    path: '',
    component: InstructorsComponent,
    children: [
      {
        path: 'edit/:id',
        component: CreateInstructorComponent
      },
      {
        path: '',
        component: InstructorListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class InstructorsRoutingModule {}
