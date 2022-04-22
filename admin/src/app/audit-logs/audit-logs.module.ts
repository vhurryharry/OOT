import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";

import { AuditLogsComponent } from "./audit-logs.component";
import { AuditLogListComponent } from "./audit-log-list/audit-log-list.component";

import { AuditLogsRoutingModule } from "./audit-logs-routing.module";
import { ClarityModule } from "@clr/angular";

@NgModule({
  imports: [
    CommonModule,
    AuditLogsRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    ClarityModule,
  ],
  declarations: [AuditLogsComponent, AuditLogListComponent],
})
export class AuditLogsModule {}
