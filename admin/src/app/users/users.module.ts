import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { UsersComponent } from "./users.component";
import { UserListComponent } from "./user-list/user-list.component";
import { CreateUserComponent } from "./create-user/create-user.component";

import { UsersRoutingModule } from "./users-routing.module";

@NgModule({
  imports: [
    CommonModule,
    UsersRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [UsersComponent, UserListComponent, CreateUserComponent]
})
export class UsersModule {}
