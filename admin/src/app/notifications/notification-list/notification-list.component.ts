import { Component, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../repository.service';
import { FileService } from '../../file.service';

@Component({
  selector: 'admin-notification-list',
  templateUrl: './notification-list.component.html'
})
export class NotificationListComponent implements OnInit {
  @ViewChild(ClrDatagrid, {static: false}) datagrid: ClrDatagrid;

  notifications = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  loading = true;
  showCreateNotification = false;
  showEditNotification = false;

  constructor(private repository: RepositoryService, private fileService: FileService) { }

  ngOnInit() {
  }

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository
      .fetch('notification', state)
      .subscribe((result: any) => {
        this.notifications = result.items;
        this.total = result.total;
        this.loading = false;
      });
  }

  onCreate() {
    this.showCreateNotification = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditNotification = true;
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
      .delete('notification', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore('notification', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export('notification', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'all_notifications.csv');
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export('notification', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'selected_notifications.csv');
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