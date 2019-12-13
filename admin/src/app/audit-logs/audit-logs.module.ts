import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { AuditLogsComponent } from './audit-logs.component';
import { AuditLogListComponent } from './audit-log-list/audit-log-list.component';

import { AuditLogsRoutingModule } from './audit-logs-routing.module';

@NgModule({
  imports: [
    CommonModule,
    AuditLogsRoutingModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [AuditLogsComponent, AuditLogListComponent]
})
export class AuditLogsModule {}
