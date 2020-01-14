import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AuthGuard } from './services/auth-guard.service';

import { CondensedComponent } from './layout/condensed/condensed.component';

const routes: Routes = [
  {
    path: '',
    component: CondensedComponent,
    loadChildren: './homepage/homepage.module#HomepageModule'
  },
  {
    path: 'auth',
    component: CondensedComponent,
    loadChildren: './auth/auth.module#AuthModule'
  },
  {
    path: 'profile',
    component: CondensedComponent,
    loadChildren: './profile/profile.module#ProfileModule'
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
