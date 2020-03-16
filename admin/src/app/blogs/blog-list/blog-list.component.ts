import { Component, OnInit, ViewChild } from "@angular/core";
import { ClrDatagrid, ClrDatagridStateInterface } from "@clr/angular";
import { RepositoryService } from "../../services/repository.service";
import { FileService } from "../../services/file.service";
import { Router } from "@angular/router";

@Component({
  selector: "admin-blog-list",
  templateUrl: "./blog-list.component.html"
})
export class BlogListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  blogs = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateBlog = false;
  showEditBlog = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService,
    private router: Router
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch("blog", state).subscribe((result: any) => {
      this.blogs = result.items;
      this.total = result.total;
      this.deleted = result.total - result.alive;
      this.loading = false;
    });
  }

  onCreate() {
    this.router.navigate(["/blogs/edit/0"]);
  }

  onEdit(blogId: string = null) {
    this.singleSelection = blogId ? blogId : this.selected[0].id;
    this.router.navigate(["/blogs/edit/" + this.singleSelection]);
  }

  onContentUpdated() {
    this.showCreateBlog = false;
    this.showEditBlog = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete("blog", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore("blog", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export("blog", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "all_blogs.csv");
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export("blog", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "selected_blogs.csv");
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const blog of this.selected) {
      ids.push(blog.id);
    }

    return ids;
  }
}
