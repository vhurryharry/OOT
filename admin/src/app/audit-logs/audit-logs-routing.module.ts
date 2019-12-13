import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { AuditLogsComponent } from './audit-logs.component';
import { AuditLogListComponent } from './audit-log-list/audit-log-list.component';

const routes: Routes = [
  {
    path: '',
    component: AuditLogsComponent,
    children: [
      {
        path: '',
        component: AuditLogListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuditLogsRoutingModule {}
