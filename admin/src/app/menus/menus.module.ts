import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ClarityModule } from '@clr/angular';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';

import { MenusComponent } from './menus.component';
import { MenuListComponent } from './menu-list/menu-list.component';
import { CreateMenuComponent } from './create-menu/create-menu.component';

import { MenusRoutingModule } from './menus-routing.module';

@NgModule({
  imports: [
    CommonModule,
    MenusRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [MenusComponent, MenuListComponent, CreateMenuComponent]
})
export class MenusModule {}
