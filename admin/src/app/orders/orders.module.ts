import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { ClarityModule } from "@clr/angular";
import { CKEditorModule } from "@ckeditor/ckeditor5-angular";

import { OrdersComponent } from "./orders.component";
import { OrderListComponent } from "./order-list/order-list.component";
import { EditOrderComponent } from "./edit-order/edit-order.component";
import { OrderPaymentsComponent } from "./order-payments/order-payments.component";

import { OrdersRoutingModule } from "./orders-routing.module";

@NgModule({
  imports: [
    CommonModule,
    OrdersRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    OrdersComponent,
    OrderListComponent,
    EditOrderComponent,
    OrderPaymentsComponent
  ]
})
export class OrdersModule {}
