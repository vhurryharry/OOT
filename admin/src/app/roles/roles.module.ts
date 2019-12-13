import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ClarityModule } from '@clr/angular';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';

import { RolesComponent } from './roles.component';
import { RoleListComponent } from './role-list/role-list.component';
import { CreateRoleComponent } from './create-role/create-role.component';

import { RolesRoutingModule } from './roles-routing.module';

@NgModule({
  imports: [
    CommonModule,
    RolesRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [RolesComponent, RoleListComponent, CreateRoleComponent]
})
export class RolesModule {}
