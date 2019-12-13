import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { NotificationsComponent } from './notifications.component';
import { NotificationListComponent } from './notification-list/notification-list.component';
import { CreateNotificationComponent } from './create-notification/create-notification.component';

const routes: Routes = [
  {
    path: '',
    component: NotificationsComponent,
    children: [
      {
        path: 'edit/:id',
        component: CreateNotificationComponent
      },
      {
        path: '',
        component: NotificationListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class NotificationsRoutingModule {}
