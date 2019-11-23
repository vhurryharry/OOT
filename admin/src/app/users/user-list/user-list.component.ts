import { Component, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../repository.service';
import { FileService } from '../../file.service';

@Component({
  selector: 'admin-user-list',
  templateUrl: './user-list.component.html'
})
export class UserListComponent implements OnInit {
  @ViewChild(ClrDatagrid, {static: false}) datagrid: ClrDatagrid;

  users = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  loading = true;
  showCreateUser = false;
  showEditUser = false;

  constructor(private repository: RepositoryService, private fileService: FileService) { }

  ngOnInit() {
  }

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository
      .fetch('user', state)
      .subscribe((result: any) => {
        this.users = result.items;
        this.total = result.total;
        this.loading = false;
      });
  }

  onCreate() {
    this.showCreateUser = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditUser = true;
  }

  onContentUpdated() {
    this.showCreateUser = false;
    this.showEditUser = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete('user', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore('user', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export('user', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'all_users.csv');
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export('user', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'selected_users.csv');
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const user of this.selected) {
      ids.push(user.id);
    }

    return ids;
  }
}
