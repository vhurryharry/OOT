import { Component, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../repository.service';
import { FileService } from '../../file.service';

@Component({
  selector: 'admin-tag-list',
  templateUrl: './tag-list.component.html'
})
export class TagListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  tags = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateTag = false;
  showEditTag = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch('tag', state).subscribe((result: any) => {
      this.tags = result.items;
      this.total = result.total;
      this.deleted = result.total - result.alive;
      this.loading = false;
    });
  }

  onCreate() {
    this.showCreateTag = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditTag = true;
  }

  onContentUpdated() {
    this.showCreateTag = false;
    this.showEditTag = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete('tag', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore('tag', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export('tag', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'all_tags.csv');
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export('tag', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'selected_tags.csv');
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const tag of this.selected) {
      ids.push(tag.id);
    }

    return ids;
  }
}
