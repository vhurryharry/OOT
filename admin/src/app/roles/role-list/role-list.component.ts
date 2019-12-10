import { Component, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../repository.service';
import { FileService } from '../../file.service';

@Component({
  selector: 'admin-role-list',
  templateUrl: './role-list.component.html'
})
export class RoleListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  roles = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateRole = false;
  showEditRole = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch('role', state).subscribe((result: any) => {
      this.roles = result.items;
      this.total = result.total;
      this.deleted = result.total - result.alive;
      this.loading = false;
    });
  }

  onCreate() {
    this.showCreateRole = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditRole = true;
  }

  onContentUpdated() {
    this.showCreateRole = false;
    this.showEditRole = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete('role', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore('role', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export('role', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'all_roles.csv');
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export('role', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'selected_roles.csv');
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const role of this.selected) {
      ids.push(role.id);
    }

    return ids;
  }
}
