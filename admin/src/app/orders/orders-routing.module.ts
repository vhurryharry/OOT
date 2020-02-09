import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { OrdersComponent } from "./orders.component";
import { OrderListComponent } from "./order-list/order-list.component";
import { EditOrderComponent } from "./edit-order/edit-order.component";
import { OrderPaymentsComponent } from "./order-payments/order-payments.component";

const routes: Routes = [
  {
    path: "",
    component: OrdersComponent,
    children: [
      {
        path: "edit/:id",
        component: EditOrderComponent
      },
      {
        path: "pay/:id",
        component: OrderPaymentsComponent
      },
      {
        path: "",
        component: OrderListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class OrdersRoutingModule {}
