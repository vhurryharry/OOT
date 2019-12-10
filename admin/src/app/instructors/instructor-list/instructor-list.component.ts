import { Component, Input, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../repository.service';
import { FileService } from '../../file.service';

@Component({
  selector: 'admin-instructor-list',
  templateUrl: './instructor-list.component.html'
})
export class InstructorListComponent implements OnInit {
  @Input()
  type: string;

  @ViewChild(ClrDatagrid, { static: false })
  datagrid: ClrDatagrid;

  instructors = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateInstructor = false;
  showEditInstructor = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;
    const instructorState = state;
    instructorState.filters = [
      {
        property: 'type',
        value: 'instructor'
      }
    ];

    this.repository
      .fetch('customer', instructorState)
      .subscribe((result: any) => {
        this.instructors = result.items;
        this.total = result.total;
        this.deleted = result.total - result.alive;
        this.loading = false;
      });
  }

  onCreate() {
    this.showCreateInstructor = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditInstructor = true;
  }

  onContentUpdated() {
    this.showCreateInstructor = false;
    this.showEditInstructor = false;
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
        this.fileService.saveAsCsv(result.csv, 'all_instructors.csv');
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export('customer', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'selected_instructors.csv');
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const instructor of this.selected) {
      ids.push(instructor.id);
    }

    return ids;
  }
}
