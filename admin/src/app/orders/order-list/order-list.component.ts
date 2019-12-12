import { Component, Input, OnInit, ViewChild } from '@angular/core';
import { ClrDatagrid, ClrDatagridStateInterface } from '@clr/angular';
import { RepositoryService } from '../../services/repository.service';
import { FileService } from '../../services/file.service';

@Component({
  selector: 'admin-order-list',
  templateUrl: './order-list.component.html'
})
export class OrderListComponent implements OnInit {
  @Input()
  type: string;

  @ViewChild(ClrDatagrid, { static: false })
  datagrid: ClrDatagrid;

  orders = [];
  selected = [];
  singleSelection = null;
  lastState = {};
  total: number;
  loading = true;
  showViewPayment = false;
  showEditOrder = false;

  constructor(
    private repository: RepositoryService,
    private fileService: FileService
  ) {}

  ngOnInit() {}

  refresh(state: ClrDatagridStateInterface) {
    this.loading = true;
    this.lastState = state;

    this.repository.fetch('order', state).subscribe((result: any) => {
      this.orders = result.items;
      this.total = result.total;
      this.loading = false;
    });
  }

  onViewPayment() {
    this.singleSelection = this.selected[0];
    this.showViewPayment = true;
  }

  onEdit() {
    this.singleSelection = this.selected[0];
    this.showEditOrder = true;
  }

  onContentUpdated() {
    this.showViewPayment = false;
    this.showEditOrder = false;
    this.refresh(this.lastState);
    this.selected = [];
  }

  onExportAll() {
    this.loading = true;
    this.repository
      .export('order', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'all_orders.csv');
        this.loading = false;
      });
  }

  onExportSelected() {
    this.loading = true;
    this.repository
      .export('order', this.getSelectedIds())
      .subscribe((result: any) => {
        this.fileService.saveAsCsv(result.csv, 'selected_orders.csv');
        this.loading = false;
      });
  }

  getSelectedIds() {
    const ids = [];

    for (const order of this.selected) {
      ids.push(order.id);
    }

    return ids;
  }
}
