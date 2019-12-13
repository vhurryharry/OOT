import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { RolesComponent } from './roles.component';
import { RoleListComponent } from './role-list/role-list.component';
import { CreateRoleComponent } from './create-role/create-role.component';

const routes: Routes = [
  {
    path: '',
    component: RolesComponent,
    children: [
      {
        path: 'edit/:id',
        component: CreateRoleComponent
      },
      {
        path: '',
        component: RoleListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RolesRoutingModule {}
