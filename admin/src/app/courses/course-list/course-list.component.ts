import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';

import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';

import { RepositoryService } from '../../repository.service';
import { FileService } from '../../file.service';

@Component({
  selector: 'admin-course-list',
  templateUrl: './course-list.component.html'
})
export class CourseListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  courses = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateCourse = false;
  showEditCourse = false;
  showOptions = false;
  showInstructors = false;
  showReviews = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService,
    private router: Router
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch('course', state).subscribe((result: any) => {
      this.courses = result.items;
      this.total = result.total;
      this.deleted = result.total - result.alive;
      this.loading = false;
    });
  }

  onCreate() {
    //this.showCreateCourse = true;
    this.router.navigate(['/courses/edit/0']);
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditCourse = true;
  }

  onOptions() {
    this.singleSelection = this.selected[0];
    this.showOptions = true;
  }

  onInstructors() {
    this.singleSelection = this.selected[0];
    this.showInstructors = true;
  }

  onReviews() {
    this.singleSelection = this.selected[0];
    this.showReviews = true;
  }

  onContentUpdated() {
    this.showCreateCourse = false;
    this.showEditCourse = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete('course', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore('course', this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export('course', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'all_courses.csv');
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export('course', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'selected_courses.csv');
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const course of this.selected) {
      ids.push(course.id);
    }

    return ids;
  }
}
