import { Component, OnInit, ViewChild } from "@angular/core";
import { ClrDatagrid, ClrDatagridStateInterface } from "@clr/angular";
import { RepositoryService } from "../../services/repository.service";
import { FileService } from "../../services/file.service";

@Component({
  selector: "admin-menu-list",
  templateUrl: "./menu-list.component.html"
})
export class MenuListComponent implements OnInit {
  @ViewChild(ClrDatagrid, { static: false }) datagrid: ClrDatagrid;

  menus = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  deleted: number;
  loading = true;
  showCreateMenu = false;
  showEditMenu = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch("menu", state).subscribe((result: any) => {
      this.menus = result.items;
      this.total = result.total;
      this.deleted = result.total - result.alive;
      this.loading = false;
    });
  }

  onCreate() {
    this.showCreateMenu = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditMenu = true;
  }

  onMoveUp(selected) {
    this.loading = true;
    this.repository.move("menu", selected, "up").subscribe((result: any) => {
      this.refresh(this.lastState);
      this.selected = [];
    });
  }

  onMoveDown(selected) {
    this.loading = true;
    this.repository.move("menu", selected, "down").subscribe((result: any) => {
      this.refresh(this.lastState);
      this.selected = [];
    });
  }

  onContentUpdated() {
    this.showCreateMenu = false;
    this.showEditMenu = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onDelete() {
    this.loading = true;
    this.repository
      .delete("menu", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onRestore() {
    this.loading = true;
    this.repository
      .restore("menu", this.getSelectedIds())
      .subscribe((result: any) => {
        this.refresh(this.lastState);
        this.selected = [];
      });
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export("menu", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "all_menus.csv");
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export("menu", this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, "selected_menus.csv");
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const menu of this.selected) {
      ids.push(menu.id);
    }

    return ids;
  }
}
