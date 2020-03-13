import { Component, OnInit, ViewChild } from "@angular/core";
import { ClrDatagrid, ClrDatagridStateInterface } from "@clr/angular";
import { RepositoryService } from "../../services/repository.service";
import { FileService } from "../../services/file.service";
import { Router } from "@angular/router";

@Component({
  selector: "admin-testimonial-list",
  templateUrl: "./testimonial-list.component.html"
})
export class TestimonialListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  testimonials = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateTestimonial = false;
  showEditTestimonial = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService,
    private router: Router
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository
      .fetch("course_testimonial", state)
      .subscribe((result: any) => {
        this.testimonials = result.items;
        this.total = result.total;
        this.deleted = result.total - result.alive;
        this.loading = false;
      });
  }

  onCreate() {
    this.router.navigate(["/testimonials/edit/0"]);
  }

  onEdit(id: string = null) {
    this.singleSelection = id ? id : this.selected[0].id;
    this.router.navigate(["/testimonials/edit/" + this.singleSelection]);
  }

  onContentUpdated() {
    this.showCreateTestimonial = false;
    this.showEditTestimonial = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete("course_testimonial", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore("course_testimonial", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export("course_testimonial", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "all_testimonials.csv");
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export("course_testimonial", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "selected_testimonials.csv");
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const testimonial of this.selected) {
      ids.push(testimonial.id);
    }

    return ids;
  }
}
