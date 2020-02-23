import { Component, OnInit, ViewChild } from "@angular/core";
import { ClrDatagrid, ClrDatagridStateInterface } from "@clr/angular";
import { RepositoryService } from "../../services/repository.service";
import { FileService } from "../../services/file.service";

@Component({
  selector: "admin-course-topic-list",
  templateUrl: "./course-topic-list.component.html"
})
export class CourseTopicListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  topics = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateTopic = false;
  showEditTopic = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch("course_topic", state).subscribe((result: any) => {
      this.topics = result.items;
      this.total = result.total;
      this.deleted = result.total - result.alive;
      this.loading = false;
    });
  }

  onCreate() {
    this.showCreateTopic = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditTopic = true;
  }

  onContentUpdated() {
    this.showCreateTopic = false;
    this.showEditTopic = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete("course_topic", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore("course_topic", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export("course_topic", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "all_topics.csv");
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export("course_topic", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "selected_topics.csv");
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const Topic of this.selected) {
      ids.push(Topic.id);
    }

    return ids;
  }
}
