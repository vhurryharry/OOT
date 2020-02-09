import { Component, OnInit, ViewChild } from "@angular/core";
import { ClrDatagrid, ClrDatagridStateInterface } from "@clr/angular";
import { RepositoryService } from "../../services/repository.service";
import { FileService } from "../../services/file.service";
import { Router } from "@angular/router";

@Component({
  selector: "admin-notification-list",
  templateUrl: "./notification-list.component.html"
})
export class NotificationListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  notifications = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateNotification = false;
  showEditNotification = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService,
    private router: Router
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch("notification", state).subscribe((result: any) => {
      this.notifications = result.items;
      this.total = result.total;
      this.deleted = result.total - result.alive;
      this.loading = false;
    });
  }

  onCreate() {
    this.router.navigate(["/notifications/edit/0"]);
  }

  onEdit(id: string = null) {
    this.singleSelection = id ? id : this.selected[0].id;
    this.router.navigate(["/notifications/edit/" + this.singleSelection]);
  }

  onContentUpdated() {
    this.showCreateNotification = false;
    this.showEditNotification = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete("notification", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore("notification", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export("notification", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "all_notifications.csv");
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export("notification", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "selected_notifications.csv");
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const notification of this.selected) {
      ids.push(notification.id);
    }

    return ids;
  }
}
