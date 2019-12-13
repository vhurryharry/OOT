import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { MenusComponent } from './menus.component';
import { MenuListComponent } from './menu-list/menu-list.component';
import { CreateMenuComponent } from './create-menu/create-menu.component';

const routes: Routes = [
  {
    path: '',
    component: MenusComponent,
    children: [
      {
        path: 'edit/:id',
        component: CreateMenuComponent
      },
      {
        path: '',
        component: MenuListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MenusRoutingModule {}
