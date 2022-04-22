import { Component, OnInit, ViewChild } from "@angular/core";
import { ClrDatagrid, ClrDatagridStateInterface } from "@clr/angular";
import { RepositoryService } from "../../services/repository.service";
import { FileService } from "../../services/file.service";

@Component({
  selector: "admin-audit-log-list",
  templateUrl: "./audit-log-list.component.html",
})
export class AuditLogListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  auditLogs = [];
  selected = [];
  total: number;
  loading = true;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.repository.fetch("audit-log", state).subscribe((result: any) => {
      this.auditLogs = result.items;
      this.total = result.total;
      this.loading = false;
    });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export("audit-log", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "all_audit_logs.csv");
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export("audit-log", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "selected_audit_logs.csv");
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const auditLog of this.selected) {
      ids.push(auditLog.id);
    }

    return ids;
  }
}
