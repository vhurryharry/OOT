import { Component, OnInit, ViewChild } from "@angular/core";
import { Router, ActivatedRoute } from "@angular/router";
import { Location } from "@angular/common";

import { ClrDatagrid, ClrDatagridStateInterface } from "@clr/angular";

import { RepositoryService } from "../../services/repository.service";
import { FileService } from "../../services/file.service";

@Component({
  selector: "admin-manage-survey-results",
  templateUrl: "./manage-survey-results.component.html"
})
export class ManageSurveyResultsComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  results = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;

  courseId: string = null;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService,
    private route: ActivatedRoute,
    private location: Location,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.courseId = params.id;
    });
  }

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository
      .custom("survey/results", { id: this.courseId, state }, "findByCourse")
      .subscribe((result: any) => {
        this.results = result.items;
        this.total = result.total;
        this.loading = false;
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export("survey/results", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "all_results.csv");
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export("survey/results", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "selected_results.csv");
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const result of this.selected) {
      ids.push(result.id);
    }

    return ids;
  }

  goBack() {
    this.location.back();
  }
}
