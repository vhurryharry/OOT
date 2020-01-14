import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';

import { AuthComponent } from './auth.component';
import { AuthRoutingModule } from './auth-routing.module';

@NgModule({
  imports: [AuthRoutingModule, FormsModule],
  declarations: [AuthComponent]
})
export class AuthModule {}
