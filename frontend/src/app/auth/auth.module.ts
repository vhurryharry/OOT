import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';

import { LoginComponent } from './login/login.component';
import { AuthRoutingModule } from './auth-routing.module';
import { RegisterComponent } from './register/register.component';

@NgModule({
  imports: [AuthRoutingModule, FormsModule],
  declarations: [LoginComponent, RegisterComponent]
})
export class AuthModule {}
