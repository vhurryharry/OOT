import { Component, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-user-list',
  templateUrl: './user-list.component.html'
})
export class UserListComponent implements OnInit {
  @ViewChild(ClrDatagrid, {static: false}) datagrid: ClrDatagrid;

  users = [];
  selected = [];
  lastState = {};
  total: number;
  loading = true;
  showCreateUser = false;
  showEditUser = false;

  constructor(private repository: RepositoryService) { }

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
    console.log(this.getSelectedIds());
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
    console.log(this.selected);
  }

  onExportSelected() {
    console.log(this.selected);
  }

  getSelectedIds() {
    const ids = [];

    for (const user of this.selected) {
      ids.push(user.id);
    }

    return ids;
  }
}
