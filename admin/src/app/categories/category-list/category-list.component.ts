import { Component, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../repository.service';
import { FileService } from '../../file.service';

@Component({
  selector: 'admin-category-list',
  templateUrl: './category-list.component.html'
})
export class CategoryListComponent implements OnInit {
  @ViewChild(ClrDatagrid, {static: false}) datagrid: ClrDatagrid;

  categories = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  loading = true;
  showCreateCategory = false;
  showEditCategory = false;

  constructor(private repository: RepositoryService, private fileService: FileService) { }

  ngOnInit() {
  }

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository
      .fetch('category', state)
      .subscribe((result: any) => {
        this.categories = result.items;
        this.total = result.total;
        this.loading = false;
      });
  }

  onCreate() {
    this.showCreateCategory = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditCategory = true;
  }

  onContentUpdated() {
    this.showCreateCategory = false;
    this.showEditCategory = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete('category', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore('category', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export('category', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'all_categories.csv');
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export('category', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'selected_categories.csv');
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const category of this.selected) {
      ids.push(category.id);
    }

    return ids;
  }
}
