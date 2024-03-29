import { Component, OnInit, ViewChild } from "@angular/core";
import { ClrDatagrid, ClrDatagridStateInterface } from "@clr/angular";
import { RepositoryService } from "../../services/repository.service";
import { FileService } from "../../services/file.service";
import { Router } from "@angular/router";

@Component({
  selector: "admin-entity-list",
  templateUrl: "./entity-list.component.html"
})
export class EntityListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  entities = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateEntity = false;
  showEditEntity = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService,
    private router: Router
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch("entity", state).subscribe((result: any) => {
      this.entities = result.items;
      this.total = result.total;
      this.deleted = result.total - result.alive;
      this.loading = false;
    });
  }

  onCreate() {
    this.router.navigate(["/pages/edit/0"]);
  }

  onEdit(entityId: string = null) {
    this.singleSelection = entityId ? entityId : this.selected[0].id;
    this.router.navigate(["/pages/edit/" + this.singleSelection]);
  }

  onContentUpdated() {
    this.showCreateEntity = false;
    this.showEditEntity = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete("entity", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore("entity", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export("entity", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "all_entities.csv");
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export("entity", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "selected_entities.csv");
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const entity of this.selected) {
      ids.push(entity.id);
    }

    return ids;
  }
}
