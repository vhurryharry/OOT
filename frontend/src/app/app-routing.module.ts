import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AuthGuard } from './services/auth-guard.service';

import { CondensedComponent } from './layout/condensed/condensed.component';
import { BlankComponent } from './layout/blank/blank.component';

const routes: Routes = [
  {
    path: '',
    component: CondensedComponent,
    loadChildren: './homepage/homepage.module#HomepageModule'
  },
  {
    path: '**',
    component: CondensedComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
