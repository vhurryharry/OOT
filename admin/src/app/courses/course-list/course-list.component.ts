import { Component, OnInit, ViewChild } from "@angular/core";
import { Router } from "@angular/router";

import { ClrDatagrid, ClrDatagridStateInterface } from "@clr/angular";

import { RepositoryService } from "../../services/repository.service";
import { FileService } from "../../services/file.service";

@Component({
  selector: "admin-course-list",
  templateUrl: "./course-list.component.html"
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

  constructor(
    private repository: RepositoryService,
    private fileService: FileService,
    private router: Router
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch("course", state).subscribe((result: any) => {
      this.courses = result.items;
      this.total = result.total;
      this.deleted = result.total - result.alive;
      this.loading = false;
    });
  }

  onCreate() {
    this.router.navigate(["/courses/edit/0"]);
  }

  onEdit(courseId: string = null) {
    this.singleSelection = courseId ? courseId : this.selected[0].id;
    this.router.navigate(["/courses/edit/" + this.singleSelection]);
  }

  onOptions() {
    this.singleSelection = this.selected[0];
    this.router.navigate([
      "/courses/edit/" + this.singleSelection.id + "/options"
    ]);
  }

  onInstructors() {
    this.singleSelection = this.selected[0];
    this.router.navigate([
      "/courses/edit/" + this.singleSelection.id + "/instructors"
    ]);
  }

  onReviews() {
    this.singleSelection = this.selected[0];
    this.router.navigate([
      "/courses/edit/" + this.singleSelection.id + "/reviews"
    ]);
  }

  onContentUpdated() {
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete("course", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore("course", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export("course", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "all_courses.csv");
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export("course", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "selected_courses.csv");
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
