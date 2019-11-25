import { Component, Input, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../repository.service';
import { FileService } from '../../file.service';

@Component({
  selector: 'admin-student-list',
  templateUrl: './student-list.component.html'
})
export class StudentListComponent implements OnInit {
  @Input()
  type: string;

  @ViewChild(ClrDatagrid, {static: false})
  datagrid: ClrDatagrid;

  students = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  loading = true;
  showCreateStudent = false;
  showEditStudent = false;

  constructor(private repository: RepositoryService, private fileService: FileService) { }

  ngOnInit() {
  }

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;
    const studentState = state;
    studentState.filters = [
      {
        property: 'type',
        value: 'instructor',
      },
    ];

    this.repository
      .fetch('customer', studentState)
      .subscribe((result: any) => {
        this.students = result.items;
        this.total = result.total;
        this.loading = false;
      });
  }

  onCreate() {
    this.showCreateStudent = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditStudent = true;
  }

  onContentUpdated() {
    this.showCreateStudent = false;
    this.showEditStudent = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete('customer', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore('customer', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export('customer', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'all_students.csv');
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export('customer', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'selected_students.csv');
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const student of this.selected) {
      ids.push(student.id);
    }

    return ids;
  }
}
