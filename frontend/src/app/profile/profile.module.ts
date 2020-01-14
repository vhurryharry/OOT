import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { ProfileRoutingModule } from './profile-routing.module';
import { ProfileComponent } from './profile.component';

@NgModule({
  imports: [
    ProfileRoutingModule,
    FormsModule,
    CommonModule,
    ReactiveFormsModule
  ],
  declarations: [ProfileComponent]
})
export class ProfileModule {}