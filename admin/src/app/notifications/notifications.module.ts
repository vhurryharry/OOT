import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ClarityModule } from '@clr/angular';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';

import { NotificationsComponent } from './notifications.component';
import { NotificationListComponent } from './notification-list/notification-list.component';
import { CreateNotificationComponent } from './create-notification/create-notification.component';

import { NotificationsRoutingModule } from './notifications-routing.module';

@NgModule({
  imports: [
    CommonModule,
    NotificationsRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    NotificationsComponent,
    NotificationListComponent,
    CreateNotificationComponent
  ]
})
export class NotificationsModule {}
