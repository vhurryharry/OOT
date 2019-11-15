import { Component, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../repository.service';

@Component({
  selector: 'admin-course-list',
  templateUrl: './course-list.component.html',
  styleUrls: ['./course-list.component.scss']
})
export class CourseListComponent implements OnInit {
  @ViewChild(ClrDatagrid, {static: false}) datagrid: ClrDatagrid;

  courses = [];
  selected = [];
  lastState = {};
  total: number;
  loading = true;
  showCreateCourse = false;
  showEditCourse = false;

  constructor(private repository: RepositoryService) { }

  ngOnInit() {
  }

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository
      .fetch('course', state)
      .subscribe((result: any) => {
        this.courses = result.items;
        this.total = result.total;
        this.loading = false;
      });
  }

  onCreate() {
    this.showCreateCourse = true;
  }

  onEdit() {
    console.log(this.getSelectedIds());
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
    console.log(this.selected);
  }

  onExportSelected() {
    console.log(this.selected);
  }

  getSelectedIds() {
    const ids = [];

    for (const course of this.selected) {
      ids.push(course.id);
    }

    return ids;
  }
}
