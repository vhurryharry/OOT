import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AuthGuard } from '../services/auth-guard.service';
import { LoginComponent } from './login.component';

@NgModule({
  imports: [CommonModule],
  declarations: [LoginComponent],
  providers: [AuthGuard]
})
export class LoginModule {}
